<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage;

use PHPUnit\Framework\TestCase;

/**
 * Class OnceMigrationUnitTest.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
abstract class OnceMigrationUnitTest extends TestCase
{
    use InitMigrationAppTrait;

    /**
     * Seeds list.
     *
     * @var array
     *
     * @author Donii Sergii <doniysa@gmail.com>
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
        static::initInitMigrationAppTrait();

        parent::setUpBeforeClass();
    }

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
