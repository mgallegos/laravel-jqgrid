# Laravel 4 jqGrid package

[![Latest Stable Version](https://poser.pugx.org/mgallegos/laravel-jqgrid/v/stable.png)](https://packagist.org/packages/mgallegos/laravel-jqgrid) [![Total Downloads](https://poser.pugx.org/mgallegos/laravel-jqgrid/downloads.png)](https://packagist.org/packages/mgallegos/laravel-jqgrid)

A Laravel 4 package implementation of the popular jQuery Grid Plugin (jqGrid).

![Image](https://raw.github.com/mgallegos/laravel-jqgrid/master/jqGrid.png)

## Requirements

* [Laravel 4 Framework](https://github.com/laravel/laravel)
* [jQuery Grid Plugin v4.5.2 or later](http://www.trirand.com/blog/)

## Features

* Config file with global properties to use in all grids of your application.
* PHP Render to handle javascript code.
* Datasource independent (you are able to create your own datasource implementation).

## Live Demo

A live demo of Laravel 4 jqGrid package is available at the following address: http://goo.gl/s8uNBR

The source code of the demo is available [here](https://github.com/mgallegos/laravel-jqgrid-demo).

## Installation

Require this package in your composer.json and run composer update:

    "mgallegos/laravel-jqgrid": "dev-master"

After updating composer, add the ServiceProvider to the providers array in app/config/app.php

    'Mgallegos\LaravelJqgrid\LaravelJqgridServiceProvider',

Add the Render Facade to the aliases array in app/config/app.php:

    'GridRender' => 'Mgallegos\LaravelJqgrid\Facades\GridRender',

Optionally, run the following command if you wish to overwrite the default config properties:
    
	php artisan config:publish mgallegos/laravel-jqgrid/

## Usage

### Step 1: Use the jqgrid render to create a grid in your application.

 Let's create the view myview.blade.php:
```php
{{ 
    GridRender::setGridId("myFirstGrid")
    		->enablefilterToolbar()
    		->setGridOption('url',URL::to('example/grid-data'))
    		->setGridOption('rowNum',5)
    		->setGridOption('shrinkToFit',false)
    		->setGridOption('sortname','id')
    		->setGridOption('caption','LaravelJqGrid example')
    		->setNavigatorOptions('navigator', array('viewtext'=>'view'))
    		->setNavigatorOptions('view',array('closeOnEscape'=>false))
    		->setFilterToolbarOptions(array('autosearch'=>true))
    		->setGridEvent('gridComplete', 'function(){alert("Grid complete event");}') 
    		->setNavigatorEvent('view', 'beforeShowForm', 'function(){alert("Before show form");}')
    		->setFilterToolbarEvent('beforeSearch', 'function(){alert("Before search event");}') 
    		->addColumn(array('index'=>'id', 'width'=>55))
    		->addColumn(array('name'=>'product','width'=>100))
    		->addColumn(array('name'=>'amount','index'=>'amount', 'width'=>80, 'align'=>'right'))
    		->addColumn(array('name'=>'total','index'=>'total', 'width'=>80))
    		->addColumn(array('name'=>'note','index'=>'note', 'width'=>55,'searchoptions'=>array('attr'=>array('title'=>'Note title'))))
    		->renderGrid(); 
}}
```
You can see the documentation of each method in the [RenderInterface source code](src/Mgallegos/LaravelJqgrid/Renders/RenderInterface.php).
>   Note: This package will **NOT** include the `jquery.jqGrid.min.js`, that is your work to do.

### Step 2: Create a class that implements the "RepositoryInterface".

Create your own datasource implementation, just remember to take into account all parameter received by both methods and the expected type of the return value.

Let's create the class ExampleRepository:
```php
<?php namespace Example;
use Mgallegos\LaravelJqgrid\Repositories\RepositoryInterface;

class ExampleRepository implements RepositoryInterface{
	
	/**
	 * Calculate the number of rows. It's used for paging the result.
	 *
	 * @param  array $filters
	 *	An array of filters, example: array(array('field'=>'column index/name 1','op'=>'operator','data'=>'searched string column 1'), array('field'=>'column index/name 2','op'=>'operator','data'=>'searched string column 2'))
	 *	The 'field' key will contain the 'index' column property if is set, otherwise the 'name' column property.
	 *	The 'op' key will contain one of the following operators: '=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'is in', 'is not in'.
	 *	when the 'operator' is 'like' the 'data' already contains the '%' character in the appropiate position.
	 *	The 'data' key will contain the string searched by the user.
	 * @return integer
	 *	Total number of rows
	 */
	public function getTotalNumberOfRows(array $filters = array())
	{
		return 5;
	}
	
	
	/**
	 * Get the rows data to be shown in the grid.
	 *
	 * @param  integer $limit
	 *	Number of rows to be shown into the grid
	 * @param  integer $offset
	 *	Start position
	 * @param  string $orderBy
	 *	Column name to order by.
	 * @param  array $sord
	 *	Sorting order
	 * @param  array $filters
	 *	An array of filters, example: array(array('field'=>'column index/name 1','op'=>'operator','data'=>'searched string column 1'), array('field'=>'column index/name 2','op'=>'operator','data'=>'searched string column 2'))
	 *	The 'field' key will contain the 'index' column property if is set, otherwise the 'name' column property.
	 *	The 'op' key will contain one of the following operators: '=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'is in', 'is not in'.
	 *	when the 'operator' is 'like' the 'data' already contains the '%' character in the appropiate position.
	 *	The 'data' key will contain the string searched by the user.
	 * @return array
	 *	An array of array, each array will have the data of a row.
	 *	Example: array(array('row 1 col 1','row 1 col 2'), array('row 2 col 1','row 2 col 2'))
	 */
	public function getRows($limit, $offset, $orderBy = null, $sord = null, array $filters = array())
	{
		return array(
					array('1-1', '1-2' , '1-3', '1-4', '1-5'),
					array('2-1', '2-2' , '2-3', '2-4', '2-5'),
					array('3-1', '3-2' , '3-3', '3-4', '3-5'),
					array('4-1', '4-2' , '4-3', '4-4', '4-5'),
					array('5-1', '5-2' , '5-3', '5-4', '5-5'),
				);
	}
	
}
```
If you are using [Query Builder](http://laravel.com/docs/queries) or [Eloquent ORM](http://laravel.com/docs/eloquent) to implement your repository, your class can extends the [EloquentRepositoryAbstract](src/Mgallegos/LaravelJqgrid/Repositoies/EloquentRepositoryAbstract.php) class as it will do all the heavy lifting for you.

If you are using [Query Builder](http://laravel.com/docs/queries), your repository class should look like this:
```php
<?php namespace Example;
use Mgallegos\LaravelJqgrid\Repositories\EloquentRepositoryAbstract;
use \Illuminate\Support\Facades\DB;

class ExampleRepository extends EloquentRepositoryAbstract {

	public function __construct()
	{
		$this->Database = DB::table('table_1')
				             ->join('table_2', 'table_1.id', '=', 'table_2.id');
											
		$this->visibleColumns = array('column_1','column_2','column_3');
		
		$this->orderBy = array(array('table_1.id', 'asc'), array('table_1.name', 'desc'));
	}

}
```
And if you are using [Eloquent ORM](http://laravel.com/docs/eloquent), your repository class should look like this:
```php
<?php namespace Example;

use Illuminate\Database\Eloquent\Model;
use Mgallegos\LaravelJqgrid\Repositories\EloquentRepositoryAbstract;

class ExampleRepository extends EloquentRepositoryAbstract {

	public function __construct()
	{
		$this->Database = new YOUR_DATABASE_MODEL;
											
		$this->visibleColumns = array('column_1','column_2','column_3');
		
		$this->orderBy = array(array('id', 'asc'), array('name','desc'));
	}

}
```
>   Note: I recommend you to see the [source code of the live example](https://github.com/mgallegos/laravel-jqgrid-demo), to get a better understanding of how to implement a repository class using [Query Builder](http://laravel.com/docs/queries) and [Eloquent ORM](http://laravel.com/docs/eloquent).

### Step 3: Create a controller to handle your grid data request.

The package includes a data encoder to help you send the data to the grid in the correct format. An instance of a class that implements the interface Mgallegos\LaravelJqgrid\Encoders\RequestedDataInterface has already been bound in the package service provider, so all you have to do is declare it as an argument in you class constructor.

Let's create the class AppController:
```php
<?php namespace Example;

use BaseController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Mgallegos\LaravelJqgrid\Encoders\RequestedDataInterface;

class AppController extends BaseController {

	protected $GridEncoder;
	
	public function __construct(RequestedDataInterface $GridEncoder)
	{
		$this->GridEncoder = $GridEncoder;
	}
	
	public function getIndex()
	{
		
		return View::make('myview');
		
	}
	
	public function postGridData()
	{
		$this->GridEncoder->encodeRequestedData(new ExampleRepository(), Input::all());
	}

}
```
### Step 4: Route all of the actions represented by our RESTful controller.

Finally let's add the following line in the file app/routes.php
```php
Route::controller('example', 'Example\AppController');
```

### Step 5: Edit package config file (optional)

In the [package config file](src/config/config.php) you can set global properties to use in all grids of your application.

## Aditional information

Any questions, feel free to [contact me](https://github.com/mgallegos) or ask [here](http://forums.laravel.io/viewtopic.php?id=15609).

Any issues, please [report here](https://github.com/mgallegos/laravel-jqgrid/issues).

## TODO

* PDF Exporter.
* Spreadsheet Exporter.

## License

Laravel jqGrid package is open source software licensed under the MIT License.
