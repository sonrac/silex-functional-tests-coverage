<?php

namespace sonrac\FCoverage\Tests\Stubs;

use sonrac\FCoverage\BootTraits;
use sonrac\FCoverage\MigrationsTrait;

/**
 * Class MigrationWithSeeds.
 */
class MigrationWithSeeds extends Migration
{
    use MigrationsTrait, BootTraits;

    protected static $seeds = [1, 2, 3];

    /**
     * MigrationWithSeeds constructor.
     *
     * @throws \ReflectionException
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function __construct()
    {
        $this->bootTrait();

        $this->setBinDir(__DIR__.'/..')
            ->setSeedCommand('123 > ');

        $this->boot();
    }

    protected function getMigrationsList()
    {
        return [
            '1',
            '2',
            '3',
        ];
    }
}
