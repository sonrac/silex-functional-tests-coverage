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
    public static $appPath = 'app/app.php';

    /**
     * {@inheritdoc}
     */
    public static function getApplication($dir = __DIR__)
    {
        return parent::getApplication($dir);
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
