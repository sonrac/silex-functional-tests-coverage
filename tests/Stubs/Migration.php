<?php

namespace sonrac\FCoverage\Tests\Stubs;

use sonrac\FCoverage\BootTraits;
use sonrac\FCoverage\MigrationsTrait;

/**
 * Class Migration.
 */
class Migration
{
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
        $this->boot();
    }

    protected function bootTrait()
    {
        $this->setBinDir('/bin')
            ->setPhpExecutor('')
            ->setConsoleCommand('echo')
            ->setMigrationCommand('123 > /tmp/1.txt')
            ->setRollbackMigrationCommand(' > /tmp/2.txt');
    }

    /**
     * {@inheritdoc}
     */
    protected function getMigrationsList()
    {
    }
}
