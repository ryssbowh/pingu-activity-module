<?php

namespace Modules\Activity\Providers;

use Activity,Event,Settings;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;
use Modules\Activity\Console\PurgeActivity;

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
        $this->registerViews();
        $this->registerFactories();
        $this->registerCommands();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        Event::listen(['eloquent.created: *'], function($event, $model) {
            if(get_class($model[0]) != "Modules\Activity\Entities\Activity"){
                Activity::log('created', $model[0]);
            }
        });

        Event::listen(['eloquent.updated: *'], function($event, $model) {
            if(get_class($model[0]) != "Modules\Activity\Entities\Activity"){
                Activity::log('updated', $model[0]);
            }
        });

        Event::listen(['eloquent.deleted: *'], function($event, $model) {
            if(get_class($model[0]) != "Modules\Activity\Entities\Activity"){
                Activity::log('deleted', $model[0]);
            }
        });

        Settings::register('activity.lifetime',[
            'Title' => 'Activity life span',
            'Section' => 'Activity Logging',
            'type' => 'number',
            'validation' => 'required|integer'
        ]);

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
        $this->app->bind('activity', \Modules\Activity\Components\Activity::class);
    }

    public function registerCommands(){
        $this->commands([
            PurgeActivity::class
        ]);
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
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $themePaths = $this->app->make('view.finder')->getThemesPublishPaths('activity');

        $sourcePath = __DIR__.'/../Resources/views';

        foreach($themePaths as $path => $namespace){
            $this->publishes([
                $sourcePath => $path
            ],$namespace);
        }

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/activity';
        }, \Config::get('view.paths')), [$sourcePath]), 'activity');
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
