<?php
/**
 * @author Donii Sergii <doniysa@gmail.com>.
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

    /**
     * {@inheritdoc}
     */
    public function createApplication()
    {
        $class = $this->getAppClass();

        return $class::getInstance()
            ->getApplication();
    }
}
