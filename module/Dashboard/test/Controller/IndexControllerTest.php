<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Dashboard\Test\Controller;

use Dashboard\Controller\DashboardController;
use Zend\Dom\Document;
use Zend\Dom\Document\Query;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class DashboardControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        //

        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
    }

    public function testApplicationIndexActionCanBeAccessed()
    {
        error_log('TESTING' . __METHOD__);
        $this->dispatch('/', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('application');
        $this->assertControllerName('application\controller\indexcontroller');
    }

    public function testLoginIndexActionCanBeAccessed()
    {
        error_log('TESTING' . __METHOD__);
        $this->dispatch('/login', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('application');
        $this->assertControllerName('application\controller\logincontroller');
    }

    public function testSuccessfullLoginRedirectsToDashboard()
    {
        error_log('TESTING'.__METHOD__);
        //$this->dispatch('/', 'GET');
        //$this->assertResponseStatusCode(200);
        //$this->assertModuleName('application');

        // fetch content of the page into DOMDocument
        // $this->dispatch('/login', 'GET');
        // $html = $this->getResponse()->getBody();

        // work around know issue with \DOMDocument
        // $search = array("<header>", "</header>", "<nav>", "</nav>", "<section>", "</section>","<footer>","</footer>","<article>","</article>");
        // $replace = array("<div>", "</div>","<div>", "</div>", "<div>", "</div>","<div>", "</div>","<div>", "</div>");
        // $html = str_replace($search, $replace, $html);
        // $doc = new \DOMDocument();
        // $doc->loadHtml($html);
        // $csrf = $doc->getElementById('csrf');

        //
        // $doc = new Document($html);
        // parse page content, find the hash value pre-filled to the hidden element
        // $dom = new Query();
        // $DOMnodes = $dom->execute('input',$doc);
        // foreach ($DOMnodes as $DOMElement) {
        //    $csrf = $DOMElement->nodeValue;
        //}

        // $this->dispatch('/login', 'GET');
        // $this->assertControllerName(LoginController::class);
        $this->dispatch('/login', 'POST',
            [
                'email' => 'harry@potter.com',
                'password' => 'W2e3r4T5@',
                // 'csrf' => $csrf
            ]
        );
        $this->assertMatchedRouteName('login');
        $this->assertModuleName('application');
        $this->assertControllerName('application\controller\logincontroller');
        $this->assertControllerClass('LoginController');
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/dashboard');
    }

    /*
        public function testIndexActionViewModelTemplateRenderedWithinLayout()
        {
            $this->dispatch('/', 'GET');
            $this->assertQuery('.container .jumbotron');
        }
    */
    public function testInvalidRouteDoesNotCrash()
    {
        error_log('TESTING'.__METHOD__);
        $this->dispatch('/dashboard/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }

    public function testUserCanBeEdited()
    {
        error_log('TESTING'.__METHOD__);

        $this->dispatch('/login', 'POST',
            [
                'email' => 'harry@potter.com',
                'password' => 'W2e3r4T5@',
                // 'csrf' => $csrf
            ]
        );
        $this->assertMatchedRouteName('login');
        $this->assertModuleName('application');
        $this->assertControllerName('application\controller\logincontroller');
        $this->assertControllerClass('LoginController');
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/dashboard');

        $this->dispatch('/user', 'GET');

        ///////////// IS REDIRECTING TO DASHBOARD index ???? //////
        $this->assertResponseStatusCode(200);
        $this->assertRedirectTo('/user');
        /////////////////

        $this->dispatch('user/edit/1', 'POST',
            [
                'id' => '4',
                'email' => 'test@test.com',
                'user' => 'madeye_ut',
                'password' => 'Q1w2e3R4@',
                'name' => 'Mad Eye',
                'role' => 'admin',
            ]
        );

        //$this->assertModuleName('application');
        //$this->assertControllerName('login\controller\logincontroller');
        $this->assertResponseStatusCode(302);
        //$this->assertRedirectTo('/login');
    }

}
