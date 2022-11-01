<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));


/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/
//wajib di ubah
require __DIR__.'/../vendor/autoload.php';
// require __DIR__.'/../../salesman-dev/vendor/autoload.php';
// require __DIR__.'/../../salesman-jtrg/vendor/autoload.php';
// require __DIR__.'/../../salesman-surya/vendor/autoload.php';
// require __DIR__.'/../../salesman-mandiri/vendor/autoload.php';

$url=Request::capture()->getHttpHost();

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}
else if (str_contains($url, 'salesman-dev.')) {
    if (file_exists($maintenance = __DIR__.'/../../salesman-dev/storage/framework/maintenance.php')) {
        require $maintenance;
    }
}
else if (str_contains($url, 'salesman-jtrg.')) {
    if (file_exists($maintenance = __DIR__.'/../../salesman-jtrg/storage/framework/maintenance.php')) {
        require $maintenance;
    }
}
else if (str_contains($url, 'salesman-surya.')) {
    if (file_exists($maintenance = __DIR__.'/../../salesman-surya/storage/framework/maintenance.php')) {
        require $maintenance;
    }
}
else if (str_contains($url, 'salesman-mandiri.')) {
    if (file_exists($maintenance = __DIR__.'/../../salesman-mandiri/storage/framework/maintenance.php')) {
        require $maintenance;
    }
}

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/
if (file_exists(__DIR__."/../bootstrap/app.php")) {
    $app = require_once __DIR__.'/../bootstrap/app.php';
}
else if (str_contains($url, 'salesman-dev.')) {
    $app = require_once __DIR__.'/../../salesman-dev/bootstrap/app.php';
}
else if (str_contains($url, 'salesman-jtrg.')) {
    $app = require_once __DIR__.'/../../salesman-jtrg/bootstrap/app.php';
}
else if (str_contains($url, 'salesman-surya.')) {
    $app = require_once __DIR__.'/../../salesman-surya/bootstrap/app.php';
}
else if (str_contains($url, 'salesman-mandiri.')) {
    $app = require_once __DIR__.'/../../salesman-mandiri/bootstrap/app.php';
}

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
