<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * Class BaseControllerTest
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
abstract class BaseControllerTest extends OnceMigrationUnitTest
{
    use ControllersTestTrait;

    /**
     * Request object.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected $request;

    /**
     * Application instance.
     *
     * @var \Silex\Application
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected $application;

    /**
     * Is admin controller. Used for application detect.
     *
     * @var bool
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected $isAdminController = false;

    /**
     * Response object.
     *
     * @var \Symfony\Component\HttpFoundation\Response
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected $response;

    /**
     * {@inheritdoc}
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setUp()
    {
        $this->application = $this->isAdminController ?
            require __DIR__.'/../../../bootstrap/app.php'
            : require __DIR__.'/../../../bootstrap/api.php';

        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function request(
        $method,
        $uri,
        array $parameters = [],
        array $files = [],
        array $server = [],
        $content = null,
        $changeHistory = true
    ) {
        // Clear previous request
        $this->request = null;

        // Parse get parameters
        $routeURL = parse_url($uri);
        $query = !empty($routeURL['query']) ? $routeURL['query'] : [];

        // Set server variables and fill $_POST/$_GET
        $body = $this->prepareParams($method, $parameters, $query);
        $this->prepareServerVariables([
            'server'        => $server,
            'uri'           => $uri,
            'changeHistory' => $changeHistory,
            'files'         => $files,
            'parameters'    => $parameters,
            'method'        => $method,
            'body'          => $body ?: ''
        ]);

        // Get controller
        $controller = $this->getController($method, $routeURL['path']);

        if ($controller instanceof Response) {
            $this->response = $controller;

            return $this;
        }

        $this->request->attributes->add([
            '_controller' => $controller
        ]);

        $this->response = $this->getResponse($controller);

        return $this;
    }

    /**
     * Prepare $_POST/$_GET params before request set.
     *
     * @param string       $method Query method
     * @param array        $data   Post data
     * @param string|array $query  Query string
     *
     * @return string|null
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function prepareParams($method, $data, $query)
    {
        switch (strtolower($method)) {
            case 'post':
                $_POST = array_merge($_POST, $data);
                $get = [];
                if (is_string($query) && !empty($query)) {
                    parse_str($query, $get);
                }
                $_GET = array_merge($_GET, $get);
                if (count($data)) {
                    return json_encode($data);
                }
                break;
            case 'get':
                $get = [];
                if (is_string($query) && !empty($query)) {
                    parse_str($query, $get);
                }
                $_GET = array_merge($_GET, $get);
                break;
            default:
                return http_build_query($data);
                break;
        }
    }

    /**
     * Get response from closure callback.
     *
     * @param \Closure $callback      Closure for run
     * @param bool     $allowRedirect Allow redirects
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Throwable
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function getResponse($callback, $allowRedirect = false)
    {
        /* Resolve arguments and run controller action. */
        $controllerResolver = new ControllerResolver();
        /* Resolve controller name */
        $controller = $controllerResolver->getController($this->request);
        /* Resolve arguments */
        $argumentResolver = new ArgumentResolver($this->application['argument_metadata_factory'],
            $this->application['argument_value_resolvers']);
        $arguments = $argumentResolver->getArguments($this->request, $controller);

        /* Call controller */
        try {
            $response = call_user_func_array($callback, $arguments);
        } catch (HttpException $httpException) {
            return $this->returnResponseFromHttpException($httpException);
        }

        if ($response instanceof RedirectResponse && $allowRedirect) {
            /* Disable redirect response */
            throw new \InvalidArgumentException('Not implement redirects yet');
        }

        /* Trigger after middleware */
        $this->triggerKernelEvent(
            KernelEvents::RESPONSE,
            new FilterResponseEvent(
                $this->application,
                $this->request,
                HttpKernelInterface::MASTER_REQUEST,
                $response
            )
        );

        return $response;
    }

    /**
     * Get controller instance from request URI.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Throwable
     * @throws \Exception
     *
     * @return string|\Closure
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function getController($method, $uri)
    {
        $method = strtoupper($method);
        $index = $method.'_'.$this->shackCase($uri);
        if (!$this->request) {
            $this->request = Request::createFromGlobals();
        }
        $this->application->handle($this->request);

        $response = $this->triggerKernelEvent(
            KernelEvents::REQUEST,
            new GetResponseEvent(
                $this->application,
                $this->request,
                HttpKernelInterface::MASTER_REQUEST
            )
        );

        if ($response) {
            // Http exception found, return response without call controller (emulate before middleware)
            return $response;
        }

        /** @var \Silex\Route $route */
        $route = $this->application['routes']->get($index);

        if (!$route) { // Fix match route
            $index = str_replace($method, '', $index);
            $route = $this->application['routes']->get($index);
        }

        if (!$route) {
            foreach ($this->application['routes'] as $_route) { // Find by path. Index not found
                /** @var $_route \Silex\Route */
                if ($_route->getPath() === $uri) {
                    $route = $_route;
                    break;
                }
            }

            if (!$route) {
                throw new RouteNotFoundException('Route not found');
            }
        }

        /** @var \Closure $closure */
        return $route->getDefault('_controller');
    }

    /**
     * Return response object from \Symfony\Component\HttpKernel\Exception\HttpException exception.
     *
     * @param \Symfony\Component\HttpKernel\Exception\HttpException $httpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function returnResponseFromHttpException(HttpException $httpException)
    {
        $response = new Response();
        $response->setContent($httpException->getMessage());
        $response->setStatusCode($httpException->getStatusCode());
        $response->headers->add($httpException->getHeaders());

        return $response;
    }

    /**
     * Trigger kernel event
     *
     * @param string                                   $eventName Event name
     * @param \Symfony\Component\EventDispatcher\Event $class     Event class
     *
     * @throws \Throwable
     * @throws \Exception
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return null|Response
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function triggerKernelEvent($eventName, $class = null)
    {
        /** @var \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher */
        $dispatcher = $this->application['dispatcher'];

        /* Emulate request event for prepare middleware & other providers initialization */
        ob_start();
        try {
            $dispatcher->dispatch($eventName, $class);
        } catch (HttpException $httpException) { // If http exception, return response with error
            ob_end_clean();
            return $this->returnResponseFromHttpException($httpException);
        } catch (\Exception $exception) {
            ob_end_clean();
            throw $exception;
        } catch (\Throwable $throwable) {
            ob_end_clean();
            throw $throwable;
        }
        ob_end_clean();

        return null;
    }

    /**
     * Prepare server variables.
     *
     * @param array $options
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function prepareServerVariables($options)
    {
        $_SERVER['REQUEST_URI'] = $options['uri'];
        $_SERVER['REQUEST_METHOD'] = strtoupper($options['method']);
        $_SERVER = array_merge($_SERVER, $options['server']);

        if ($options['body']) {
            $this->request = Request::create(
                $options['uri'],
                $_SERVER['REQUEST_METHOD'],
                $options['parameters'],
                $_COOKIE,
                $options['files'],
                $_SERVER,
                $options['body']
            );
        }
    }

    /**
     * Snack case.
     *
     * @param string $input
     *
     * @return string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private function shackCase($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match === strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
}