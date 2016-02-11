<?php
$app = require('../app_config/app.php');

use Ansiployer\Controller\Provider\Deploy;
$app->mount('/deploy', new Deploy());

$app->get('/', function() {
    return 'List of available methods
    - /deploy/{environment} - triggers a deploy to the given environment';
});

$app->run();