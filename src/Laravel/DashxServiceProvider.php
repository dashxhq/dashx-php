<?php

namespace Dashx\Php\Laravel;

use Illuminate\Support\ServiceProvider;

use Dashx\Php\Client;

use Exception;

class DashxServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // merge default config
        $this->mergeConfigFrom(
            __DIR__.'/config.php',
            'dashx'
        );

        $this->app->singleton('DashX', function($app) {
            $config = $this->getDashxConfig($app);

            if (empty($config)) {
                throw new Exception('DashX configuration not exists.');
            }

            return new Client(
              $config['public_key'],
              $config['private_key'],
              $config['target_environment'],
              $config['base_uri'],
            );
        });

        $app->alias('DashX', 'Dashx\Php\Client');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
          __DIR__.'/config.php' => config_path('dashx.php')
        ]);
    }

    /**
     * Return dashx configuration as array
     *
     * @param  Application $app
     * @return array
     */
    private function getDashxConfig($app)
    {
        $config = $app['config']->get('dashx');

        if (is_null($config)) {
            return [];
        }

        return $config;
    }
}
