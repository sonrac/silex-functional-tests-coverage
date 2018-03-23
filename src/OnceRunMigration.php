<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage;

/**
 * Class OnceRunMigration
 * TestCase with migration running.
 *
 * @author Donii Sergii <s.donii@infomir.com>
 */
class OnceRunMigration
{
    use MigrationsTrait, BootTraits;

    /**
     * Application instance.
     *
     * @var object
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    protected $app;

    /**
     * Seeds list.
     *
     * @var array
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    protected $seeds = [];

    /**
     * OnceRunMigration constructor.
     *
     * @param object $application Application instance
     * @param array  $seeds       Seeds list
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    public function __construct($application, $seeds = [])
    {
        $this->app = $application;
        $this->seeds = $seeds;
    }
}