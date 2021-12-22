<?php

namespace mohamadmurad\LaravelTelegramReport\Traits;

use Illuminate\Support\Facades\Auth;
use mohamadmurad\LaravelTelegramReport\Bot\Telegram;

trait HasTelegramReports{


    static $created = 'Created';
    static $updated = 'Updated';
    static $deleted = 'Deleted';

    static function sendTelegramMessage($model , $logType){


        $table = $model->getTable();
        $dateTime = date('Y-m-d H:i:s');
        $userId = Auth::check() ? auth()->user()->id : null;


        $text = 'Report From *'. config('app.name') .'*' . PHP_EOL;
        $text.= 'Changes in ' . $table . 'Table ' . PHP_EOL;
        if ($userId){
            $text.= 'User : ' . $userId .  PHP_EOL;
        }

        if ($logType === self::$created){
            $text .= 'An Record Has been *' . self::$created . '*' ;
            $originalData = json_encode($model);
        }elseif ($logType === self::$updated){
            $text .= 'An Record Has been *' . self::$updated . '*';
            if (version_compare(app()->version(), '7.0.0', '>='))
                $originalData = json_encode($model->getRawOriginal()); // getRawOriginal available from Laravel 7.x
            else
                $originalData = json_encode($model->getOriginal());

        }elseif ($logType === self::$deleted){
            $text .= 'An Record Has been *' . self::$deleted . '*';
            if (version_compare(app()->version(), '7.0.0', '>='))
                $originalData = json_encode($model->getRawOriginal()); // getRawOriginal available from Laravel 7.x
            else
                $originalData = json_encode($model->getOriginal());
        }else{
            $originalData = '';
        }

        $text.= PHP_EOL .'Date : ' . $dateTime;

        $originalData = str_replace('{','{'. PHP_EOL ,$originalData);
        $originalData = str_replace('}',PHP_EOL . '}'  ,$originalData);
        $originalData = str_replace(',',  ',' . PHP_EOL  ,$originalData);

        if ($logType === self::$updated){
            $data = json_encode($model);
            $data = str_replace('{','{'. PHP_EOL ,$data);
            $data = str_replace( '}',PHP_EOL . '}'  ,$data);
            $data = str_replace(',',  ',' . PHP_EOL  ,$data);
            $text.= PHP_EOL . PHP_EOL .  'New Data : ' ;
            $text.= PHP_EOL .  '`' . $data . '`';
        }


        $text.= PHP_EOL . PHP_EOL . 'Original Data : ';
        $text.= PHP_EOL .  '`' . $originalData . '`';

        try {

            $telegram = new Telegram(config('telegram-report.token'));
            $response = $telegram->sendMessage([
                'chat_id' => config('telegram-report.chat_id'),
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);


        }  catch (\Exception $e) {
            echo $e->getMessage();
        }


    }

    public static function bootHasTelegramReports(){
        self::created(function ($model) {

           self::sendTelegramMessage($model,self::$created);

        });

        self::updated(function ($model) {
            self::sendTelegramMessage($model,self::$updated);
        });

        self::deleted(function ($model) {
            self::sendTelegramMessage($model,self::$deleted);
        });
    }


}
