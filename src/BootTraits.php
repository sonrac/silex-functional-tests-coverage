<?php

namespace sonrac\FCoverage;

use ReflectionClass;

/**
 * Trait BootTraits.
 * Boot traits in tests.
 *
 * For add new trait:
 * Create trait
 * Add method boot<TraitName>
 * Add to your test class BootTraits and custom trait
 * Add _boot() trait method call to test set-up
 *
 * @example
 * <code lang="php">
 * class AssertionTest extends PHPUnit\Framework\TestCase {
 *      use Tests\BootTraits,
 *          Tests\CustomTrait;
 *      public function setUp() {
 *          parent::setUp();
 *          $this->_boot();
 *      }
 *      public function tearDown() {
 *          parent::tearDown();
 *          $this->_unBoot();
 *      }
 * }
 *
 * class CustomTrait {
 *      public function bootCustomTrait() {
 *          // some run
 *      }
 *
 *      public function unBootCustomTrait() {
 *          // some run rollback boot changes
 *      }
 * }
 * </code>
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
trait BootTraits
{
    /**
     * Find and boot class traits.
     *
     * @throws \ReflectionException
     */
    protected function _boot()
    {
        $this->checkTraits('boot');
    }

    /**
     * Find and un-boot traits.
     *
     * @throws \ReflectionException
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function _unBoot()
    {
        $this->checkTraits('unBoot');
    }

    /**
     * Check method exists in trait and call it.
     *
     * @param string $prefix Method name prefix
     *
     * @throws \ReflectionException
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function checkTraits($prefix = '')
    {
        $reflection = new ReflectionClass(static::class);

        $traits = $reflection->getTraitNames();

        foreach ($traits as $trait) {
            $parts = explode('\\', $trait);
            $name = $parts[count($parts) - 1];
            $methodName = $prefix.ucfirst($name);
            if (method_exists($this, $methodName)) {
                $this->{$methodName}();
            }
        }
    }
}
