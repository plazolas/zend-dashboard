<?php

namespace Dashboard\Controller;

use PHPAuth;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Dashboard\Allergen\Model\AllergenTable;
use Dashboard\User\Model\UserTable;
use Dashboard\Allergen\Model\Allergen;
use Dashboard\Allergen\Form\AllergenFormEdit;
use Dashboard\Allergen\Form\AllergenFormView;
use Zend\Db\Adapter\Adapter;


/**
 * This is the main controller class of Allergens. The
 * controller class is used to receive user input, validate user input,
 * pass the data to the models and pass the results
 * returned by models to the view for rendering.
 */
class AllergenController extends AbstractActionController
{
    private $controller_name;
    private $activeUser;
    private $sessionContainer;
    private $auth;
    private $dbAdapter;
    private $isLogged;
    protected $userTable;

    protected $allergenTable;

    /**
     * Constructor. Its goal is to inject dependencies into controller.
     *
     * @param $sessionContainer \Zend\Session\Container --> session injection
     * @param $auth PHPAuth\Auth --> PHPAuth injection
     * @param $dbAllergenTable \Dashboard\Allergen\Model\AllergenTable --> AllergenTable object injection
     * @param $dbUserTable \Dashboard\User\Model\UserTable --> UserTable object injection
     * @param $dbAdapter \Zend\Db\Adapter\Adapter
     */
    public function __construct(Container $sessionContainer, PHPAuth\Auth $auth, AllergenTable $dbAllergenTable, UserTable $dbUserTable, Adapter $dbAdapter)
    {
        $this->sessionContainer = $sessionContainer;
        $this->auth = $auth;
        $this->allergenTable = $dbAllergenTable;
        $this->userTable = $dbUserTable;
        $this->dbAdapter = $dbAdapter;
        $this->isLogged = $auth->isLogged();

        if ($this->auth->isLogged() === false) {
            // handle it on onDispatch
            return;
        }
        $hash = $this->auth->getSessionHash();
        $uid = $this->auth->getSessionUID($hash);
        $this->activeUser = $this->auth->getUser($uid);
        $user = $this->userTable->get($uid);
        $this->activeUser['role'] = $user->role;
        if (!is_array($this->activeUser) || $this->activeUser['role'] == '') {
            //return $this->redirect()->toRoute('login', ['action' => 'logout'], ['message' => 'you need to login']);
            return;
        }

        $class_breakdown = explode('\\',get_class($this));
        foreach($class_breakdown as $class_part) {
            if(preg_match('/Controller/', $class_part)){
                $this->controller_name = preg_replace('/Controller/','',$class_part);
            }
        }

        return true;
    }

    public function indexAction()
    {

        $data = $this->allergenTable->fetchAll();

        return new ViewModel([
            'controller' => $this->controller_name,
            'objs' => $data,
            'activeUser' => $this->activeUser,
            'isLogged' => $this->isLogged
        ]);
    }

    public function editAction()
    {
        $id = (int)$this->params('id');
        if (empty($id) || !is_int($id)) {
            $msg = "Could not UPDATE entry no valid id provided" . __METHOD__;
            error_log($msg);
            $this->flashMessenger()->addMessage($msg);
            return $this->redirect()->toRoute('allergen', ['action' => 'index']);
        }
        $obj = $this->allergenTable->get($id);
        if (!($obj instanceof Allergen)) {
            $msg = "Could not UPDATE! {$id} ";
            error_log(__METHOD__.$msg);
            $this->flashMessenger()->addMessage($msg);
            return $this->redirect()->toRoute('allergen', ['action' => 'index']);
        }

        $form = new AllergenFormEdit($obj);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            $new_obj = new Allergen($this->dbAdapter);
            $new_obj->exchangeArray($data);

            // Validate form
            if ($form->isValid()) {

                $result = $this->allergenTable->save($new_obj);
                if ($result === false) {
                    $msg = "Could not UPDATE table ";
                    error_log(__METHOD__.$msg);
                    $this->flashMessenger()->addMessage($msg);
                }
                $this->flashMessenger()->addMessage("Entry {$new_obj->id} UPDATED!");

                return $this->redirect()->toRoute('allergen', ['action' => 'index']);
            }
        }

        $title = $this->controller_name." ". ucfirst($this->params('action'));
        return new ViewModel([
            'title' => $title,
            'form' => $form,
        ]);
    }

    public function viewAction()
    {
        if ($this->getRequest()->isPost()) {
            return $this->redirect()->toRoute('allergen', ['action' => 'index']);
        }
        $id = (int)$this->params('id');
        if (empty($id) || !is_int($id)) {
            $msg = "Could not VIEW no valid id provided";
            error_log(__METHOD__.$msg);
            $this->flashMessenger()->addMessage($msg);
            return $this->redirect()->toRoute('allergen', ['action' => 'index']);
        }
        $allergen = $this->allergenTable->get($id);
        if (!($allergen instanceof Allergen)) {
            $msg = "Could not UPDATE {$id} from table ";
            error_log(__METHOD__.$msg);
            $this->flashMessenger()->addMessage($msg);
            return $this->redirect()->toRoute('allergen', ['action' => 'index']);
        }

        $form = new AllergenFormView($allergen);

        $title = $this->controller_name." ". ucfirst($this->params('action'));
        return new ViewModel([
            'title' => $title,
            'form' => $form,
        ]);
    }


    public function deleteAction()
    {
        $id = (int)$this->params('id');
        if (empty($id) || !is_int($id)) {
            $msg = "Could not DELETE entry `{$id}`";
            error_log(__METHOD__.$msg);
            $this->flashMessenger()->addMessage($msg);
            return $this->redirect()->toRoute('allergen', ['action' => 'index']);
        }

        $this->allergenTable->delete($id);

        $this->flashMessenger()->addMessage("Entry HAS BEEN DELETED!");
        return $this->redirect()->toRoute('allergen', ['action' => 'index']);
    }

    public function addAction()
    {

        $new_obj = new Allergen($this->dbAdapter);

        $form = new AllergenFormEdit($new_obj);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            $new_obj = new Allergen($this->dbAdapter);
            $new_obj->exchangeArray($data);

            // Validate form
            if ($form->isValid()) {
                    $new_obj->id = 0;
                    $result = $this->allergenTable->save($new_obj);
                    if ($result == false) {
                        $msg = "Could not ADD entry! ";
                        error_log(__METHOD__.$msg);
                        $this->flashMessenger()->addMessage($msg);
                        return $this->redirect()->toRoute('allergen', ['action' => 'index']);
                    }
                $this->flashMessenger()->addMessage("ENTRY ADDED!");
                return $this->redirect()->toRoute('allergen', ['action' => 'index']);
            }
        }

        $title = $this->controller_name." ". ucfirst($this->params('action'));
        $view = new ViewModel([
            'title' => $title,
            'form' => $form,
        ]);
        $view->setTemplate('dashboard/allergen/edit');
        return $view;
    }

    /**
     * We override the parent class' onDispatch() method to
     * set an alternative layout for all actions in this controller.
     *
     * @var $e \Zend\Mvc\MvcEvent
     * @return \Zend\Http\Response
     */
    public function onDispatch(MvcEvent $e)
    {
        if ($this->auth->isLogged() === false) {
            // handle it on onDispatch
            return $this->redirect()->toRoute('login', ['action' => 'logout'], ['message' => 'you need to login']);
        }

        if (!is_array($this->activeUser) || $this->activeUser['role'] == '') {
            return $this->redirect()->toRoute('login', ['action' => 'logout'], ['message' => 'you need to login']);
        }

        // Call the base class' onDispatch() first and grab the response
        $response = parent::onDispatch($e);

        // Set user layout
        $this->layout()->setTemplate('layout/table');

        // Return the response
        return $response;
    }

    public function onRoute(MvcEvent $e) {
        $match = $e->getRouteMatch();
        // No route match, this is a 404
        if (! $match instanceof RouteMatch) {
            return;
        }


    }

}
