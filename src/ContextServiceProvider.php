<?php

namespace Dottwatson\Context;

use Illuminate\Support\ServiceProvider;
use Dottwatson\Context\ContextManager;

class ContextServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ContextManager::class, function ($app) {
            return new ContextManager();
        });
    }

    public function provides() {
        return [ContextManager::class];
    }

    /**
     * Boot methods for the package
     */
    public function boot()
    {
        // Laravel takes over `$this` context over in the macro closure,
        // so we have to bind $this->app to a variable here instead.
        $app = $this->app;

        $this->app['request']->macro('context', function (string $name = null) use ($app) {
            return (new ContextManager)->getContext();
        });

        $this->app['request']->macro('setContext', function (string $name) use ($app) {
            (new ContextManager)->setCurrentContext($name);
            return $this;
        });


        $this->loadConfig();

        $this->publishes([
            $this->packagePath('config/context.php') => config_path('context.php')
        ], 'context');

        if(!app()->runningInConsole()){
            $defaultContexts = config('context.default',[]);
            foreach($defaultContexts as $contextName=>$storageType){
                (new ContextManager)->register($contextName,$storageType);
            }
        }

    }

    /**
     * Load the package config.
     *
     * @return void
     */
    private function loadConfig()
    {
        $configPath = $this->packagePath('config/context.php');

        $this->mergeConfigFrom($configPath, 'context');
    }

    /**
     * Get the absolute path to some package resource.
     *
     * @param  string  $path  The relative path to the resource
     * @return string
     */
    private function packagePath($path)
    {
        return __DIR__."/$path";
    }


}
