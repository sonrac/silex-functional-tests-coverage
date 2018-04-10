<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage\Tests;

/**
 * Class RunRealApplicationWithOneRunMigration.
 *
 * @author Donii Sergii <s.donii@infomir.com>
 */
class RunRealApplicationWithOneRunMigration extends RealApplicationUnitTest
{
    /**
     * {@inheritdoc}
     */
    protected static $runMigration = false;

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
