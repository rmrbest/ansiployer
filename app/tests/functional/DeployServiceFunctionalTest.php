<?php


namespace tests\functional;


use Ansiployer\Services\DeployService;

class DeployServiceFunctionalTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        unlink('/tmp/deploytest.txt');
        system('rm -rf /tmp/roles');
        parent::tearDown();
    }

    /**
     * method deploy
     * when called
     * should installRequirementsAndDeploy
     */
    public function test_deploy_called_installRequirementsAndDeploy()
    {
        $sut = new DeployService('staging', __DIR__.'/playbook');
        $sut->deploy('master', ' -p /tmp/roles');
        self::assertFileExists('/tmp/roles/kbrebanov.docker');
        self::assertStringEqualsFile('/tmp/deploytest.txt', "test\n");
    }
}