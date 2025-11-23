<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Cek Mode Maintenance
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register Auto Loader
require __DIR__.'/../vendor/autoload.php';

// Run The Application (Style Laravel 11)
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
