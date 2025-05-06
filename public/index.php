<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

// Disable error display in production
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

define('LARAVEL_START', microtime(true));

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

require __DIR__.'/../vendor/autoload.php';

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

try {
    $app = require_once __DIR__.'/../bootstrap/app.php';

    $kernel = $app->make(Kernel::class);

    $response = $kernel->handle(
        $request = Request::capture()
    )->send();

    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    // Log the error but display generic message
    error_log($e->getMessage());

    // Determine if this is an Ajax request
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if ($isAjax) {
        // Return JSON error for Ajax requests
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'An unexpected error occurred']);
    } else {
        // Show a generic error page for regular requests
        http_response_code(500);
        include __DIR__ . '/../resources/views/errors/500.blade.php';
    }
}
