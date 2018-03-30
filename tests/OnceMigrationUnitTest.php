<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage\Tests;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use sonrac\FCoverage\OnceMigrationUnitTest as BaseOnceMigrationUnitTest;

/**
 * Class OnceMigrationUnitTest
 * Once migration controller tester.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class OnceMigrationUnitTest extends TestCase
{
    /**
     * Database file path.
     *
     * @var string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $dbFile = 'out/db.sqlite';

    /**
     * {@inheritdoc}
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        file_put_contents(__DIR__.'/'.$this->dbFile, '');
    }

    /**
     * Test run migrations.
     *
     * @param string|null $class
     *
     * @throws \Exception
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    public function testRunMigrations($class = null)
    {
        $class = $class ?: OnceMigrationTests::class;
        $class::setUpBeforeClass();

        $app = OnceMigrationTests::getMigration()->getApp();
        /** @var \Doctrine\DBAL\Connection $db */
        $db = $app['db'];

        static::assertTrue((bool) $this->getTable($db));

        $class::tearDownAfterClass();

        static::assertFalse((bool) $this->getTable($db));
    }

    /**
     * Get table from sqlite schema.
     *
     * @param \Doctrine\DBAL\Connection $db
     *
     * @return bool
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    private function getTable(Connection $db)
    {
        try {
            return (bool) $db->createQueryBuilder()
                ->select('name')
                ->from('sqlite_master')
                ->where('type = :type AND name = :table_name')
                ->setParameter('type', 'table')
                ->setParameter('table_name', 'users')
                ->execute()
                ->fetchColumn();
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::tearDown();
        if (file_exists(__DIR__.'/'.$this->dbFile)) {
            unlink(__DIR__.'/'.$this->dbFile);
        }
    }
}

class OnceMigrationTests extends BaseOnceMigrationUnitTest
{
    public static $appPath = 'app/app.php';

    /**
     * {@inheritdoc}
     */
    public static function getApplication($dir = __DIR__)
    {
        return parent::getApplication($dir);
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpMigration()
    {
        static::$migration->setBinDir(__DIR__.'/app/bin');
    }

    /**
     * Get migration object.
     *
     * @return \sonrac\FCoverage\OnceRunMigration
     *
     * @author Donii Sergii <s.donii@infomir.com>
     */
    public static function getMigration()
    {
        return static::$migration;
    }
}
