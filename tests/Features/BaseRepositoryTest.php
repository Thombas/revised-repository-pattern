<?php

namespace Thombas\RevisedRepositoryPattern\Tests\Features;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;
use Thombas\RevisedRepositoryPattern\Repository\BaseRepository;

class DummyModel extends Model {}

class DummyRepositoryWithModel extends BaseRepository {
    protected function baseFolder(): string
    {
        return 'TestFolder';
    }
}

class DummyRepositoryWithoutModel extends BaseRepository {
    protected function baseFolder(): string
    {
        return 'TestFolder';
    }
}

it('registers and calls model method based on folder structure', function () {
    $dummyModel = new DummyModel();
    $directory = app_path("TestFolder/Models/" . class_basename($dummyModel));
    $expectedClass = "App\\TestFolder\\Models\\" . class_basename($dummyModel) . "\\TestMethod";

    $this->prepareMethodFile($directory, $expectedClass, 'modelMethodCalled');

    $repository = new DummyRepositoryWithModel($dummyModel);
    $result = $repository->testMethod();

    expect($result)->toBe('modelMethodCalled');
});

it('registers and calls default method based on folder structure', function () {
    $directory = app_path('TestFolder');
    $expectedClass = "App\\TestFolder\\TestMethod";

    $this->prepareMethodFile($directory, $expectedClass, 'defaultMethodCalled');

    $repository = new DummyRepositoryWithoutModel();
    $result = $repository->testMethod();

    expect($result)->toBe('defaultMethodCalled');
});

it('throws exception when method does not exist', function () {
    $repository = new DummyRepositoryWithoutModel();

    expect(fn() => $repository->nonExistentMethod())
        ->toThrow(BadMethodCallException::class, "Method nonExistentMethod does not exist");
});