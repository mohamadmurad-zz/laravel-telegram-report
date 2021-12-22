<?php

namespace mohamadmurad\LaravelTelegramReport;

use Illuminate\Support\ServiceProvider;
use mohamadmurad\LaravelTelegramReport\Console\TelegramReportInstall;

class TelegramReportServiceProvider extends ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/telegram-report.php';

    private function publish()
    {

        if (! function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen
            return;
        }

        $this->publishes([
            self::CONFIG_PATH => config_path('telegram-report.php')
        ], 'config');


    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'telegram-report'
        );


    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publish();

        $this->commands([TelegramReportInstall::class]);
    }
}
