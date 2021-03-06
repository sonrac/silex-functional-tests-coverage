<?php
/**
 * @author Donii Sergii <doniysa@gmail.com>.
 */

namespace sonrac\FCoverage\Tests;

use PHPUnit\Framework\TestCase;
use Silex\Application;
use sonrac\FCoverage\MaxRedirectException;
use sonrac\FCoverage\Tests\Stubs\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseControllerTestTest
 * Base controller tester.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class BaseControllerTestTest extends TestCase
{
    /**
     * Database file path.
     *
     * @var string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $dbFile = 'out/db.sqlite';

    /**
     * Test controller instance.
     *
     * @var \Silex\Tests\ControllerTest
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $controller;

    /**
     * Test create application.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testCreateApplication()
    {
        $this->assertInstanceOf(Application::class, $this->controller->getClientApplication());
    }

    /**
     * Test simple response methods.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testSimpleCheckMethods()
    {
        $response = new Response();
        $response->setStatusCode(200);
        $response->setContent(json_encode(['status' => 'OK', 'result' => ['data' => 123, 'test' => 234]]));
        $response->headers->add([
            'test-header' => 'test-header',
            'bearer'      => 'token',
        ]);
        $this->controller->setResponse($response);

        $this->assertInstanceOf(
            get_class($this->controller),
            $this->controller
                ->seeStatusCode(200)
                ->seeJsonStructure(['status', 'result' => ['data' => 123, 'test']])
                ->seeHeader('test-header', 'test-header')
                ->seeHeader('bearer')
        );
    }

    /**
     * Test database methods.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testDatabaseMethods()
    {
        $this->controller->getClientApplication();
        $this->controller->post('/create-user/123/123')
            ->seeInDatabase('users', [
                'username' => 123,
            ])->seeCountInTable('users', 1)
            ->seeCountInTable('users', 1, [])
            ->seeCountInTable('users', 1, ['username' => 123]);
        $this->controller->post('/create-user/222/222')
            ->seeInDatabase('users', 'username = \'222\'');

        $this->controller->deleteAllInTable('users');
        $this->controller->seeCountInTable('users', 0);

        $this->controller->getResponseObject()->setContent('<span class="t"></span><span class="t"></span>');

        $this->controller->seeCountHtmlElement('.t', 2);
        $this->controller->seeHtmlElement('.t');
    }

    /**
     * Test get request.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testGetRequest()
    {
        $this->controller->getClientApplication();
        $this->controller->get('/?id=1');
        $this->assertEquals('{"status":"OK"}', $this->controller->getResponseObject()->getContent());
    }

    /**
     * Test get request.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testPostRequest()
    {
        $this->controller->getClientApplication();
        $this->controller->post('/?id=1');
        $this->assertEquals('{"status":"OK_POST"}', $this->controller->getResponseObject()->getContent());
    }

    /**
     * Test get request.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testPutRequest()
    {
        $this->controller->getClientApplication();
        $this->controller->put('/item/2');
        $this->assertEquals('{"status":"OK_PUT2"}', $this->controller->getResponseObject()->getContent());
    }

    /**
     * Test delete request.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testDeleteRequest()
    {
        $this->controller->getClientApplication();
        $this->controller->delete('/item/1');
        $this->assertEquals('{"status":"OK_DELETE1"}', $this->controller->getResponseObject()->getContent());
    }

    /**
     * Test patch request.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testPatchRequest()
    {
        $this->controller->getClientApplication();
        $this->controller->patch('/item/5');
        $this->assertEquals('{"status":"OK_PATCH5"}', $this->controller->getResponseObject()->getContent());
    }

    /**
     * Test patch request.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testPatchWithBodyRequest()
    {
        $this->controller->getClientApplication();
        $this->controller->patch('/item/5', [
            'model' => 'model',
        ]);
        $this->assertEquals('{"status":"OK_PATCH5"}', $this->controller->getResponseObject()->getContent());
    }

    /**
     * Test route not found.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testRouteNotFound()
    {
        $this->controller->getClientApplication();
        $this->assertContains(
            'the page you are looking for could not be found',
            $this->controller->get('/not_found')->getResponseObject()->getContent()
        );
        $this->controller->seeStatusCode(404);
    }

    /**
     * Test redirect with disable redirect enable option.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testRedirectResponseWithDisableRedirects()
    {
        $this->controller->getClientApplication();
        $this->assertInstanceOf(
            RedirectResponse::class,
            $this->controller->get('/redirect')->getResponseObject()
        );
    }

    /**
     * Test redirect with disable redirect enable option.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testRedirectResponseWithEnableRedirect()
    {
        $this->controller->getClientApplication();
        $this->controller->enableRedirect(2);
        $this->controller->get('/redirect');
        $this->assertEquals('{"status":"OK"}', $this->controller->getResponseObject()->getContent());
    }

    /**
     * Test exception redirect.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testExceptionWithEnableRedirect()
    {
        $this->controller->getClientApplication();
        $this->controller->enableRedirect(2);

        if (method_exists($this, 'expectException')) {
            $this->expectException(MaxRedirectException::class);
        } else {
            $this->setExpectedException(MaxRedirectException::class);
        }
        $this->controller->get('/circle-redirect');
    }

    /**
     * Test get redirect without exception.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testDisableRedirectResponse()
    {
        $this->controller->getClientApplication();
        $this->controller->setThrowExceptionOnRedirect(false);
        $this->controller->enableRedirect(2);
        $this->controller->get('/circle-redirect');

        $this->controller->seeStatusCode(302);
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        file_put_contents(__DIR__.'/'.$this->dbFile, '');

        $this->controller = new Controller();
        $this->controller->setUp();
        $this->controller->setUpBeforeClass();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->controller->tearDown();
        $this->controller->tearDownAfterClass();

        if (is_file(__DIR__.'/'.$this->dbFile)) {
            unlink(__DIR__.'/'.$this->dbFile);
        }
    }
}
