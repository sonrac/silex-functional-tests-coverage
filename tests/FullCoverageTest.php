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
    public function getAppClass()
    {
        return \TApp::class;
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
