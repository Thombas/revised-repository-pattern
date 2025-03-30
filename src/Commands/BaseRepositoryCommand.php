<?php

namespace Thombas\RevisedRepositoryPattern\Commands;

use Illuminate\Console\Command;

abstract class BaseRepositoryCommand extends Command
{
    abstract protected function getNamespace(): string;

    abstract protected function getNameString(): string;

    abstract protected function getPath(): string;

    abstract protected function getExtend(?string $model): string;

    public function handle()
    {
        $name = $this->argument('name');
        $model = $this->option('model');
        $dir = $this->option('dir');

        $namespace = $this->getNamespace();
        $path = $this->getPath();
        $extend = $this->getExtend(model: $model);

        $constructor = file_get_contents(__DIR__ . '/../../resources/stubs/constructor-without-model.stub');

        if (!empty($model)) {
            $namespace .= '\\Models\\' . $model;
            $path .= '/Models/' . $model;

            $constructor = file_get_contents(__DIR__ . '/../../resources/stubs/constructor-with-model.stub');
        }

        if (!empty($dir)) {
            $namespace .= '\\' . str_replace('/', '\\', $dir);
            $path .= '/' . $dir;
        }

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $stubPath = __DIR__ . '/../../resources/stubs/repository-' . $this->getNameString() . '.stub';

        if (!file_exists($stubPath)) {
            $this->error("Stub file not found at: $stubPath");
            return;
        }

        $stub = file_get_contents($stubPath);

        $stub = str_replace('{namespace}', $namespace, $stub);
        $stub = str_replace('{class}', $name, $stub);
        $stub = str_replace('{extend}', $extend, $stub);
        $stub = str_replace('{extension}', substr(strrchr($extend, '\\'), 1), $stub);
        $stub = str_replace('{constructor}', $constructor, $stub);

        $filePath = $path . '/' . $name . '.php';

        if (file_exists($filePath)) {
            $this->error("File already exists: $filePath");
            return;
        }

        file_put_contents($filePath, $stub);
        $this->info("Repository " . $this->getNameString() . " created successfully at: $filePath");
    }
}