<?php

namespace Dashboard\Controller;

use PHPAuth;
use Dashboard\User\Model\PracticeTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Dashboard\User\Model\UserTable;
use Dashboard\User\Model\User;
use Dashboard\User\Model\Users;
use Dashboard\User\Form\UserFormEdit;
use Dashboard\User\Form\UserFormView;

/**
 * This is the main controller class of the Hello World dashboard. The
 * controller class is used to receive user input, validate user input,
 * pass the data to the models and pass the results
 * returned by models to the view for rendering.
 */
class UserController extends AbstractActionController
{

    /**
     * Session container.
     * @var $sessionContainer \Zend\Session\Container
     * PHPAuth
     * @var $auth \PHPAuth\Auth
     * @var $isLogged boolean
     * @var $userTable \Zend\Db\TableGateway\TableGateway
     * @var $practiceTable \Zend\Db\TableGateway\TableGateway
     *
     */
    private $activeUser;
    private $sessionContainer;
    private $auth;
    private $isLogged;
    protected $userTable;
    protected $practiceTable;
    protected $usersTable;

    /**
     * Constructor. Its goal is to inject dependencies into controller.
     *
     * @param  $sessionContainer \Zend\Session\Container --> session injection
     * @param  $auth PHPAuth\Auth --> PHPAuth injection
     * @param $dbUserTable \Dashboard\User\Model\UserTable --> UserTable object injection
     * @param PracticeTable|UserTable $dbPracticeTable
     */
    public function __construct(Container $sessionContainer, PHPAuth\Auth $auth, UserTable $dbUserTable, PracticeTable $dbPracticeTable)
    {
        $this->sessionContainer = $sessionContainer;
        $this->auth = $auth;
        $this->userTable = $dbUserTable;
        $this->practiceTable = $dbPracticeTable;
        // $this->usersTable = $dbUsersTable;  maxed out number of tables -- throws error saying dbUsersTable is a Controller ???

        $this->isLogged = $this->auth->isLogged();
        if ($this->isLogged === false) {
            return $this->redirect()->toRoute('login', ['action' => 'logout'], ['message' => 'you need to login']);
        }
        $this->sessionContainer->isLogged = $this->isLogged;
        $hash = $this->auth->getSessionHash();
        $uid = $this->auth->getSessionUID($hash);
        $this->activeUser = $this->auth->getUser($uid);
        $user = $this->userTable->get($uid);
        $this->activeUser['role'] = $user->role;
        if (!is_array($this->activeUser) || $this->activeUser['role'] == '') {
            return $this->redirect()->toRoute('login', ['action' => 'logout'], ['message' => 'you need to login']);
        }

        return true;
    }

    public function indexAction()
    {
/*
        $users_set = $this->userTable->fetchAll();
        while ($users_set->current()) {
            $users[] = $users_set->current();
            $users_set->next();
        }

        $practices_set = $this->practiceTable->fetchAll();
        while ($practices_set->current()) {
            $practices[] = $practices_set->current();
            $practices_set->next();
        }
        foreach ($users as $user) {
            foreach ($practices as $practice) {
                if($user->pid == $practice->id){
                    $user->pid = $practice->name;
                    $user->region = $practice->region;
                }
            }
        }
 */
        $users = $this->userTable->fetchJoinUser();

        return new ViewModel([
            'users' => $users,
            'activeUser' => $this->activeUser,
            'isLogged' => $this->isLogged
        ]);
    }

    public function editAction()
    {
        $uid = (int)$this->params('id');
        if (empty($uid) || !is_int($uid)) {
            $msg = "Could not UPDATE user no valid id provided" . __METHOD__;
            error_log($msg);
            $this->flashMessenger()->addMessage($msg);
            return $this->redirect()->toRoute('user', ['action' => 'index']);
        }
        $user = $this->userTable->get($uid);
        if (!($user instanceof User)) {
            $msg = "Could not UPDATE user {$uid} from users table " . __METHOD__;
            error_log($msg);
            $this->flashMessenger()->addMessage($msg);
            return $this->redirect()->toRoute('user', ['action' => 'index']);
        }

        $practicesResultset = $this->practiceTable->fetchAll();

        $practices = [];
        foreach ($practicesResultset as $practice_row) {
            $practices[$practice_row->id] = $practice_row->name;
        }

        $form = new UserFormEdit($user, $practices);
        //$form->populate($user);   did not work: is supposed to auto populate???

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            $new_user = new User();
            $new_user->exchangeArray($data);

            // Validate form
            if ($form->isValid()) {
                //Do I validate CSRF? The answer is no. You don't have to do anything to check whether the hash is valid or not.
                //When you create a Zend_Form_Element_Hash element, it automatically adds a validator
                // (using Zend_Validate_Identical) to your form and register your hash into a new namespace session.
                if ($user->password !== trim($new_user->password)) {
                    $result = $this->auth->changePassword($user->id, $user->password, $new_user->password, $new_user->password);
                    if ($result['error'] == true) {
                        $msg = "Could not UPDATE user passsword IN users table: {$result['message']} " . __METHOD__;
                        error_log($msg);
                        $this->flashMessenger()->addMessage($msg);
                        return $this->redirect()->toRoute('user', ['action' => 'index']);
                    }
                }
                if ($user->email !== trim($new_user->email)) {
                    $this->auth->changeEmail($user->id, $new_user->email, $new_user->password);
                    if ($result['error'] === true) {
                        $msg = "Could not UPDATE user email IN users table: {$result['message']} " . __METHOD__;
                        error_log($msg);
                        $this->flashMessenger()->addMessage($msg);
                        return $this->redirect()->toRoute('user', ['action' => 'index']);
                    }
                }

                $result = $this->userTable->save($new_user);
                if ($result === false) {
                    $msg = "Could not UPDATE user to user table " . __METHOD__;
                    error_log($msg);
                    $this->flashMessenger()->addMessage($msg);
                }
                $this->flashMessenger()->addMessage("User {$new_user->email} UPDATED!");

                return $this->redirect()->toRoute('user', ['action' => 'index']);
            }
        }

        return new ViewModel([
            'form' => $form,
            'practices' => $practices,
        ]);
    }

    public function viewAction()
    {
        if ($this->getRequest()->isPost()) {
            return $this->redirect()->toRoute('user', ['action' => 'index']);
        }
        $uid = (int)$this->params('id');
        if (empty($uid) || !is_int($uid)) {
            $msg = "Could not VIEW user no valid id provided" . __METHOD__;
            error_log($msg);
            $this->flashMessenger()->addMessage($msg);
            return $this->redirect()->toRoute('user', ['action' => 'index']);
        }
        $user = $this->userTable->get($uid);
        if (!($user instanceof User)) {
            $msg = "Could not VIEW user {$uid} from users table " . __METHOD__;
            error_log($msg);
            $this->flashMessenger()->addMessage($msg);
            return $this->redirect()->toRoute('user', ['action' => 'index']);
        }

        $practicesResultset = $this->practiceTable->fetchAll();

        $practices = [];
        foreach ($practicesResultset as $practice_row) {
            $practices[$practice_row->id] = $practice_row->name;
        }

        $form = new UserFormView($user, $practices);

        return new ViewModel([
            'form' => $form,
            'practices' => $practices,
        ]);
        //$view->setTemplate('dashboard/user/edit');
        //return $view;
    }


    public function deleteAction()
    {
        $uid = (int)$this->params('id');
        if (empty($uid) || !is_int($uid)) {
            $msg = "Could not DELETE user `{$uid}` IN users table: " . __METHOD__;
            error_log($msg);
            $this->flashMessenger()->addMessage($msg);
            return $this->redirect()->toRoute('user', ['action' => 'index']);
        }
        $user = $this->userTable->get($uid);
        if (!($user instanceof User)) {
            $msg = "Could not DELETE user {$uid} from users table " . __METHOD__;
            error_log($msg);
            $this->flashMessenger()->addMessage($msg);
            return $this->redirect()->toRoute('user', ['action' => 'index']);
        }
        $result = $this->auth->deleteUser($uid, $user->password);
        if ($result['error'] === true) {
            $msg = "Could not DELETE user {$uid} from users table : {$result['message']}" . __METHOD__;
            error_log($msg);
            $this->flashMessenger()->addMessage($msg);
            return $this->redirect()->toRoute('user', ['action' => 'index']);
        }

        $this->userTable->delete($uid);

        $this->flashMessenger()->addMessage("User with email {$user->email} HAS BEEN DELETED!");
        return $this->redirect()->toRoute('user', ['action' => 'index']);
    }

    public function addAction()
    {

        $new_user = new Users();
        // $new_user->exchangeArray(array('id' => 0));
        $practices_row_set = $this->practiceTable->fetchAll();

        $practices = array();
        foreach ($practices_row_set as $practice_row) {
            // use pid as practice/learning center id
            $practices[$practice_row->id] = $practice_row->practice;
        }

        $form = new UserFormEdit($new_user, $practices);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            $new_user = new User();
            $new_user->exchangeArray($data);

            // Validate form
            if ($form->isValid()) {
                $result = $this->auth->register($new_user->email, $new_user->password, $new_user->password);
                if ($result['error'] == true) {
                    $msg = "Could not ADD user at users table: {$result['message']} " . __METHOD__;
                    error_log($msg);
                    $this->flashMessenger()->addMessage($msg);
                    return $this->redirect()->toRoute('user', ['action' => 'index']);
                } else {
                    $new_user->id = 0;
                    $result = $this->userTable->save($new_user);
                    if ($result == false) {
                        $msg = "Could not ADD user to user table " . __METHOD__;
                        error_log($msg);
                        $this->flashMessenger()->addMessage($msg);
                        return $this->redirect()->toRoute('user', ['action' => 'index']);
                    }
                }
                return $this->redirect()->toRoute('user', ['action' => 'index']);
            }
        }

        $view = new ViewModel([
            'form' => $form,
            'practices' => $practices,
        ]);
        $view->setTemplate('dashboard/user/edit');
        return $view;
    }


    public function sendErrorAction()
    {
        return new ViewModel();
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
        // Call the base class' onDispatch() first and grab the response
        $response = parent::onDispatch($e);

        // Set user layout
        $this->layout()->setTemplate('layout/table');

        // Return the response
        return $response;
    }

}
