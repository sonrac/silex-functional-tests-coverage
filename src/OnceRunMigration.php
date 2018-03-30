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
     * Application instance.
     *
     * @var object
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected $app;

    /**
     * Seeds list.
     *
     * @var array
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected $seeds = [];

    /**
     * OnceRunMigration constructor.
     *
     * @param object $application Application instance
     * @param array  $seeds       Seeds list
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function __construct($application, $seeds = [])
    {
        $this->app = $application;
        $this->seeds = $seeds;
    }

    /**
     * Get application.
     *
     * @return \Silex\Application
     *
     * @author Donii Sergii <s.donii@infomir.com>
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
     * @author Donii Sergii <s.donii@infomir.com>
     */
    public function setApp($app)
    {
        $this->app = $app;
        return $this;
    }

    /**
     * Get seeds.
     *
     * @return array
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    public function getSeeds()
    {
        return $this->seeds;
    }

    /**
     * @param array $seeds
     *
     * @return OnceRunMigration
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    public function setSeeds($seeds)
    {
        $this->seeds = $seeds;
        return $this;
    }
}
