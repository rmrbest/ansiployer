<?php
$app = require('../app_config/app.php');

use Ansiployer\Controller\Provider\Deploy;
$app->mount('/deploy', new Deploy());

$app->run();