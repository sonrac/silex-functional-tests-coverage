<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage\Tests\app;

use sonrac\FCoverage\BaseControllerTest;

/**
 * Class FullCoverageTest.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class FullCoverageTest extends BaseControllerTest
{
    /**
     * {@inheritdoc}
     */
    public function getApplication()
    {
        return require __DIR__.'/app/app.php';
    }

    public static function setUpMigration()
    {
        static::$migration->setBinDir(__DIR__.'/app/bin');
    }

    public function testIndexPage()
    {
        $this->get('/')
            ->seeJsonStructure(['status'])
            ->seeStatusCode(200);
    }

    public function testUpdateItem()
    {
        $this->put('/item/4')
            ->seeJsonStructure(['status' => 'OK_PUT4'])
            ->seeStatusCode(200);
    }
}
