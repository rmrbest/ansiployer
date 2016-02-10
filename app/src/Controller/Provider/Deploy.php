<?php
namespace Ansiployer\Controller\Provider;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Deploy implements ControllerProviderInterface {
    const INVENTORY_PATH = '/playbook';

    public function connect(Application $app) : ControllerCollection
    {
        $deploy_routing = $app['controllers_factory'];

        $deploy_routing->value('environment', 'staging');
        $deploy_routing->before($this->checkInventory());

        $deploy_routing->get('/{environment}', 'Ansiployer\\Controller\\DeployController::list');
        $deploy_routing->post('/{environment}', 'Ansiployer\\Controller\\DeployController::make');

        return $deploy_routing;
    }

    /**
     * @return \Closure
     */
    private function checkInventory()
    {
        return function (Request $request) {
            $environment = $request->attributes->get('environment');
            if (!file_exists(self::INVENTORY_PATH . '/' . $environment)) {
                return new JsonResponse('Environment doesn\'t exist', 404);
            }
            return null;
        };
    }
}