<?php

namespace sonrac\FCoverage\Tests\Stubs;

/**
 * Trait TFirst.
 */
trait TFirst
{
    public $var;

    protected function bootTFirst()
    {
        $this->var = 123;
    }
}
