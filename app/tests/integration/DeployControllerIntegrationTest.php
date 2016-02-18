<?php
namespace tests\integration;

use Ansiployer\Controller\DeployController;
use Ansiployer\Services\Queue\TextfileQueueStrategy;
use Ansiployer\Services\QueueService;

class DeployControllerIntegrationTest extends IntegrationTestBase
{

    protected function getFileFixtures()
    {
        return array_merge(
            [
                'busy_queue',
            ],
            parent::getFileFixtures()
        );
    }

    protected function getFolderFixtures()
    {
        return array_merge(
            [
                'playbook'
            ]
            ,parent::getFolderFixtures()
        );
    }

    /**
     * method make
     * when calledWithTextFileQueueStrategy
     * should queueTheRequests
     */
    public function test_make_calledWithTextFileQueueStrategy_queueTheRequests()
    {
        $environment = 'busy';
        $request_double = $this->prophesize('Symfony\Component\HttpFoundation\Request');
        $request_double->get('environment')->willReturn($environment);
        $request_double->get('version')->willReturn('master');
        $expected = "\"first job\"\n\"second job\"\n\"third job\"\n\"fourth job\"\n\"fifth job\"\n\"master\"\n\"master\"\n\"master\"\n";
        $sut = new DeployController(new QueueService(new TextfileQueueStrategy(self::FILE_FOLDER)));
        $request = $request_double->reveal();
        $sut->make($request);
        $sut->make($request);
        $sut->make($request);
        self::assertStringEqualsFile(self::FILE_FOLDER . '/' . $environment . '_queue', $expected);
    }
}