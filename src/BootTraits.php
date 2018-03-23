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
 * }
 *
 * class CustomTrait {
 *      public function bootCustomTrait() {
 *          // some run
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
        $reflection = new ReflectionClass(static::class);

        $traits = $reflection->getTraitNames();

        foreach ($traits as $trait) {
            $parts = explode('\\', $trait);
            $name  = $parts[count($parts) - 1];
            if (method_exists($this, 'boot'.$name)) {
                $this->{'boot'.$name}();
            }
        }
    }
}
