<?php

namespace sonrac\FCoverage\Tests\Stubs;

/**
 * Trait TSecond.
 */
trait TSecond
{
    public $var2;

    protected function bootTSecond()
    {
        $this->var2 = 333;
    }
}
