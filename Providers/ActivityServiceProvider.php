<?php

namespace Pingu\Activity\Providers;

use Activity,Event,Settings;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Factory;
use Pingu\Activity\Console\PurgeActivity;
use Pingu\Core\Support\ModuleServiceProvider;
use Pingu\Forms\Fields\Number;

class ActivityServiceProvider extends ModuleServiceProvider
{
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

        Event::listen(
            ['eloquent.created: *'], function ($event, $model) {
                Activity::logModel('created', $model[0]);
            }
        );

        Event::listen(
            ['eloquent.updated: *'], function ($event, $model) {
                Activity::logModel('updated', $model[0]);
            }
        );

        Event::listen(
            ['eloquent.deleted: *'], function ($event, $model) {
                Activity::logModel('deleted', $model[0]);
            }
        );

        Event::listen(
            ['eloquent.restored: *'], function ($event, $model) {
                Activity::logModel('restored', $model[0]);
            }
        );

        \Cron::command('Purge activity', 'activity:purge')->daily();
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

    public function registerCommands()
    {
        $this->commands(
            [
            PurgeActivity::class
            ]
        );
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'activity'
        );
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('module-activity.php')
        ], 'config');
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
}
