<?php

namespace benlehr\MailTracker;

use Mail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MailTrackerServiceProvider extends ServiceProvider
{
    /**
     * Check to see if we're using lumen or laravel.
     *
     * @return bool
     */
    public function isLumen()
    {
        $lumenClass = 'Laravel\Lumen\Application';
        return ($this->app instanceof $lumenClass);
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if (MailTracker::$runsMigrations && $this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        // Publish pieces
        $this->publishConfig();
        $this->publishViews();
        $this->publishTranslations();

        // Register console commands
        $this->registerCommands();

        // Hook into the mailer
        $this->registerSwiftPlugin();

        // Install the routes
        $this->installRoutes();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }


    protected function publishTranslations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'mail-tracker');

        $this->publishes([
            __DIR__.'/lang' => resource_path('lang/mail-tracker'),
        ]);
    }

    /**
     * Publish the configuration files
     *
     * @return void
     */
    protected function publishConfig()
    {
        if (!$this->isLumen()) {
            $this->publishes([
                __DIR__.'/../config/mail-tracker.php' => config_path('mail-tracker.php')
            ], 'config');
        }
    }

    /**
     * Publish the views
     *
     * @return void
     */
    protected function publishViews()
    {
        if (!$this->isLumen()) {
            $this->loadViewsFrom(__DIR__.'/views', 'emailTrakingViews');
            $this->publishes([
                __DIR__.'/views' => base_path('resources/views/vendor/emailTrakingViews'),
                ]);
        }
    }

    public function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\MigrateRecipients::class,
                Console\MakeTrackableMail::class,
            ]);
        }
    }

    /**
     * Register the Swift plugin
     *
     * @return void
     */
    protected function registerSwiftPlugin()
    {
        $this->app['mailer']->getSwiftMailer()->registerPlugin(new MailTracker());
    }

    /**
     * Install the needed routes
     *
     * @return void
     */
    protected function installRoutes()
    {
        $config = $this->app['config']->get('mail-tracker.route', []);
        $config['namespace'] = 'benlehr\MailTracker';

        if (!$this->isLumen()) {
            Route::group($config, function () {
                Route::get('t/{hash}', 'MailTrackerController@getT')->name('mailTracker_t');
                Route::get('l/{url}/{hash}', 'MailTrackerController@getL')->name('mailTracker_l');
                Route::get('n', 'MailTrackerController@getN')->name('mailTracker_n');
            });
        } else {
            $app = $this->app;
            $app->group($config, function () use ($app) {
                $app->get('t', 'MailTrackerController@getT')->name('mailTracker_t');
                $app->get('l', 'MailTrackerController@getL')->name('mailTracker_l');
            });
        }
        // Install the Admin routes
        $config_admin = $this->app['config']->get('mail-tracker.admin-route', []);
        $config_admin['namespace'] = 'benlehr\MailTracker';

        if (Arr::get($config_admin, 'enabled', true)) {
            if (!$this->isLumen()) {
                Route::group($config_admin, function () {
                    Route::get('/', 'AdminController@getIndex')->name('mailTracker_Index');
                    Route::get('campaign/{id}', 'AdminController@getDetail')->name('mailTracker_Detail');
                    Route::post('search', 'AdminController@postSearch')->name('mailTracker_Search');
                    Route::get('clear-search', 'AdminController@clearSearch')->name('mailTracker_ClearSearch');
                    Route::get('show-email/{id}', 'AdminController@getShowEmail')->name('mailTracker_ShowEmail');
                    Route::get('url-detail/{id}', 'AdminController@getUrlDetail')->name('mailTracker_UrlDetail');
                    Route::get('smtp-detail/{id}', 'AdminController@getSmtpDetail')->name('mailTracker_SmtpDetail');
                });
            } else {
                $app = $this->app;
                $app->group($config_admin, function () use ($app) {
                    $app->get('/', 'AdminController@getIndex')->name('mailTracker_Index');
                    $app->get('campaign/{id}', 'AdminController@getDetail')->name('mailTracker_Detail');
                    $app->post('search', 'AdminController@postSearch')->name('mailTracker_Search');
                    $app->get('clear-search', 'AdminController@clearSearch')->name('mailTracker_ClearSearch');
                    $app->get('show-email/{id}', 'AdminController@getShowEmail')->name('mailTracker_ShowEmail');
                    $app->get('url-detail/{id}', 'AdminController@getUrlDetail')->name('mailTracker_UrlDetail');
                    $app->get('smtp-detail/{id}', 'AdminController@getSmtpDetail')->name('mailTracker_SmtpDetail');
                });
            }
        }
    }
}
