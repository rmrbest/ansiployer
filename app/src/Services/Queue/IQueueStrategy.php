<?php
namespace Ansiployer\Services\Queue;

interface IQueueStrategy
{
    public function produce(string $name, IQueueMessage $message);
    public function consume(string $name);
}