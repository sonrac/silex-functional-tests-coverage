<?php
/**
 * @author Donii Sergii <doniysa@gmail.com>.
 */

namespace sonrac\FCoverage\Tests;

use PHPUnit\Framework\TestCase;
use sonrac\FCoverage\Tests\Stubs\UnitTstStub;

/**
 * Class TestUnitTest.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class TestUnitTest extends TestCase
{
    /**
     * Test traits boot.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testBootTraits()
    {
        $controller = new UnitTstStub();
        $this->assertNull($controller->a);

        $controller->setupRun();

        $this->assertEquals(123, $controller->a);
        $controller->downRun();
        $this->assertNull($controller->a);
    }
}
