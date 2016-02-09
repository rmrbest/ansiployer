<?php
namespace Ansiployer\Controller;

class DeployController
{
    public function list(string $environment)
    {
        return 'esto es la lista';
    }

    public function deploy(string $environment)
    {
        return 'estoy deployando';
    }
}