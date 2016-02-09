<?php
namespace Ansiployer\Controller\Provider;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

class Deploy implements ControllerProviderInterface {

    public function connect(Application $app) : ControllerCollection
    {
        $deploy_routing = $app['controllers_factory'];

        $deploy_routing->get('/{environment}', 'Ansiployer\\Controller\\DeployController::list');
        $deploy_routing->post('/{environment}', 'Ansiployer\\Controller\\DeployController::make');

        return $deploy_routing;
    }
}