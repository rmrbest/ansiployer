<?php
/** @var \Silex\Application $app */
$app->register(new \Amqp\Silex\Provider\AmqpServiceProvider(), [
    'amqp.connections' => [
        'default' => [
            'host'     => 'localhost',
            'port'     => 5672,
            'username' => 'guest',
            'password' => 'guest',
            'vhost'    => '/',
        ],
    ]
]);
$app->register(new \Silex\Provider\ServiceControllerServiceProvider());