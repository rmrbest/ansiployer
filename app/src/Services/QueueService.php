<?php


namespace Ansiployer\Services;


use Ansiployer\Services\Queue\IQueueStrategy;
use Ansiployer\Services\Queue\SimpleStringQueueMessage;

class QueueService
{
    private $strategy;

    public function __construct(IQueueStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function produce(string $environment, string $version)
    {
        return $this->strategy->produce($environment, new SimpleStringQueueMessage($version));
    }

    public function consume()
    {
        return $this->strategy->consume();
    }
}