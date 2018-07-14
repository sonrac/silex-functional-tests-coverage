<?php
/**
 * @author Donii Sergii <doniysa@gmail.com>.
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
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::initInitMigrationAppTrait();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        static::downMigrationTrait();
    }
}
