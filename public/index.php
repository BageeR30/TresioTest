<?php

require_once __DIR__.'/../vendor/autoload.php';

date_default_timezone_set('UTC');

$requestUri = $_SERVER['REQUEST_URI'];

echo (new App\Controllers\ApiController())->handle($requestUri);

