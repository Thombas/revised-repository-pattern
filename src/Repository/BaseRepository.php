<?php

namespace Thombas\RevisedRepositoryPattern\Repository;

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
    
    private function registerModelMethods(): void
    {
        $baseFolder = $this->baseFolder();
        $basePath = class_basename($this->model);
        $directory = app_path("{$baseFolder}/Models/{$basePath}");
    
        if (!File::exists($directory) || !File::isDirectory($directory)) {
            return;
        }
    
        foreach (File::allFiles($directory) as $file) {
            $relative = $this->getRelativePath($file->getPathname(), $directory);
            $methodName = $this->buildMethodName($relative);
            $className = $this->buildModelClassName($relative, $baseFolder, $basePath);
    
            $this->methods[$methodName] = function (...$args) use ($className) {
                return app($className, array_merge(['model' => $this->model], $args))->__invoke();
            };
        }
    }
    
    private function registerDefaultMethods(): void
    {
        $baseFolder = $this->baseFolder();
        $directory = app_path($baseFolder);

        if (!File::exists($directory)) {
            return;
        }
    
        foreach (File::allFiles($directory) as $file) {
            $relative = $this->getRelativePath($file->getPathname(), $directory);
            if (in_array('Models', explode(DIRECTORY_SEPARATOR, $relative))) {
                continue;
            }
            $methodName = $this->buildMethodName($relative);
            $className = $this->buildDefaultClassName($relative, $baseFolder);
    
            $this->methods[$methodName] = function (...$args) use ($className) {
                return app($className, $args)->__invoke();
            };
        }
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