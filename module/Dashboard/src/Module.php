<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Dashboard;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Dashboard\User\Model\User;
use Dashboard\User\Model\UserTable;
use Dashboard\User\Model\Practice;
use Dashboard\User\Model\PracticeTable;
use Dashboard\User\Model\UsersTable;
use Dashboard\User\Model\Users;
use Dashboard\Allergen\Model\AllergenTable;
use Dashboard\Allergen\Model\Allergen;
use Dashboard\Learning\Model\LearningTable;
use Dashboard\Learning\Model\Learning;

//use Zend\ServiceManager\ServiceManager;

class Module implements ConfigProviderInterface, BootstrapListenerInterface
{
    const VERSION = '1.0.0dev';

    public function init(ModuleManager $moduleManager)
    {
        // Get event manager.
        $eventManager = $moduleManager->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Register the event listener method.
        $sharedEventManager->attach(__NAMESPACE__, 'route',
            [$this, 'onRoute'], 100);

        //$sharedEventManager = $eventManager->getSharedManager();
        // Register the event listener method.
        //$sharedEventManager->attach(__NAMESPACE__, 'dispatch',
        //  [$this, 'onDispatch'], 100);

    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        //$eName = $e->getName();

        //if (! $routeMatch instanceof RouteMatch) {
            // return;
        //}
        //$this->setDbTransport($sm);
    }

    /*
    public function setDbTransport( ServiceManager $sm)
    {
        // this is testing dbAdapter instantiation
        $adapter = $sm->get('Zend\Db\Adapter\Adapter');
        $resultSet = $adapter->query('SELECT * FROM `user` WHERE `id` = ?', [1]);
        $result = $resultSet->toArray();
        var_dump($result);exit;
    }
    */

    // Event listener method.
    public function onDispatch(MvcEvent $event)
    {
        // Get controller to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        // Get fully qualified class name of the controller.
        $controllerClass = get_class($controller);
        // Get module name of the controller.
        $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));

        // Switch layout only for controllers belonging to our module.
        if ($moduleNamespace == __NAMESPACE__) {
            $viewModel = $event->getViewModel();
            $viewModel->setTemplate('layout/layout2');
        }
    }

    // Event listener method.
    public function onRoute(MvcEvent $event)
    {
        /* //PREVENTS CLI REDIRECTS
        if (php_sapi_name() == "cli") {
            // Do not execute HTTPS redirect in console mode.
            return;
        }
        */

        // $route = $event->getParam('route');

        // Get request URI
        //$uri = $event->getRequest()->getUri();
        //$scheme = $uri->getScheme();
        // If scheme is not HTTPS, redirect to the same URI, but with
        // HTTPS scheme.
        //if ($scheme != 'https'){
        //    $uri->setScheme('https');
        //    $response=$event->getResponse();
        //    $response->getHeaders()->addHeaderLine('Location', $uri);
        //    $response->setStatusCode(301);
        //    $response->sendHeaders();
        //    return $response;
        //}
    }


    public function getServiceConfig()
    {
        return [
            'factories' => [
                'Dashboard\User\Model\UserTable' => function ($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'Dashboard\User\Model\PracticeTable' => function ($sm) {
                    $tableGateway = $sm->get('PracticeTableGateway');
                    $table = new PracticeTable($tableGateway);
                    return $table;
                },
                'Dashboard\User\Model\UsersTable' => function ($sm) {
                    $tableGateway = $sm->get('UsersTableGateway');
                    $table = new UsersTable($tableGateway);
                    return $table;
                },
                'Dashboard\Allergen\Model\AllergenTable' => function ($sm) {
                    $tableGateway = $sm->get('AllergenTableGateway');
                    $table = new AllergenTable($tableGateway);
                    return $table;
                },
                'Dashboard\Learning\Model\LearningTable' => function ($sm) {
                    $tableGateway = $sm->get('LearningTableGateway');
                    $table = new LearningTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
                'UsersTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Users());
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },
                'PracticeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Practice());
                    return new TableGateway('learning', $dbAdapter, null, $resultSetPrototype);
                },
                'AllergenTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Allergen($dbAdapter));
                    return new TableGateway('allergen_seasons', $dbAdapter, null, $resultSetPrototype);
                },
                'LearningTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Learning($dbAdapter));
                    return new TableGateway('learning', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
}
