<?php
/**
 * @author Donii Sergii <doniysa@gmail.com>.
 */

namespace sonrac\FCoverage\Tests;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Silex\Application;
use sonrac\FCoverage\Tests\Stubs\OnceMigrationWeb;

/**
 * Class OnceMigrationWebTest
 * Once migration controller tester.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class OnceMigrationWebTest extends TestCase
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
     * Test run migrations.
     *
     * @throws \Exception
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testRunMigrations()
    {
        OnceMigrationWeb::setUpBeforeClass();

        $app = OnceMigrationWeb::getMigration()->getApp();
        /** @var \Doctrine\DBAL\Connection $db */
        $db = $app['db'];

        static::assertTrue((bool) $this->getTable($db));

        OnceMigrationWeb::tearDownAfterClass();

        static::assertFalse((bool) $this->getTable($db));

        $this->assertInstanceOf(Application::class, (new OnceMigrationWeb())->createApplication());
    }

    /**
     * Get table from sqlite schema.
     *
     * @param \Doctrine\DBAL\Connection $db
     *
     * @return bool
     *
     * @author Donii Sergii <doniysa@gmail.com>
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
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        file_put_contents(__DIR__.'/'.$this->dbFile, '');
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
