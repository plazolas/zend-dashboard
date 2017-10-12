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
class IndexController extends AbstractActionController {

    /**
     * This is the default "dashboard" action of the controller. It displays the
     * Home page.
     */
    private $sessionContainer;
    private $dbAdapter;
    private $auth;

    public function __construct(Container $sessionContainer, Adapter $dbAdapter, PHPAuth\Auth $auth )
    {
        $this->sessionContainer = $sessionContainer;
        $this->dbAdapter = $dbAdapter;
        $this->auth = $auth;
    }

    public function indexAction() {

        $result = $this->auth->isLogged();

        if ($result === false) {
            $logged = false;
        } else {
            $logged = true;
        }

        $view = new ViewModel(['isLogged' => $logged]);

        return $view;
    }

    public function sendErrorAction()
    {
        return new ViewModel();
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
