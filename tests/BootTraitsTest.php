<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage\Tests;

use PHPUnit\Framework\TestCase;
use sonrac\FCoverage\BootTraits;

/**
 * Class BootTraitsTest.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class BootTraitsTest extends TestCase
{
    public function testBoot()
    {
        $class = new FirstBoot();

        $this->assertEquals($class->var2, 333);
        $this->assertEquals($class->var, 123);
    }
}

trait TFirst
{
    public $var;

    protected function bootTFirst()
    {
        $this->var = 123;
    }
}

trait TSecond
{
    public $var2;

    protected function bootTSecond()
    {
        $this->var2 = 333;
    }
}

class FirstBoot
{
    use TSecond, TFirst, BootTraits;

    public function __construct()
    {
        $this->_boot();
    }
}
