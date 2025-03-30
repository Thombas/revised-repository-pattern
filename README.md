# Revised Repository Pattern for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thomasfielding/revised-repository-pattern.svg?style=flat-square)](https://packagist.org/packages/thomasfielding/revised-repository-pattern)
[![Total Downloads](https://img.shields.io/packagist/dt/thomasfielding/revised-repository-pattern.svg?style=flat-square)](https://packagist.org/packages/thomasfielding/revised-repository-pattern)
[![License: MIT](https://img.shields.io/github/license/Thombas/revised-repository-pattern?style=flat-square)](https://github.com/Thombas/revised-repository-pattern/blob/main/LICENSE)

---

## Introduction

The **Revised Repository Pattern** is a Laravel package designed to help you organize your model-related logic without sacrificing the native elegance of Laravel's Eloquent models.

This package is perfect if you want:

✅ Cleaner, more structured model actions and queries  
✅ Singleton-based repository access  
✅ Full compatibility with Laravel's native models

---

## Features

- Lightweight and non-intrusive
- Extends Laravel's Eloquent model system
- Clean repository interface via Singleton pattern
- Artisan commands for generating Actions & Queries
- Supports standalone Actions & Queries without model attachment

---

## Installation

```bash
composer require thombas/revised-repository-pattern
```

---

## Getting Started

### ✅ Step 1 — Add the Trait to Your Model

Add the trait to any model you want to use the repository pattern with:

```php
use Thombas\RevisedRepositoryPattern\Traits\ImplementsRevisedRepositoryPattern;

class User extends Model
{
    use ImplementsRevisedRepositoryPattern;
}
```

> 💡 If you have a base model, you can add it there to apply to all models.

---

### ✅ Step 2 — Accessing the Repository

You can access the repository both statically and through an instance:

```php
User::repository(); // Static
$user->repository(); // Instance
```

Or, for general actions/queries:

```php
\Thombas\RevisedRepositoryPattern\Repository\Repository::action();
```

---

### ✅ Step 3 — Generate Actions & Queries

By default, actions and queries will live under:

```
App\Actions
App\Queries
```

You can manually create these folders or use Artisan:

```bash
php artisan repository:action ActionName --model=User
php artisan repository:query QueryName --model=User
```

#### Optional:
Generate into subdirectories:

```bash
php artisan repository:action ActionName --model=User --dir=Crud/Create
```

Resulting namespace:

```
App\Actions\Models\User\Crud\Create
```

---

### ✅ Step 4 — Writing an Action

Actions are simple classes where you define model-specific logic inside an `__invoke()` method:

```php
public function __invoke(): mixed
{
    $this->model->create([
        'first_name' => 'Thomas',
        'last_name' => 'Fielding',
    ]);
}
```

#### Adding Parameters to an Action:

```php
public function __construct(
    public Model $model,
    public string $firstName,
    public string $lastName,
) {
    parent::__construct(model: $model);
}

public function __invoke(): mixed
{
    $this->model->create([
        'first_name' => $this->firstName,
        'last_name'  => $this->lastName,
    ]);
}
```

> ℹ️ Always call `parent::__construct()` when using the constructor.

#### Calling an Action with Parameters:

```php
$user->repository()->action()->createAndEmail(
    firstName: 'Thomas',
    lastName: 'Fielding'
);
```

---

### ✅ Step 5 — Writing a Query

Queries let you encapsulate common SQL logic:

```php
public function __invoke(): EloquentBuilder|QueryBuilder
{
    return $this->model
        ->query()
        ->where('first_name', 'Thomas');
}
```

#### Query with Parameters:

```php
public function __construct(
    public Model $model,
    public string $firstName,
    public string $lastName,
) {
    parent::__construct(model: $model);
}

public function __invoke(): mixed
{
    return $this->model
        ->query()
        ->where('first_name', $this->firstName)
        ->where('last_name', $this->lastName);
}
```

---

### ✅ Model-Detached Query Example:

If your query doesn't need a model:

```php
public function __construct(
    public string $firstName,
    public string $lastName,
) {
    parent::__construct(model: null);
}

public function __invoke(): mixed
{
    return User::query()
        ->where('first_name', $this->firstName)
        ->where('last_name', $this->lastName);
}
```

> ⚠️ Requires extending the `BaseQuery` class instead of `ModelQuery`.

---

### ✅ Calling a Query with Parameters:

```php
$user->repository()->query()->name(
    firstName: 'Thomas',
    lastName: 'Fielding'
);
```

---

## 🟣 Full Example

```php
User::repository()->action()->doSomethingCustom();
```

---

## 📜 Changelog

See the [CHANGELOG](https://github.com/Thombas/revised-repository-pattern/blob/main/CHANGELOG.md) for updates.

---

## 🤝 Contributing

Contributions are welcome!  
See [CONTRIBUTING.md](https://github.com/Thombas/revised-repository-pattern/blob/main/.github/CONTRIBUTING.md) for guidelines.

---

## 🔒 Security

If you find a security vulnerability, please check our [Security Policy](https://github.com/Thombas/revised-repository-pattern/security/policy).

---

## 🏆 Credits

- [Thomas Fielding](https://github.com/Thombas)

---

## License

This package is open-sourced software licensed under the **MIT License**.  
See the [LICENSE file](https://github.com/Thombas/revised-repository-pattern/blob/main/LICENSE.md) for full details.
