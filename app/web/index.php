<?php
require_once '../vendor/autoload.php';
$app = new Silex\Application();

$app->get('/deploy/{environment}', function ($environment) {
    return 'estoy deployando';
})->assert('environment', '[staging|production]');

$app->get('/', function() {
    return 'List of available methods
    - /deploy/{environment} - triggers a deploy to the given environment';
});

$app->run();