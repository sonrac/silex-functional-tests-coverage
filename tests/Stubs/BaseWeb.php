<?php

namespace sonrac\FCoverage\Tests\Stubs;

use sonrac\FCoverage\BaseWebTest;

/**
 * Class BaseWeb.
 */
class BaseWeb extends BaseWebTest
{
    /**
     * {@inheritdoc}
     */
    public function getAppClass()
    {
        return \TApp::class;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this, $name], $arguments);
    }
}
