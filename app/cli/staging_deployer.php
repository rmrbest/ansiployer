<?php
$app = require(__DIR__.'/../app_config/app.php');

$queue_strategy = $app['queue.strategy'];

/** @var \Ansiployer\Services\QueueService $queue_service */
$queue_service = new \Ansiployer\Services\QueueService($queue_strategy);

while(true) {
    $message = $queue_service->consume();
    if (null === $message) {
        sleep(10);
        continue;
    } else {
        if ($message === 'deploy_staging') {
            echo 'deploying';
        } else {
            echo 'wrong message: '.$message;
        }
    }
}