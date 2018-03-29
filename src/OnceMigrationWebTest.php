<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage;

use Silex\WebTestCase;

/**
 * Class OnceMigrationWebTest.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
abstract class OnceMigrationWebTest extends WebTestCase
{
    use InitMigrationAppTrait;

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
    protected static $staticSeeds = [];

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public static function setUpBeforeClass(
    )/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUpBeforeClass();

        static::initInitMigrationAppTrait();
    }

    /**
     * Get application instance.
     *
     * @return \Silex\Application
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    abstract public static function getApplication();


    /**
     * {@inheritdoc}
     */
    public function createApplication()
    {
        if (method_exists(static::class, 'getApplication')) {
            return static::getApplication();
        }
    }

    /**
     * Setup migration class.
     *
     * @return void
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    abstract public static function setUpMigration();

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass(
    )/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::tearDownAfterClass();

        static::downMigrationTrait();
    }
}
