<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage;

trait InitMigrationAppTrait
{
    /**
     * Migration runner class.
     *
     * @var \sonrac\FCoverage\OnceRunMigration
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected static $migration;

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
     * Init migration trait.
     *
     * @throws \Exception
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected static function initInitMigrationAppTrait()
    {
        if (true === static::$runMigration) {
            $app = static::getApplication();
            static::$migration = new OnceRunMigration($app, static::$seeds);
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
