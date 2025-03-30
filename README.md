# Revised Repository Pattern for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thomasfielding/revised-repository-pattern.svg?style=flat-square)](https://packagist.org/packages/thomasfielding/revised-repository-pattern)
[![Total Downloads](https://img.shields.io/packagist/dt/thomasfielding/revised-repository-pattern.svg?style=flat-square)](https://packagist.org/packages/thomasfielding/revised-repository-pattern)

---

## Introduction

The **Revised Repository Pattern** is a Laravel plugin that helps you organize your model-related logic in a cleaner, more structured way — without abandoning Laravel's native models.  

If you've ever found yourself wanting:
- A simple way to organize model-specific actions and queries.
- A singleton-based approach to working with repositories.
- To stick closely to Laravel's familiar model system.

Then this package is made for you.

---

## Features

- Lightweight and non-intrusive
- Builds on top of Laravel's Eloquent models
- Simple setup
- Provides a clean repository interface via singleton pattern
- Artisan commands for generating Actions and Queries

---

## Installation

You can install the package via Composer:

```bash
composer require thomasfielding/revised-repository-pattern
```

---

## Setup

### Step 1 — Add the Trait to Your Model

Add the trait to any model that you want to use the repository pattern with.  
If you have a base model, you can apply it there to make it available to all models at once.

```php
<?php

namespace App\Models;

use ThomasFielding\RevisedRepositoryPattern\Traits\ImplementsRevisedRepositoryPattern;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use ImplementsRevisedRepositoryPattern;
}
```

---

### Step 2 — Accessing the Repository

Once the trait is added, you can access the repository directly from any model instance, or statically:

```php
User::repository();
```

or

```php
$user->repository();
```

You can also access this without a model, incase you have actions of queries that don't logically belong with a single model file.

```php
\ThomasFielding\RevisedRepositoryPattern\Repository\Repository::action();
```

In this example it is calling an action, but you can call queries or other logic the exact same way.

---

### Step 3 — Creating Actions and Queries

The pattern is designed to separate your actions and queries into their own dedicated classes.

By default, the plugin expects:

- `App\Actions`
- `App\Queries`

You can create these folders manually, or generate them automatically using the provided Artisan commands:

```bash
php artisan repository:action ActionName --model=User
php artisan repository:query QueryName --model=User
```

These commands will create boilerplate classes in the correct namespace.  If you do not want to attach a model, simply leave the flag out.

You can also create subdirectories using the optional dir flag.

```bash
php artisan repository:action ActionName --model=User --dir=Crud/Create
```

This will change the namespace to `App/Actions/Models/User/Crud/Create`

---

## Step 4 — Defining an Action

Actions are where you place the logic that you want to organize for your model.  
Each action is built around the `__invoke()` method, which is the entry point that gets called automatically.

Here’s a simple example:

```php
public function __invoke(): mixed
{
    $this->model->create([
        'first_name' => 'Thomas',
        'last_name'  => 'Fielding',
    ]);
}
```

By default, the action has access to the model instance it was called from (unless you specifically chose not to generate a model when creating the action).  

---

### Using Parameters in Your Action

If you want your action to accept additional parameters, you can define them directly through the constructor:

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

> **Important:**  
> Always remember to call the `parent::__construct()` to correctly initialize the model property.

---

### Calling the Action with Parameters

Once set up, you can call the action and pass parameters like this:

```php
$user->repository()->action()->createAndEmail(
    firstName: 'Thomas',
    lastName: 'Fielding'
);
```

This will automatically inject the parameters into your action and run the logic inside `__invoke()`.

---

## Step 5 — Defining a Query

Queries are where you setup reusable sql queries that use set scopes, with behaviours modified by passed variables.  
Each query is built around the `__invoke()` method, which is the entry point that gets called automatically.

Here’s a simple example:

```php
public function __invoke(): EloquentBuilder|QueryBuilder
{
    return $this->model
        ->query()
        ->where('first_name', 'Thomas');
}
```

By default, the query has access to the model instance it was called from (unless you specifically chose not to generate a model when creating the query).  

---

### Using Parameters in Your Query

If you want your action to accept additional parameters, you can define them directly through the constructor:

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

> **Important:**  
> Always remember to call the `parent::__construct()` to correctly initialize the model property.

---

### Use a model-detached query

You can separate the need from a specific model using the following:

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

> **Important:**  
> Always remember to call the `parent::__construct()` to correctly initialize the model property.

> **When to use this:**  
> This will only work if you extend the `BaseQuery` rather than `ModelQuery` class.  This is useful if you have a complex query that doesn't belong to a singular model, or if the logic belongs to one model but the results come from another.

---

### Calling the Query with Parameters

Once set up, you can call the query and pass parameters like this:

```php
$user->repository()->query()->name(
    firstName: 'Thomas',
    lastName: 'Fielding'
);
```

This will automatically inject the parameters into your query and run the logic inside `__invoke()`.

---

## Example

```php
// Example method you'd define for your action
User::repository()->action()->doSomethingCustom();
```

---

## Changelog

See the [CHANGELOG](https://github.com/thomasfielding/revised-repository-pattern/blob/HEAD/CHANGELOG.md) for recent changes.

---

## Contributing

Contributions are welcome!  
Please read the [CONTRIBUTING guide](https://github.com/thomasfielding/revised-repository-pattern/blob/HEAD/.github/CONTRIBUTING.md) for details.

---

## Security

If you discover any security issues, please refer to our [Security Policy](https://github.com/thomasfielding/revised-repository-pattern/security/policy).

---

## Credits

- [Thomas Fielding](https://github.com/thomasfielding)

---

## License

The MIT License (MIT).  
See the [LICENSE file](https://github.com/thomasfielding/revised-repository-pattern/blob/HEAD/LICENSE.md) for more details.
