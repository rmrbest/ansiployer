<?php


namespace Ansiployer\Services;


use Ansiployer\Services\Queue\IQueueStrategy;

class QueueService
{
    private $strategy;

    public function __construct(IQueueStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function produce()
    {
        return $this->strategy->produce();
    }

    public function consume()
    {
        return $this->strategy->consume();
    }
}