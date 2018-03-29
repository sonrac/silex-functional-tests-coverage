<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage\Tests;

use PHPUnit\Framework\TestCase;
use sonrac\FCoverage\UnitTest;

/**
 * Class TestUnitTest
 *
 * @author Donii Sergii <s.donii@infomir.com>
 */
class TestUnitTest extends TestCase
{
    public function testBootTraits() {
        $controller = new UnitTester();
        $this->assertNull($controller->a);

        $controller->setupRun();

        $this->assertEquals(123, $controller->a);
        $controller->downRun();
        $this->assertNull($controller->a);
    }
}

class UnitTester extends UnitTest {
    use Tester;

    public function setupRun() {
        $this->setUp();
    }

    public function downRun() {
        $this->tearDown();
    }
}

trait Tester {

    public  $a;

    protected function rollback() {
        $this->a = null;
    }

    protected function bootTester() {
        $this->a = 123;
    }
}
