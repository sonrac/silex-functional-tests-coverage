<?php
/**
 * @author Donii Sergii <doniysa@gmail.com>.
 */

namespace sonrac\FCoverage\Tests;

use sonrac\FCoverage\BaseControllerTest;

/**
 * Class RealApplicationUnitTest.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class RealApplicationUnitTest extends BaseControllerTest
{
    /**
     * {@inheritdoc}
     */
    protected static $runMigration = true;

    protected static $seeds = ['users'];

    /**
     * {@inheritdoc}
     */
    public static function prepareMigrations()
    {
        $class = (new static())->getAppClass();
        $class::getInstance()
            ->getMigration()
            ->setSeedClassEnding('Seeds')
            ->setSeedNamespace('sonrac\\\\FCoverage\\\\Tests\\\\Seeds\\\\');
    }

    /**
     * {@inheritdoc}
     */
    public function getAppClass()
    {
        return \TApp::class;
    }

    /**
     * Test get users list.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testGetUsers()
    {
        $data = $this->get('/users/list')
            ->seeStatusCode(200)
            ->seeJsonStructure([
                'status' => 'OK',
                'items'  => [
                    ['id', 'user'],
                ],
            ]);
    }
}
