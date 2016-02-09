<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../vendor/autoload.php';
use Ansiployer\Controller\Provider\Deploy;

$app = new Silex\Application();
$app['debug'] = true;

$app->mount('/deploy', new Deploy());

$app->get('/', function() {
    return 'List of available methods
    - /deploy/{environment} - triggers a deploy to the given environment';
});

$app->run();