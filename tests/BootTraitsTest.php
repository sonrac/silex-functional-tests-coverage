<?php
/**
 * @author Donii Sergii <doniysa@gmail.com>.
 */

namespace sonrac\FCoverage\Tests;

use PHPUnit\Framework\TestCase;
use sonrac\FCoverage\Tests\Stubs\FirstBoot;

/**
 * Class BootTraitsTest.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class BootTraitsTest extends TestCase
{
    /**
     * Test boot trait.
     *
     * @throws \ReflectionException
     */
    public function testBoot()
    {
        $class = new FirstBoot();

        $this->assertEquals($class->var2, 333);
        $this->assertEquals($class->var, 123);
    }
}
