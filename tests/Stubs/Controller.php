<?php

namespace sonrac\FCoverage\Tests\Stubs;

use sonrac\FCoverage\BaseControllerTest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Controller.
 */
class Controller extends BaseControllerTest
{
    /**
     * {@inheritdoc}
     */
    protected static $runMigration = true;

    /**
     * {@inheritdoc}
     */
    public function getClientApplication()
    {
        $class = $this->getAppClass();

        return $class::getInstance()->getApplication();
    }

    public function getAppClass()
    {
        return \TApp::class;
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
        return call_user_func_array([$this, $name], $arguments);
    }

    public function enableRedirect($count = 1)
    {
        $this->setAllowRedirect($count);
    }
}
