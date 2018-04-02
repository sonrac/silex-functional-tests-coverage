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
     * Seeds list.
     *
     * @var array
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected static $seeds = [];

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
            $instance = new static();
            $class = $instance->getAppClass();
            $class::newInstance()
                ->setRunMigration(static::$runMigration)
                ->getMigration()
                ->setSeeds($instance::$seeds);
            static::prepareMigrations();
            $class::getInstance()
                ->runMigration();
        }
    }

    /**
     * Get test application instance.
     *
     * @return \sonrac\FCoverage\TestApplication
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    abstract public function getAppClass();

    /**
     * Prepare migrations before run.
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    public static function prepareMigrations()
    {
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
        if (true === static::$runMigration) {
            $class = (new static())->getAppClass();
            $class::getInstance()->rollbackMigrations();
        }
    }
}
