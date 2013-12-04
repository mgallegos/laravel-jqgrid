# Laravel 4 jqGrid package

[![Latest Stable Version](https://poser.pugx.org/mgallegos/laravel-jqgrid/v/stable.png)](https://packagist.org/packages/mgallegos/laravel-jqgrid) [![Total Downloads](https://poser.pugx.org/mgallegos/laravel-jqgrid/downloads.png)](https://packagist.org/packages/mgallegos/laravel-jqgrid)

A Laravel 4 package implementation of the popular jQuery Grid Plugin (jqGrid).

## Requirements

* [Laravel 4 Framework](https://github.com/laravel/laravel)
* [jQuery Grid Plugin v4.5.2 or later](http://www.trirand.com/blog/)

## Features

* Config file with global properties to use in all grids of your application.
* PHP Render to handle javascript code.
* Datasource independent (you are able to create your own datasource implementation).

## Installation

Require this package in your composer.json and run composer update:

    "mgallegos/laravel-jqgrid": "dev-master"

After updating composer, add the ServiceProvider to the providers array in app/config/app.php

    'Mgallegos\LaravelJqgrid\LaravelJqgridServiceProvider',

Finally, add the Render Facade to the aliases array in app/config/app.php:

    'GridRender' => 'Mgallegos\LaravelJqgrid\Facades\GridRender',

## Usage

...

## Example

...

## License

Laravel jqGrid package is open source software licensed under the MIT License.
