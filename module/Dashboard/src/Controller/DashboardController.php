<?php

namespace Dashboard\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use Zend\Db\Adapter\Adapter;
use PHPAuth;
use Zend\Session\Container;

/**
 * This is the main controller class of the Hello World dashboard. The
 * controller class is used to receive user input, validate user input, 
 * pass the data to the models and pass the results 
 * returned by models to the view for rendering.
 */
class DashboardController extends AbstractActionController {

    /**
     * Session container.
     * @var \Zend\Session\Container
     */
    private $sessionContainer;
    private $dbAdapter;
    private $auth;

    /**
     * Constructor. Its goal is to inject dependencies into controller.
     *
     * @var $sessionContainer \Zend\Session\Container --> session injection
     * @var $dbAdapter \Zend\Db\Adapter\Adapter --> session injection
     * @var $auth PHPAuth\Auth --> PHPAuth injection
     */
    public function __construct(Container $sessionContainer, Adapter $dbAdapter, PHPAuth\Auth $auth )
    {
        $this->sessionContainer = $sessionContainer;
        $this->dbAdapter = $dbAdapter;
        $this->auth = $auth;
    }

    public function indexAction() {

        $isLogged = $this->auth->isLogged();

        if ($isLogged === false) {
            return $this->redirect()->toRoute('login', ['action'=>'logout'], ['message'=>'you need to login']);
        }

        return new ViewModel(['isLogged' => $isLogged]);
    }

    /**
     * We override the parent class' onDispatch() method to
     * set an alternative layout for all actions in this controller.
     */
    public function onDispatch(MvcEvent $e) {
        // Call the base class' onDispatch() first and grab the response
        $response = parent::onDispatch($e);


        // Set alternative layout
        $this->layout()->setTemplate('/layout/layout2');

        // Return the response
        return $response;
    }

}
