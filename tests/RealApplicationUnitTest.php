<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage\Tests;

use sonrac\FCoverage\BaseControllerTest;

/**
 * Class RealApplicationUnitTest
 *
 * @author Donii Sergii <s.donii@infomir.com>
 */
class RealApplicationUnitTest extends BaseControllerTest
{
    protected static $seeds = [];

    /**
     * @inheritDoc
     */
    public function getAppClass()
    {
        return \TApp::class;
    }

}