<?php
namespace Ansiployer\Services;

use AdamBrett\ShellWrapper\Command;
use AdamBrett\ShellWrapper\Runners\Exec;

class DeployService
{
    private $exec;
    private $environment;
    private $playbookFolder;
    public function __construct(string $environment, string $playbookFolder, Exec $exec = null)
    {
        $this->exec = $exec ?? new Exec();
        $this->environment = $environment;
        $this->playbookFolder = $playbookFolder;
    }

    public function deploy(string $version, string $rolespath='')
    {
        $install_command = 'ansible-galaxy install -r ' . $this->playbookFolder . '/requirements.yml --ignore-errors' . $rolespath;
        $deploy_command = "ansible-playbook -i {$this->playbookFolder}/{$this->environment} -e 'version=master' {$this->playbookFolder}/deploy.yml";
        $this->exec->run(new Command($install_command));
        $this->exec->run(new Command($deploy_command));
    }
}
