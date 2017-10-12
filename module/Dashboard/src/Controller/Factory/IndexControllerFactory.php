<?php
namespace Dashboard\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Dashboard\Controller\IndexController;
use PHPAuth;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller.
 */
class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionContainer = $container->get('dashboard');
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        //$dbh = new \PDO("mysql:host=localhost:3306;dbname=dashboard", "root", "root");
        $dbh = $container->get('dbh')->getDriver()->getConnection()->connect()->getResource();
        $config = new PHPAuth\Config($dbh);
        $auth = new PHPAuth\Auth($dbh, $config);

        // Instantiate the controller and inject dependencies
        return new IndexController($sessionContainer, $dbAdapter, $auth);
    }
}

