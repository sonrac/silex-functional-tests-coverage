<?php
/**
 * @author Donii Sergii <doniysa@gmail.com>.
 */

namespace sonrac\FCoverage\Tests;

use sonrac\FCoverage\Tests\Stubs\BaseWeb;

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
     * @author Donii Sergii <doniysa@gmail.com>
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
