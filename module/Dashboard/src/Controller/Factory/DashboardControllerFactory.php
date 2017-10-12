<?php
namespace Dashboard\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Dashboard\Controller\DashboardController;
use PHPAuth;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller.
 */
class DashboardControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Instantiate the controller and inject dependencies
        $sessionContainer = $container->get('Dashboard');
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $dbh = $container->get('dbh')->getDriver()->getConnection()->connect()->getResource();
        $config = new PHPAuth\Config($dbh);
        $auth = new PHPAuth\Auth($dbh, $config);

        // Instantiate the controller and inject dependencies
        return new DashboardController($sessionContainer, $dbAdapter, $auth);
    }
}

