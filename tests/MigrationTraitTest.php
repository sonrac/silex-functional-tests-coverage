<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage\Tests;

use Phpunit\Framework\TestCase;
use sonrac\FCoverage\BootTraits;
use sonrac\FCoverage\MigrationsTrait;

/**
 * Class MigrationTraitTest
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class MigrationTraitTest extends TestCase
{
    public function testBoot() {
        $migration = new Migration();

        static::assertFileExists('/tmp/1.txt');
        static::assertEquals("123\n", file_get_contents('/tmp/1.txt'));

        unlink('/tmp/1.txt');

        return $migration;
    }

    /**
     * Test rollback migration.
     *
     * @throws \ReflectionException
     * @throws \Exception
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function testRollback() {
        $migration = new MigrationWithSeeds();
        $migration = $migration
            ->setRollbackMigrationCommand('123 > ')
            ->setBinDir(__DIR__);

        $migration->rollback();

        foreach ([1, 2, 3] as $item) {
            $this->assertFileExists(__DIR__.'/'.$item);
            $this->assertEquals("123\n", file_get_contents(__DIR__.'/'.$item));
            unlink(__DIR__.'/'.$item);
        }

        $migration = new Migration();
        $this->assertFalse($migration->rollback());

    }

    public function testBootWithSeeds() {
        $migration = new MigrationWithSeeds();

        foreach ([1,2,3] as $item) {
            $this->assertFileExists(__DIR__.'/'.$item);
            $this->assertEquals("123\n", file_get_contents(__DIR__.'/'.$item));
            unlink(__DIR__.'/'.$item);
        }
    }
}

class Migration {
    use MigrationsTrait, BootTraits;

    /**
     * MigrationTest constructor.
     *
     * @throws \ReflectionException
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function __construct()
    {
        $this->bootTrait();
        $this->_boot();
    }

    protected function bootTrait() {
        $this->setBinDir('/bin')
            ->setPhpExecutor('')
            ->setConsoleCommand('echo')
            ->setMigrationCommand('123 > /tmp/1.txt')
            ->setRollbackMigrationCommand(' > /tmp/2.txt')
        ;
    }

    /**
     * @inheritdoc
     */
    protected function getMigrationsList()
    {
        var_dump(123);
        return null;
    }
}

class MigrationWithSeeds extends Migration {

    use MigrationsTrait, BootTraits;

    protected $seeds = [1,2,3];

    /**
     * MigrationWithSeeds constructor.
     *
     * @throws \ReflectionException
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function __construct()
    {
        $this->bootTrait();

        $this->setBinDir(__DIR__)
            ->setSeedCommand('123 > ');

        $this->_boot();
    }

    protected function getMigrationsList()
    {
        return [
            '1', '2', '3'
        ];
    }
}