<?php

namespace sonrac\FCoverage\Tests\Stubs;

use sonrac\FCoverage\UnitTest;

/**
 * Class UnitTstStub.
 */
class UnitTstStub extends UnitTest
{
    use Tester;

    public function setupRun()
    {
        $this->setUp();
    }

    public function downRun()
    {
        $this->tearDown();
    }
}
