<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage\Tests;

use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use sonrac\FCoverage\BaseControllerTest;
use sonrac\FCoverage\MaxRedirectException;
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
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        if (!is_file(__DIR__.'/'.$this->dbFile)) {
            file_put_contents(__DIR__.'/'.$this->dbFile, '');
        }

        $this->controller = new ControllerTest();
    }

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
            ]);
        $this->controller->post('/create-user/222/222')
            ->seeInDatabase('users', 'username = \'222\'');
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
        $this->assertEquals(
            'No route found for "GET /not_found"',
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
    protected function tearDown()
    {
        parent::tearDown();

        if (is_file(__DIR__.'/'.$this->dbFile)) {
            unlink(__DIR__.'/'.$this->dbFile);
        }
    }
}

class ControllerTest extends BaseControllerTest
{
    /**
     * {@inheritdoc}
     */
    protected function createApplication()
    {
        return $this->application = static::getApplication();
    }

    public static function getApplication()
    {
        {
            $app = new Application();
            $app->get('/', function () use ($app) {
                return $app->json(['status' => 'OK']);
            });

            $app->post('/', function (Application $app) {
                return $app->json(['status' => 'OK_POST']);
            });

            $app->delete('/item/{id}', function (Application $app, $id) {
                return $app->json(['status' => 'OK_DELETE'.$id]);
            });

            $app->put('/item/{id}', function (Application $app, $id) {
                return $app->json(['status' => 'OK_PUT'.$id]);
            });

            $app->patch('/item/{id}', function (Application $app, $id) {
                return $app->json(['status' => 'OK_PATCH'.$id]);
            });

            $app->get('/redirect', function (Application $app) {
                return $app->redirect('/');
            });

            $app->get('/redirect-redirect', function (Application $app) {
                return $app->redirect('/redirect');
            });

            $app->get('/circle-redirect', function (Application $app) {
                return $app->redirect('/circle-redirect');
            });

            $app->register(new DoctrineServiceProvider(), [
                'db.options' => [
                    'path'   => __DIR__.'/out/db.sqlite',
                    'driver' => 'pdo_sqlite',
                ],
            ]);

            $app->post('/create-user/{username}/{password}', function (Application $app, $username, $password) {
                /** @var \Doctrine\DBAL\Connection $db */
                $db = $app['db'];
                $db->insert('users', [
                    'id'       => mt_rand(0, 99999),
                    'username' => $username,
                    'password' => $password,
                ]);
            });

            /** @var \Doctrine\DBAL\Connection $db */
            $db = $app['db'];
            $user = new Table('users');
            $user->addColumn('id', Type::INTEGER)
                ->setAutoincrement(true);
            $user->addColumn('username', Type::STRING)
                ->setLength(255)
                ->setNotnull(true);
            $user->addColumn('password', Type::STRING)
                ->setLength(255)
                ->setNotnull(true);
            $db->getSchemaManager()->createTable($user);

            $app->boot();

            return $app;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getClientApplication()
    {
        if (!$this->application) {
            return $this->application = static::getApplication();
        }

        return $this->application;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Call protected methods.
     *
     * @param string $name      Method name.
     * @param array  $arguments Arguments.
     *
     * @return mixed
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        }

        return $this->{$name}($arguments);
    }

    public function enableRedirect($count = 1)
    {
        $this->setAllowRedirect($count);
    }
}
