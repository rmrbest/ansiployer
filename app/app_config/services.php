<?php
/** @var \Silex\Application $app */
use Ansiployer\Controller\DeployController;

$app['queue.service'] = \Silex\Application::share(function () {
    return new \Ansiployer\Services\QueueService(new \Ansiployer\Services\Queue\TextfileQueueStrategy('/assets'));
});

$app['deploy.controller'] = \Silex\Application::share(function() use($app) {
    return new DeployController($app['queue.service']);
});