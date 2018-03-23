<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage;

use PHPUnit\Framework\TestCase;

/**
 * Class OnceMigrationUnitTest
 *
 * @author Donii Sergii <s.donii@infomir.com>
 */
abstract class OnceMigrationUnitTest extends TestCase
{
    /**
     * Migration runner class.
     *
     * @var \sonrac\FCoverage\OnceRunMigration
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    protected static $migration;

    /**
     * Run migration if true or does not run otherwise.
     *
     * @var bool
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    protected static $runMigration = true;

    /**
     * Seeds list.
     *
     * @var array
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    protected static $seeds = [];

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public static function setUpBeforeClass(
    )/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUpBeforeClass();

        if (true === static::$runMigration) {
            $app = require __DIR__.'/../../bootstrap/api.php';
            static::$migration = new OnceRunMigration($app, static::$seeds);
            static::$migration->bootMigrationsTrait();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass(
    )/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::tearDownAfterClass();

        if (true === static::$runMigration) {
            static::$migration->rollback();
        }
    }
}