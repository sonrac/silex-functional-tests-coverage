<?php

namespace sonrac\FCoverage\Tests\Stubs;

use sonrac\FCoverage\BootTraits;

/**
 * Class FirstBoot.
 */
class FirstBoot
{
    use TSecond, TFirst, BootTraits;

    /**
     * FirstBoot constructor.
     *
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->boot();
    }
}
