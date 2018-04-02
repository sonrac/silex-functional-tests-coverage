<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage\Tests;

use sonrac\FCoverage\BaseWebTest;

/**
 * Class TestBaseWebTest.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class TestBaseWebTest extends OnceMigrationWebTest
{
    /**
     * Test send request.
     *
     * @throws \ReflectionException
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    public function testRequestSend()
    {
        /** @var \sonrac\FCoverage\Tests\BaseWeb $controller */
        $controller = new BaseWeb();
        $controller->setUp();
        $controller->get('/')
            ->seeJsonStructure([
                'status' => 'OK',
            ])->seeStatusCode(200);
    }

    /**
     * {@inheritdoc}
     */
    public function testRunMigrations($class = BaseWeb::class)
    {
        parent::testRunMigrations($class);
    }
}

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
