<?php
namespace Dashboard\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Dashboard\Form\LoginForm;
use Zend\Db\Adapter\Adapter;
use PHPAuth;
use Zend\Session\Container;

/**
 * This is the controller class displaying a page with the Model Registration form.
 * Model registration has several steps, so we display different form elements on
 * each step. We use session container to remember user's choices on the previous
 * steps.
 */
class LoginController extends AbstractActionController
{
    /**
     * Session container.
     * @var \Zend\Session\Container
     */
    private $sessionDashboard;
    private $dbAdapter;
    private $auth;

    /**
     * Constructor. Its goal is to inject dependencies into controller.
     *
     * @var $sessionDashboard \Zend\Session\Container --> session injection
     * @var $dbAdapter \Zend\Db\Adapter\Adapter --> session injection
     * @var $auth PHPAuth\Auth --> PHPAuth injection
     */
    public function __construct(Container $sessionDashboard, Adapter $dbAdapter, PHPAuth\Auth $auth )
    {
        $this->sessionDashboard = $sessionDashboard;
        $this->dbAdapter = $dbAdapter;
        $this->auth = $auth;
    }
    
    /**
     * This is the default "dashboard" action of the controller. It displays the
     * Model Registration page.
     */
    public function indexAction() 
    {
        $errorMsg = '';

        $form = new LoginForm();

        // Check if user has submitted the form
        if($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();

                $login = $this->auth->login($data['email'], $data['password'], 1);

                if ($login['error'] === true) {

                    $viewModel = new ViewModel([
                        'form' => $form,
                        'errorMsg' => $login['message']
                    ]);
                    $viewModel->setTemplate("dashboard/login/index");
                    return $viewModel;
                } else {
                    if(isset($_SERVER['PHPUnit']) && $_SERVER['PHPUnit'] == 'yes'){
                        $_COOKIE['name'] = $this->auth->config->cookie_name;
                        $_COOKIE['hash'] = $login['hash'];
                    } else {
                        $result = setcookie($this->auth->config->cookie_name, $login['hash'], $login['expire'], $this->auth->config->cookie_path, $this->auth->config->cookie_domain, $this->auth->config->cookie_secure, $this->auth->config->cookie_http);
                        if ($result == false) {
                            $msg = "You need to ALLOW COOKIES on your BROWSER for this application";
                            error_log($msg);
                            $this->flashMessenger()->addMessage($msg);
                            return $this->redirect()->toRoute('login', ['action' => 'index']);
                        }
                    }
                    $resultSet = $this->dbAdapter->query('SELECT * FROM `user` WHERE `email` = ?', [$data['email']]);
                    $user = $resultSet->toArray()[0];
                    $user['hash'] = $login['hash'];
                    $user['step'] = 1;
                    $this->sessionDashboard->user = $user;
                    error_log("redirecting to dashboard/index");
                    return $this->redirect()->toRoute('dashboard', ['action'=>'index']);
                }
                // Save user choices in session.
            }
        }
        
        $viewModel = new ViewModel([
            'form' => $form,
            'errorMsg' => $errorMsg
        ]);
        $viewModel->setTemplate("dashboard/login/index");
        
        return $viewModel;
    }
    
    /**
     * The "login/dashboard" action shows a dashboard page for (testing only)
     */
    public function dashboardAction()
    {
        $result = $this->auth->isLogged();

        if ($result === false) {
            return $this->redirect()->toRoute('login', ['action'=>'logout'], ['message'=>'you need to login']);
        }
        $resultSet = $this->dbAdapter->query('SELECT * FROM `user` WHERE `id` = ?', [1]);
        $result = $resultSet->toArray();

        // Retrieve user choices from session.
        $session = $this->sessionContainer->data;
        
        return new ViewModel([
            'result'      => $result,
            'session'     => $session
        ]);
    }

    public function logoutAction()
    {
        $this->auth->logout($this->auth->getSessionHash());
        session_destroy();
        $result = setcookie($this->auth->config->cookie_name, 'loggedout', time() - 1, $this->auth->config->cookie_path, $this->auth->config->cookie_domain, $this->auth->config->cookie_secure, $this->auth->config->cookie_http);
        if ($result == false) {
            echo "<h1>COULD NOT SET COOKIE!! You must enable cookies on your browser for this site!</h1>";
            exit;
        }
        return $this->redirect()->toRoute('home');
    }
}

