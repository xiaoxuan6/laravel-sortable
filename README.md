# Sortable behaviour for Eloquent models



This package provides a trait that adds sortable behaviour to an Eloquent model.

The value of the order column of a new record of a model is determined by the maximum value of the order column of all records of that model + 1.

## Installation

This package can be installed through Composer.

```
composer require laravel-sortable
```

## Usage

To add sortable behaviour to your model you must:
1. Use the trait `James\Sortable\SortableTrait;`.
2. Optionally specify which column will be used as the order column. The default is `sort_field`.

### Example

```php
James\Sortable\SortableTrait

class MyModel extends Eloquent
{

    use SortableTrait;

    public $sortable = [
        'sort_field' => 'view',
        'sort_when_creating' => true,
    ];
    
    ...
}
```

If you don't set a value `$sortable['sort_field']` the package will assume that your order column name will be named `order_column`.

If you don't set a value `$sortable['sort_when_creating']` the package will automatically assign the highest order number to a new model;

Assuming that the db-table for `MyModel` is empty:

```php
$myModel = new MyModel();
$myModel->save(); // order_column for this record will be set to 1

$myModel = new MyModel();
$myModel->save(); // order_column for this record will be set to 2

$myModel = new MyModel();
$myModel->save(); // order_column for this record will be set to 3

```
You can also move a model:

```php

$myModel = new MyModel();
$myModel->where('id', $id)->move('up'); // up、down、top、end
```


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

