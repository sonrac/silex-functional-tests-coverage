<?php
/**
 * @author Donii Sergii <doniysa@gmail.com>.
 */

namespace sonrac\FCoverage;

/**
 * Class TestApplication.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
abstract class TestApplication
{
    /**
     * Application singleton instance.
     *
     * @var null|\sonrac\FCoverage\TestApplication
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected static $instance;
    /**
     * Application instance.
     *
     * @var \Silex\Application
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $app;
    /**
     * Migration instance.
     *
     * @var \sonrac\FCoverage\OnceRunMigration
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $migration;
    /**
     * Allow/deny run migration.
     *
     * @var bool
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $runMigration = true;

    /**
     * TestApplication constructor.
     *
     * @param array                                   $seeds     Seeds list.
     * @param null|\sonrac\FCoverage\OnceRunMigration $migration Migration instance
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function __construct($seeds = [], $migration = null)
    {
        $this->migration = $migration ?: new OnceRunMigration($this->createApplication(), $seeds);
        $this->setUpMigration();

        $this->app = $this->createApplication();
        $this->getApplication()->boot();
    }

    /**
     * Get application instance.
     *
     * @return string|\Silex\Application
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    abstract public function createApplication();

    /**
     * Setup migrations.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    abstract public function setUpMigration();

    /**
     * Get silex application.
     *
     * @return \Silex\Application
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getApplication()
    {
        return $this->app;
    }

    /**
     * Set application instance.
     *
     * @param string $app
     *
     * @return TestApplication
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setApplication($app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * @param array                                   $seeds
     * @param null|\sonrac\FCoverage\OnceRunMigration $migration
     *
     * @return \sonrac\FCoverage\TestApplication
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public static function getInstance($seeds = [], $migration = null)
    {
        return static::$instance ?: static::$instance = new static($seeds, $migration);
    }

    /**
     * Get new application instance.
     *
     * @param array $seeds
     * @param null  $migration
     *
     * @return \sonrac\FCoverage\TestApplication
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public static function newInstance($seeds = [], $migration = null)
    {
        return static::$instance = new static($seeds, $migration);
    }

    /**
     * Get migration instance.
     *
     * @return \sonrac\FCoverage\OnceRunMigration
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getMigration()
    {
        return $this->migration;
    }

    /**
     * Set migration instance.
     *
     * @param \sonrac\FCoverage\OnceRunMigration $migration
     *
     * @return TestApplication
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setMigration($migration)
    {
        $this->migration = $migration;

        return $this;
    }

    /**
     * Set application instance.
     *
     * @param \sonrac\FCoverage\TestApplication $app
     *
     * @return \sonrac\FCoverage\TestApplication
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setInstance(self $app)
    {
        return static::$instance = $app;
    }

    /**
     * Setup migrations.
     *
     * @throws \Exception
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function runMigration()
    {
        $this->migration->bootMigrationsTrait();
    }

    /**
     * Rollback migrations.
     *
     * @throws \Exception
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function rollbackMigrations()
    {
        if ($this->isRunMigration()) {
            $this->migration->rollback();
        }
    }

    /**
     * Check allow run migration.
     *
     * @return bool
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function isRunMigration()
    {
        return $this->runMigration;
    }

    /**
     * Set allow/deny run migration.
     *
     * @param bool $runMigration
     *
     * @return TestApplication
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setRunMigration($runMigration)
    {
        $this->runMigration = $runMigration;

        return $this;
    }
}
