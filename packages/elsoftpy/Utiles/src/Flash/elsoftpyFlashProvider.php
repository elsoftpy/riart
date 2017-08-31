<?php

namespace elsoftpy\Utiles\Flash;

use Illuminate\Support\ServiceProvider;

class elsoftpyFlashProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


     /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../views', 'flash');
        $this->publishes([
            __DIR__ . '/../../views' => base_path('resources/views/vendor/flash')
        ]);
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
      
        $this->app->bind( 
            'elsoftpy\Utiles\Flash\SessionStore', 
            'elsoftpy\Utiles\Flash\LaravelSessionStore'
        );

        $this->app->singleton('flash', function(){
            return $this->app->make('elsoftpy\Utiles\Flash\FlashNotifier');
        });

         /*$this->app->singleton('command.stepapp.migrate', function ($app) {
            return $this->app->make('elsoftpy\Flash\FlashNotifier');
        });*/
        
      
    }
}
