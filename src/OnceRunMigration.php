<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage;

/**
 * Class OnceRunMigration
 * TestCase with migration running.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class OnceRunMigration
{
    use MigrationsTrait, BootTraits;

    /**
     * Seeds list.
     *
     * @var array
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected static $seeds = [];
    /**
     * Application instance.
     *
     * @var object
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected $app;

    /**
     * OnceRunMigration constructor.
     *
     * @param object $app Application instance
     * @param array  $seeds       Seeds list
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function __construct($app, $seeds = [])
    {
        $this->app = $app;
        static::$seeds = $seeds;
    }

    /**
     * Get application.
     *
     * @return object
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Set application.
     *
     * @param \Silex\Application $app
     *
     * @return \sonrac\FCoverage\OnceRunMigration
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setApp($app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * @param array $seeds
     *
     * @return OnceRunMigration
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setSeeds($seeds)
    {
        static::$seeds = $seeds;

        return $this;
    }
}
