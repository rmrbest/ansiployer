<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();
$app['debug'] = true;

include_once(__DIR__.'/providers.php');
include_once(__DIR__.'/services.php');

return $app;
