<?php

namespace ThomasFielding\RevisedRepositoryPattern\Tests\Traits;

use Illuminate\Support\Facades\File;

trait CanPrepareFiles
{
    protected function prepareMethodFile(
        string $directory,
        string $expectedClass,
        string $methodReturn
    ): void {
        $dummyFilePath = $directory . DIRECTORY_SEPARATOR . 'TestMethod.php';
        $dummyFile = new \SplFileInfo($dummyFilePath);

        File::shouldReceive('exists')
            ->once()
            ->with($directory)
            ->andReturn(true);

        if (strpos($directory, 'Models') !== false) {
            File::shouldReceive('isDirectory')
                ->once()
                ->with($directory)
                ->andReturn(true);
        }

        File::shouldReceive('allFiles')
            ->once()
            ->with($directory)
            ->andReturn([$dummyFile]);

        if (!class_exists($expectedClass)) {
            $parts = explode('\\', $expectedClass);
            $className = array_pop($parts);
            $namespace = implode('\\', $parts);

            eval("
                namespace {$namespace};
                class {$className} {
                    public function __construct(\$params = []) {}
                    public function __invoke() {
                        return '{$methodReturn}';
                    }
                }
            ");
        }
    }
}