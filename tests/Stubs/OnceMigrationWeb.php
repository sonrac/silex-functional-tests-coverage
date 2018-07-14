<?php

namespace sonrac\FCoverage\Tests\Stubs;

use sonrac\FCoverage\OnceMigrationWebTest as BaseOnceMigrationWebTest;

/**
 * Class OnceMigrationWeb.
 */
class OnceMigrationWeb extends BaseOnceMigrationWebTest
{
    /**
     * {@inheritdoc}
     */
    protected static $runMigration = true;

    /**
     * Get migration object.
     *
     * @return \sonrac\FCoverage\OnceRunMigration
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public static function getMigration()
    {
        $class = (new static())->getAppClass();

        return $class::getInstance()
            ->getMigration();
    }

    /**
     * {@inheritdoc}
     */
    public function getAppClass()
    {
        return \TApp::class;
    }
}
