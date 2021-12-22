<?php
namespace mohamadmurad\LaravelTelegramReport\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TelegramReportInstall extends Command{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram-report:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will publish config file for Telegram Report';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //config
        if (File::exists(config_path('telegram-report.php'))) {
            $confirm = $this->confirm("telegram-report.php config file already exist. Do you want to overwrite?");
            if ($confirm) {
                $this->publishConfig();
                $this->info("config overwrite finished");
            } else {
                $this->info("skipped config publish");
            }
        } else {

            $this->publishConfig();
            $this->info("config published");
        }

    }

    private function publishConfig()
    {

        $this->call('vendor:publish', [
            '--provider' => "mohamadmurad\LaravelTelegramReport\TelegramReportServiceProvider",
            '--tag'      => 'config',
            '--force'    => true
        ]);
    }



}
