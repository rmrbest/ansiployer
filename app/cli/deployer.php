<?php
$app = require(__DIR__.'/../app_config/app.php');

$queue_strategy = $app['queue.strategy'];

if(!isset($argv[1])) {
    die('Usage = php deployer.php ENVIRONMENT');
}

$environment = $argv[1];

/** @var \Ansiployer\Services\QueueService $queue_service */
$queue_service = $app['queue.service'];
$deploy_service = new \Ansiployer\Services\DeployService($environment, '/playbook');

while(true) {
    $message = $queue_service->consume();
    if (null === $message) {
        sleep(10);
        continue;
    } else {
        $deploy_service->deploy($message);
    }
}