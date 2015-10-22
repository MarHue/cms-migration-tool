<?php

namespace MarHue\CMSMigrations\Providers;

use Illuminate\Support\ServiceProvider;
use MarHue\CMSMigrations\MigrationTool;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [__DIR__ . '/../../config/cms-migrations.php'  => config_path('cms-migrations.php')],
            'config'
        );

        $this->publishes(
            [__DIR__ . '/../../storage/cms-migrations' => storage_path('cms-migrations')],
            'storage'
        );
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, array_merge_recursive(require $path, $config));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/filesystems.php', 'filesystems'
        );

        $this->commands([MigrationTool::class]);
    }
}