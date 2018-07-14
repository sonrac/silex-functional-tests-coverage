<?php
/**
 * @author Donii Sergii <doniysa@gmail.com>.
 */

namespace sonrac\FCoverage\Tests;

/**
 * Class RunRealApplicationWithOneRunMigration.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class RunRealApplicationWithOneRunMigrationTest extends RealApplicationUnitTest
{
    /**
     * {@inheritdoc}
     */
    protected static $runMigration = false;

    /**
     * {@inheritdoc}
     */
    protected static $seeds = ['users'];

    /**
     * {@inheritdoc}
     */
    public static function prepareMigrations()
    {
        $class = (new static())->getAppClass();
        $class::getInstance()
            ->getMigration()
            ->setSeedClassEnding('Seeds')
            ->setSeedNamespace('sonrac\\\\FCoverage\\\\Tests\\\\Seeds\\\\');
    }

    /**
     * {@inheritdoc}
     */
    public function getAppClass()
    {
        return \TAppOnce::class;
    }
}
