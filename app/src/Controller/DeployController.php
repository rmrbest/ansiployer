<?php
namespace Ansiployer\Controller;

use Ansiployer\Services\QueueService;

class DeployController
{
    private $queueService;

    public function __construct(QueueService $queueService)
    {
        $this->queueService = $queueService;
    }

    public function list()
    {
        return 'esto es la lista';
    }

    public function make(string $environment, string $version = 'master')
    {
        $this->queueService->produce($environment, $version);
    }
}