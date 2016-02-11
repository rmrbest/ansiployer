<?php
namespace Ansiployer\Controller;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class DeployController
{
    private $queueConnection;

    public function __construct(AMQPConnection $queueConnection)
    {
        $this->queueConnection = $queueConnection;
    }

    public function list()
    {
        $channel = $this->queueConnection->channel();
        $channel->queue_declare('deploy_queue', false, true, false, false);
        $now = new \DateTime();

        $msg = new AMQPMessage($now->format('YmdHis'), ['delivery_mode'=> 2]);
        $channel->basic_publish($msg, '', 'deploy_queue');
        $channel->close();
        $this->queueConnection->close();
        return 'esto es la lista';
    }

    public function make(string $environment)
    {

        return 'estoy deployando';
    }
}