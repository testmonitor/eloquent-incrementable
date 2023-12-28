# Incrementable Eloquent models

[![Latest Stable Version](https://poser.pugx.org/testmonitor/eloquent-incrementable/v/stable)](https://packagist.org/packages/testmonitor/eloquent-incrementable)
[![Travis Build](https://travis-ci.org/testmonitor/eloquent-incrementable.svg?branch=master)](https://app.travis-ci.com/github/testmonitor/eloquent-incrementable)
[![Code Quality](https://scrutinizer-ci.com/g/testmonitor/eloquent-incrementable/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/testmonitor/eloquent-incrementable/?branch=master)
[![StyleCI](https://styleci.io/repos/89586066/shield)](https://styleci.io/repos/89586066)
[![License](https://poser.pugx.org/testmonitor/eloquent-incrementable/license)](https://packagist.org/packages/eloquent-incrementable)

Define a custom auto-increment field in your Eloquent model, that is determined through PHP
rather than your database engine.

Furthermore, by making use of increment groups, you can restart counting in-table based on
other fields. Consider this example:

| id | **code** | project_id |
|----|:--------:|:----------:|
| 1  | **1**    | 1          |
| 2  | **2**    | 1          |
| 3  | **3**    | 1          |
| 4  | **1**    | 2          |
| 5  | **2**    | 2          |

Imagine a bug tracking application that stores each bug in a single table, but is represented
on a per-project basis. You'll want start each project with a fresh bug count, while maintaining
a unique database id. Incrementable will enable you to automatically reset the `code` counter
once a new `project_id` is defined.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Examples](#examples)
- [Tests](#tests)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

This package can be installed through Composer:

```sh
$ composer require testmonitor/eloquent-incrementable
```

## Usage

In order to add Incrementable to your Eloquent model, you'll need to:<br />

1. Use the trait ```TestMonitor\Incrementable\Traits\Incrementable``` on your model(s).
2. Configure the incrementable field *(note: make sure its an integer column)*.
3. Optionally, add one or more increment groups.

Add the Incrementable trait on the models you want to track:

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use TestMonitor\Incrementable\Traits\Incrementable;

class Bug extends Model
{
    use Incrementable, SoftDeletes;

    protected $table = 'bugs';

    protected $incrementable = 'code';

    // This will cause the code to reset once
    // a new project_id is found.
    protected $incrementableGroup = ['project_id'];
}
```

In order to avoid collisions, Incrementable will preserve the count for a
soft-deleted model. Although this will cause a gap between this and the
next model, it will ensure uniqueness when the model is restored.

## Examples

In this example, we have set up the following:

- A table containing a `name` and `code` field.
- An Eloquent model called `App\Bug`, which uses the Incrementable trait
- A property on the Bug model: `$incrementable = 'code'`

We can now run this example:

```php
$bug = new App\Bug(['name' => 'It doesn\'t work.']);
$bug->save();

// Will show '1'
echo $bug->code;

$bug = new App\Bug(['name' => 'It really doesn\'t work.']);
$bug->save();

// Will show '2'
echo $bug->code;
```

## Tests

The package contains integration tests. You can run them using PHPUnit.

```
$ vendor/bin/phpunit
```

## Changelog

Refer to [CHANGELOG](CHANGELOG.md) for more information.

## Contributing

Refer to [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

## Credits

- [Thijs Kok](https://www.testmonitor.com/)
- [Stephan Grootveld](https://www.testmonitor.com/)
- [Frank Keulen](https://www.testmonitor.com/)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Refer to the [License](LICENSE.md) for more information.
