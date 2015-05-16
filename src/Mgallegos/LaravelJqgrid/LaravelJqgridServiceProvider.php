<?php
/**
 * @file
 * LaravelJqGrid Service Provider.
 *
 * All LaravelJqGrid code is copyright by the original authors and released under the MIT License.
 * See LICENSE.
 */

namespace Mgallegos\LaravelJqgrid;

use Mgallegos\LaravelJqgrid\Renders\Validations\ColModel\NameValidation;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class LaravelJqgridServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		if ($this->isLaravelVersion('4'))
		{
			$this->package('mgallegos/laravel-jqgrid');
		}
		elseif ($this->isLaravelVersion('5'))
		{
			$this->publishes([
          __DIR__ . '/../../config/config.php' => config_path('laravel-jqgrid.php'),
      ]);

			$this->mergeConfigFrom(
          __DIR__ . '/../../config/config.php', 'laravel-jqgrid'
      );
		}
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->register('Maatwebsite\Excel\ExcelServiceProvider');

		$this->registerRender();

		$this->registerEncoder();
	}

	/**
	 * Register render service provider.
	 *
	 * @return void
	 */
	public function registerRender()
	{
		if ($this->isLaravelVersion('4'))
		{
			$prefix = 'laravel-jqgrid::';
		}
		elseif ($this->isLaravelVersion('5'))
		{
			$prefix = 'laravel-jqgrid.';
		}

		$this->app->bind('gridrender', function($app) use ($prefix)
		{
			return new Renders\JqGridRender(	array(),
												array(new NameValidation()),
												array(),
												array(),
												$app['config']->get($prefix . 'default_grid_options'),
												$app['config']->get($prefix . 'default_pivot_grid_options'),
												$app['config']->get($prefix . 'default_group_header_options'),
												$app['config']->get($prefix . 'default_col_model_properties'),
												$app['config']->get($prefix . 'default_navigator_options'),
												$app['config']->get($prefix . 'default_filter_toolbar_options'),
												$app['config']->get($prefix . 'default_filter_toolbar_buttons_options'),
												$app['config']->get($prefix . 'default_export_buttons_options'),
												$app['config']->get($prefix . 'default_file_properties'),
												$app['config']->get($prefix . 'default_sheet_properties'),
												$app['config']->get($prefix . 'function_type_properties'),
												$app['config']->get($prefix . 'pivot_options'),
												$app['config']->get($prefix . 'group_header_options'),
												$app['session']->token()
											);
		});
	}

	/**
	 * Register encoder service provider.
	 *
	 * @return void
	 */
	public function registerEncoder()
	{
		$this->app->bind('Mgallegos\LaravelJqgrid\Encoders\RequestedDataInterface', function($app)
		{
			return new Encoders\JqGridJsonEncoder($app->make('excel'));
		});
	}

	/**
	* Determine if laravel starts with any of the given version strings
	*
	* @param  string|array  $startsWith
	* @return boolean
	*/
	protected function isLaravelVersion($startsWith)
	{
		return Str::startsWith(Application::VERSION, $startsWith);
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('gridrender', 'Mgallegos\LaravelJqgrid\Encoders\RequestedDataInterface');
	}

}
