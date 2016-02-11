<?php
/** @var \Silex\Application $app */
use Ansiployer\Controller\DeployController;

$app['deploy.controller'] = \Silex\Application::share(function() use($app) {
    return new DeployController($app['amqp']['default']);
});

$app['queue.strategy'] = \Silex\Application::share(function () {
    return new \Ansiployer\Services\Queue\TextfileQueueStrategy(__DIR__.'../assets');
});