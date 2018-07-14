<?php

namespace sonrac\FCoverage\Tests\Stubs;

/**
 * Trait Tester.
 */
trait Tester
{
    public $a;

    protected function rollback()
    {
        $this->a = null;
    }

    protected function bootTester()
    {
        $this->a = 123;
    }
}
