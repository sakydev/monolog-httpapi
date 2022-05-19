

monolog-httpapi
=============

HTTP API Handler for php monolog which allows you to log messages into external API of your choice.


# Installation
-----------
Install using composer:

```bash
composer require sakydev/monolog-httpapi  
```

# Usage
TODO

# Examples
Now Simply use it like this :

```php
require 'vendor/autoload.php';
use Monolog\Logger;
use sakydev\HttpApiHandler\HttpApiHandler;
$log = new Logger('HttpApiHandler');
$headers = ['Content-Type: application/json'];
$log->pushHandler(new HttpApiHandler('http://url-here.com', $headers));


$log->notice('hello world !');
$log->info('hello world !');
$log->debug('hello world !');
$log->warning('hello world !');
$log->critical('hello world !');
$log->alert('hello world !');
$log->emergency('hello world !');
$log->error('hello world !');


/**
* Optionally you can pass second paramater such as a object
**/
$log->info('user just logged in !',['user'=>$user]);

```

# License
This tool in Licensed under MIT, so feel free to fork it and make it better than it is !
