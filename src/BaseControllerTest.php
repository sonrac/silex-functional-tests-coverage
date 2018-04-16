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
use Symfony\Component\Routing\RouteCompiler;

/**
 * Class BaseControllerTest.
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
    protected $app;

    /**
     * Response object.
     *
     * @var \Symfony\Component\HttpFoundation\Response
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected $response;

    /**
     * Origin server vars.
     *
     * @var array
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $originServerVars;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->originServerVars = $_SERVER;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Throwable
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
        if ($this->isClearCountRedirects()) {
            $this->setCountRedirects(0);
        }

        // Clear previous request
        $this->request = null;

        // Parse get parameters
        $routeURL = parse_url($uri);
        $query = !empty($routeURL['query']) ? $routeURL['query'] : [];

        // Set server variables and fill $_POST/$_GET
        $body = $this->prepareParams($method, $parameters, $query);

        if (!$content && $body) {
            $content = $body;
        }

        $this->prepareServerVariables([
            'server'        => $server,
            'uri'           => $uri,
            'changeHistory' => $changeHistory,
            'files'         => $files,
            'parameters'    => $parameters,
            'method'        => $method,
            'body'          => $content,
        ]);

        // Get controller
        $controller = $this->getControllerActionFromRouteConfig($method, $routeURL['path']);

        if ($controller instanceof Response) {
            $this->response = $controller;

            return $this;
        }

        $this->request->attributes->add([
            '_controller' => $controller,
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
        }
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
     * Get controller instance from request URI.
     *
     * @param string $method
     * @param string $uri
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Throwable
     * @throws \Exception
     *
     * @return string|\Closure|\Symfony\Component\HttpFoundation\Response
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function getControllerActionFromRouteConfig($method, $uri)
    {
        $method = strtoupper($method);
        $index = $method.'_'.$this->shackCase($uri);
        if (!$this->request) {
            $this->request = Request::createFromGlobals();
        }
        $this->setApplication($this->getApplication());
        $this->app->handle($this->request);

        $response = $this->triggerKernelEvent(
            KernelEvents::REQUEST,
            new GetResponseEvent(
                $this->getApplication(),
                $this->request,
                HttpKernelInterface::MASTER_REQUEST
            )
        );

        if ($response) {
            // Http exception found, return response without call controller (emulate before middleware)
            return $response;
        }

        /** @var \Silex\Route|null $route */
        $route = $this->app['routes']->get($index);

        if (!$route) { // Fix match route
            $index = str_replace($method, '', $index);
            $route = $this->app['routes']->get($index);
        }

        if (!$route) {
            /** @var \Symfony\Component\Routing\RouteCollection $routes */
            $routes = $this->app['routes'];
            foreach ($routes as $_route) { // Find by path. Index not found
                /** @var \Silex\Route $_route */
                $compileRoute = RouteCompiler::compile($_route);

                $matched = preg_match($compileRoute->getRegex(), $uri) &&
                           in_array(
                               $this->request->getMethod(),
                               $_route->getMethods()
                           );
                if (($_route->getPath() === $uri) || $matched) {
                    $route = $_route;
                    break;
                }
            }

            if (!$route) {
                throw new RouteNotFoundException('Route not found');
            }
        }

        /* @var \Closure $closure */
        return $route->getDefault('_controller');
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
        $parts = $matches[0];
        foreach ($parts as &$match) {
            $match = $match === strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $parts);
    }

    /**
     * Get application.
     *
     * @return \Silex\Application
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    public function getApplication()
    {
        return $this->app ?: $this->app = $this->createApplication();
    }

    /**
     * Set application.
     *
     * @param \Silex\Application $app
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setApplication($app)
    {
        $this->app = $app;
    }

    /**
     * Create application.
     *
     * @return \Silex\Application
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function createApplication()
    {
        $class = static::getAppClass();

        return $class::getInstance()
            ->getApplication();
    }

    /**
     * Trigger kernel event.
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
        $this->setApplication($this->getApplication());
        /** @var \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher */
        $dispatcher = $this->app['dispatcher'];

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
     * Get response from closure callback.
     *
     * @param \Closure $callback Closure for run
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Throwable
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function getResponse($callback)
    {
        $this->setClearCountRedirects(false);
        /* Resolve arguments and run controller action. */
        $controllerResolver = new ControllerResolver();
        /* Resolve controller name */
        $controller = $controllerResolver->getController($this->request);
        /* Resolve arguments */
        $this->setApplication($this->getApplication());
        $argumentResolver = new ArgumentResolver($this->app['argument_metadata_factory'],
            $this->app['argument_value_resolvers']);
        $arguments = $argumentResolver->getArguments($this->request, $controller);

        /* Call controller */
        try {
            $response = call_user_func_array($callback, $arguments);
        } catch (HttpException $httpException) {
            return $this->returnResponseFromHttpException($httpException);
        }

        /* Allow redirect in unit tests */
        if ($response instanceof RedirectResponse && $this->getAllowRedirect() !== false) {
            $this->incrementCountRedirects();

            if ($this->getCountRedirects() > $this->getAllowRedirect()) {
                if ($this->isThrowExceptionOnRedirect() && ((int) $this->getAllowRedirect() !== 0)) {
                    $exception = new MaxRedirectException();
                    $exception->setCountRedirects($this->__countRedirects);

                    throw $exception;
                }

                $this->response = $response;
            } else {
                $url = $response->getTargetUrl();
                $headers = $response->headers->all();
                $_SERVER = array_merge($this->originServerVars, $this->getPredefinedServerVars());

                $_SERVER['REQUEST_URI'] = $url;

                foreach ($headers as $header => $value) {
                    $_SERVER['HTTP_'.strtoupper($header)] = $value;
                }

                $this->request('GET', $url, [], [], $_SERVER, $response->getContent(), true);
                $response = $this->getResponseObject();
            }
        }

        if ($response) {
            /* Trigger after middleware */
            $this->triggerKernelEvent(
                KernelEvents::RESPONSE,
                new FilterResponseEvent(
                    $this->getApplication(),
                    $this->request,
                    HttpKernelInterface::MASTER_REQUEST,
                    $response
                )
            );
        }

        return $response;
    }

    /**
     * Get predefined server vars.
     *
     * @return array
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function getPredefinedServerVars()
    {
        return [];
    }

    /**
     * Get response object.
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getResponseObject()
    {
        return $this->response;
    }
}
