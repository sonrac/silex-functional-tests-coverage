<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage;

trait InitMigrationAppTrait
{
    /**
     * Run migration if true or does not run otherwise.
     *
     * @var bool
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected static $runMigration = true;

    /**
     * Migration runner class.
     *
     * @var \sonrac\FCoverage\OnceRunMigration
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected static $migration;

    /**
     * Seeds list.
     *
     * @var array
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected static $staticSeeds = [];

    /**
     * Setup migration class.
     *
     * @return void
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public static function setUpMigration()
    {
    }

    /**
     * Get application instance.
     *
     * @return \Silex\Application
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    abstract public function getApplication();

    /**
     * Init migration trait.
     *
     * @throws \Exception
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected static function initInitMigrationAppTrait()
    {
        if (true === static::$runMigration) {
            $app = (new static())->getApplication();
            $app->boot();
            static::$migration = new OnceRunMigration($app, static::$staticSeeds);
            static::setUpMigration();
            static::$migration->bootMigrationsTrait();
        }
    }

    /**
     * Rollback migrations.
     *
     * @throws \Exception
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected static function downMigrationTrait()
    {
        if (true === static::$runMigration && static::$migration) {
            static::$migration->rollback();
        }
    }
}
