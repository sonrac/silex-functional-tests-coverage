<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage\Tests;

use sonrac\FCoverage\BaseWebTest;

/**
 * Class TestBaseWebTest
 *
 * @author Donii Sergii <s.donii@infomir.com>
 */
class TestBaseWebTest extends OnceMigrationWebTest
{
    public function testRequestSend()
    {
        /** @var \sonrac\FCoverage\Tests\BaseWeb $controller */
        $controller = new BaseWeb();
        $controller->setUp();
        $controller->get('/')
            ->seeJsonStructure([
                'status' => 'OK'
            ])->seeStatusCode(200);
    }

    /**
     * {@inheritdoc}
     */
    public function testRunMigrations($class = BaseWeb::class) {
        parent::testRunMigrations($class);
    }
}

class BaseWeb extends BaseWebTest
{
    /**
     * {@inheritdoc}
     */
    public static function getApplication()
    {
        return require __DIR__.'/app/app.php';
    }

    /**
     * @inheritDoc
     */
    public static function setUpMigration()
    {
        static::$migration->setBinDir(__DIR__.'/app/bin');
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this, $name], $arguments);
    }
}