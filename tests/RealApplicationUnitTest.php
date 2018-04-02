<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage\Tests;

use sonrac\FCoverage\BaseControllerTest;

/**
 * Class RealApplicationUnitTest.
 *
 * @author Donii Sergii <s.donii@infomir.com>
 */
class RealApplicationUnitTest extends BaseControllerTest
{
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
     * @author Donii Sergii <s.donii@infomir.com>
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
