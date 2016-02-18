<?php
namespace tests\unit;

use AdamBrett\ShellWrapper\Command;
use Ansiployer\Services\DeployService;
use PHPUnit_Framework_TestCase;

class DeployServiceUnitTest extends PHPUnit_Framework_TestCase
{
    const PLAYBOOK_FOLDER = '/playbook';
    const ENVIRONMENT = 'staging';

    /**
     * method deploy
     * when called
     * should installRequirementsAndCallDeployPlaybook
     */
    public function test_deploy_called_installRequirements()
    {
        $command_requirements = new Command('ansible-galaxy install -r '.self::PLAYBOOK_FOLDER.'/requirements.yml');
        $command_deploy = new Command('ansible-playbook -i '.self::PLAYBOOK_FOLDER.'/'.self::ENVIRONMENT.' '.self::PLAYBOOK_FOLDER.'/deploy.yml');
        $exec_double = $this->prophesize('AdamBrett\ShellWrapper\Runners\Exec');
        $exec_double->run($command_requirements)->shouldBeCalled();
        $exec_double->run($command_deploy)->shouldBeCalled();
        $sut = new DeployService(self::ENVIRONMENT, self::PLAYBOOK_FOLDER, $exec_double->reveal());
        $sut->deploy('master');
    }
}