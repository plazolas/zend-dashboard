<?php
namespace Dashboard\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Dashboard\Controller\LoginController;
use PHPAuth;

//include("PHPAuth/Config.php");
//include("libraries/PHPAuth/Auth.php");

/**
 * This is the factory for RegistrationController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class LoginControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionDashboard = $container->get('Dashboard');
        $dbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $dbh = $container->get('dbh')->getDriver()->getConnection()->connect()->getResource();
        // $dbh = new \PDO("mysql:host=localhost:3306;dbname=dashboard", "root", "root");
        $config = new PHPAuth\Config($dbh);
        $auth = new PHPAuth\Auth($dbh, $config);

        // Instantiate the controller and inject dependencies
        return new LoginController($sessionDashboard, $dbAdapter, $auth);
    }
}