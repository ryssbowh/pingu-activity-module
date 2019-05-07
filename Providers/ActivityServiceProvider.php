<?php

namespace Pingu\Activity\Providers;

use Activity,Event,Settings;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;
use Pingu\Activity\Console\PurgeActivity;
use Pingu\Forms\Fields\Number;

class ActivityServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerFactories();
        $this->registerCommands();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        Event::listen(['eloquent.created: *'], function($event, $model) {
            if(get_class($model[0]) != "Pingu\Activity\Entities\Activity"){
                Activity::log('created', $model[0]);
            }
        });

        Event::listen(['eloquent.updated: *'], function($event, $model) {
            if(get_class($model[0]) != "Pingu\Activity\Entities\Activity"){
                Activity::log('updated', $model[0]);
            }
        });

        Event::listen(['eloquent.deleted: *'], function($event, $model) {
            if(get_class($model[0]) != "Pingu\Activity\Entities\Activity"){
                Activity::log('deleted', $model[0]);
            }
        });

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('activity:purge')->daily();
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('activity', \Pingu\Activity\Components\Activity::class);
    }

    public function registerCommands(){
        if($this->app->runningInConsole()){
            $this->commands([
                PurgeActivity::class
            ]);
        }
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('activity.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'activity'
        );
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/activity');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'activity');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'activity');
        }
    }

    /**
     * Register an additional directory of factories.
     * 
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['activity'];
    }
}
