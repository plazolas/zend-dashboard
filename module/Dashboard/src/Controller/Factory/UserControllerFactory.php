<?php
namespace Dashboard\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Dashboard\Controller\UserController;
use PHPAuth;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller.
 */
class UserControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Instantiate the controller and inject dependencies
        $sessionContainer = $container->get('Dashboard');
        $pdoAdapter = $container->get('dbh');   // sencond dbAdapter pdo
        $pdoDriver = $pdoAdapter->getDriver();
        $pdoConnection = $pdoDriver->getConnection();
        $pdoConnect = $pdoConnection->connect();
        $dbh = $pdoConnect->getResource();
        //$dbh = new \PDO("mysql:host=localhost:3306;dbname=dashboard", "root", "root");

        $config = new PHPAuth\Config($dbh);
        $auth = new PHPAuth\Auth($dbh, $config);
        $dbUserTable = $container->get('Dashboard\User\Model\UserTable');
        $dbPracticeTable = $container->get('Dashboard\User\Model\PracticeTable');
        // $dbUsersTable = $container->get('Dashboard\User\Model\UsersTable');

        // Instantiate the controller and inject dependencies
        return new UserController($sessionContainer, $auth, $dbUserTable, $dbPracticeTable);
    }
}

