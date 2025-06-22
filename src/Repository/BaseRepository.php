<?php

namespace Thombas\RevisedRepositoryPattern\Repository;

use ReflectionClass;
use BadMethodCallException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    public function __construct(
        protected ?Model $model = null,
        protected array $methods = [],
    ) {
        if ($this->model) {
            $this->registerModelMethods();
            return;
        }
        
        $this->registerDefaultMethods();
    }

    public function __call(
        string $name,
        array $arguments,
    ) {
        if (isset($this->methods[$name])) {
            return ($this->methods[$name])(...$arguments);
        }

        throw new BadMethodCallException("Method $name does not exist");
    }

    abstract protected function baseFolder(): string;
    
    private function registerActions(
        string $baseRelativePath,
        bool $skipModels,
        callable $classResolver,
        bool $withModel
    ): void {
        $directory = app_path($baseRelativePath);

        if (!File::isDirectory($directory)) {
            return;
        }

        foreach (File::allFiles($directory) as $file) {
            $relative = $this->getRelativePath($file->getPathname(), $directory);

            if ($skipModels
                && in_array('Models', explode(DIRECTORY_SEPARATOR, $relative), true)
            ) {
                continue;
            }

            $methodName = $this->buildMethodName($relative);
            $className  = $classResolver($relative);

            $ctorParams = (new ReflectionClass($className))
                ->getConstructor()?->getParameters()
                ?? [];

            $allowed = [];
            foreach ($ctorParams as $p) {
                if ($p->isVariadic()) {
                    $allowed = null;
                    break;
                }
                $allowed[$p->getName()] = true;
            }

            $this->methods[$methodName] = $withModel
                ? (
                    $allowed === null
                        ? function (mixed ...$args) use ($className) {
                            $params = ['model' => $this->model] + $args;
                            return (new $className(...$params))->__invoke();
                        }
                        : function (mixed ...$args) use ($className, $allowed) {
                            $params = ['model' => $this->model] + $args;
                            $params = array_intersect_key($params, $allowed);
                            return (new $className(...$params))->__invoke();
                        }
                )
                : (
                    $allowed === null
                        ? function (mixed ...$args) use ($className) {
                            return (new $className(...$args))->__invoke();
                        }
                        : function (mixed ...$args) use ($className, $allowed) {
                            $params = array_intersect_key($args, $allowed);
                            return (new $className(...$params))->__invoke();
                        }
                );
        }
    }

    private function registerModelMethods(): void
    {
        $baseFolder = $this->baseFolder();
        $basePath   = class_basename($this->model);

        $this->registerActions(
            "{$baseFolder}/Models/{$basePath}",
            false,
            fn($relative) => $this->buildModelClassName($relative, $baseFolder, $basePath),
            true
        );
    }

    private function registerDefaultMethods(): void
    {
        $baseFolder = $this->baseFolder();

        $this->registerActions(
            $baseFolder,
            true,
            fn($relative) => $this->buildDefaultClassName($relative, $baseFolder),
            false
        );
    }
    
    private function getRelativePath(string $path, string $directory): string
    {
        return str_replace($directory . DIRECTORY_SEPARATOR, '', $path);
    }
    
    private function buildMethodName(string $relative): string
    {
        $parts = explode(DIRECTORY_SEPARATOR, $relative);
        $fileName = pathinfo(array_pop($parts), PATHINFO_FILENAME);
        return implode('', array_map(fn($part) => Str::camel($part), $parts)) . Str::camel($fileName);
    }
    
    private function buildModelClassName(string $relative, string $baseFolder, string $basePath): string
    {
        $classPath = str_replace(DIRECTORY_SEPARATOR, '\\', str_replace('.php', '', $relative));
        return "App\\{$baseFolder}\\Models\\{$basePath}\\" . $classPath;
    }
    
    private function buildDefaultClassName(string $relative, string $baseFolder): string
    {
        $classPath = str_replace(['/', '\\'], '\\', str_replace('.php', '', $relative));
        return "App\\{$baseFolder}\\" . $classPath;
    }
}