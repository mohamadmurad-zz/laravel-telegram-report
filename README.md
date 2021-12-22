<h1 align="center">laravelTelegramReport</h1>
<p align="center">Easy Send Activity For Any Model To Your Telegram</p>

# Documentation

install the package via composer

```
composer require mohamadmurad/laravel-telegram-report
```

# Config file
This package publishes a config/telegram-report.php file.
If you already have a file by that name, you must rename or remove it, as it will conflict with this package.
You could optionally merge your own values with those required by this package, as long as the keys that this package expects are present.
See the source file for more details <a href="https://github.com/mohamadmurad/laravelTelegramReport/blob/main/config/telegram-report.php">telegram-report.php</a>

Publish the config/telegram-report.php config file with:

```
php artisan telegram-report:install
```

Add Configration data to your .env file
see  <a href="https://core.telegram.org/bots#3-how-do-i-create-a-bot">how to create bot</a>
```
TELEGRAM_TOKEN="Token for your telegram bot"  
TELEGRAM_CHAT_ID ="your account id in telegram"
```

Add package trait to any model you want to get report about it
example : 

```php

use mohamadmurad\LaravelTelegramReport\Traits\HasTelegramReports;

class User extends Authenticatable
{
    use HasTelegramReports;
....

}
```

the report send after create , update or delete any record in this model
