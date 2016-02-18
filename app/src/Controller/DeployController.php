<?php
namespace Ansiployer\Controller;

use Ansiployer\Services\QueueService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DeployController
{
    private $queueService;

    const STATUS_QUEUED = 'QUEUED';

    public function __construct(QueueService $queueService)
    {
        $this->queueService = $queueService;
    }

    public function list()
    {
        return 'esto es la lista';
    }

    public function make(Request $request)
    {
        $this->queueService->produce($request->get('environment'), $request->get('version'));
        return new JsonResponse(['status'=> self::STATUS_QUEUED], 200);
    }

    public function actionList()
    {
        return 'List of available methods
    - /deploy/{environment} - triggers a deploy to the given environment';

    }
}