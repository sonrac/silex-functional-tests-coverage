<?php

namespace sonrac\FCoverage;

use PHPUnit\Framework\TestCase;

/**
 * Class UnitTest
 * Unit test with auto boot traits and rollback migrations (if trait are included in test).
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
abstract class UnitTest extends TestCase
{
    use BootTraits;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->_boot();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        if (method_exists($this, 'rollback')) {
            $this->rollback();
        }
    }
}
