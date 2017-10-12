<?php
namespace Dashboard\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Dashboard\Controller\AllergenController;
use PHPAuth;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller.
 */
class AllergenControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Instantiate the controller and inject dependencies
        $sessionContainer = $container->get('Dashboard');
        $dbh = $container->get('dbh')->getDriver()->getConnection()->connect()->getResource();
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');

        $config = new PHPAuth\Config($dbh);
        $auth = new PHPAuth\Auth($dbh, $config);
        $dbAllergenTable = $container->get('Dashboard\Allergen\Model\AllergenTable');
        $dbUserTable = $container->get('Dashboard\User\Model\UserTable');

        // Instantiate the controller and inject dependencies
        return new AllergenController($sessionContainer, $auth, $dbAllergenTable, $dbUserTable, $dbAdapter);
    }
}

