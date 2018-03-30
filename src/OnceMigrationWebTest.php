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
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public static function setUpMigration() {}

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
