<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */
$file = __DIR__.'/out/db.sqlite';

if (is_file($file)) {
    unlink($file);
}

require __DIR__.'/../vendor/autoload.php';

/**
 * Class TApp
 * Test application.
 *
 * @author Donii Sergii <s.donii@infomir.com>
 */
class TApp extends \sonrac\FCoverage\TestApplication
{
    /**
     * {@inheritdoc}
     */
    public function createApplication()
    {
        return require __DIR__.'/app/app.php';
    }

    /**
     * {@inheritdoc}
     */
    public function setUpMigration()
    {
        $this->getMigration()
            ->setBinDir(__DIR__.'/app/bin');
    }
}

class TAppOnce extends TApp
{
    protected $isRegistered = false;

    /**
     * {@inheritdoc}
     */
    public function createApplication()
    {
        if (!$this->isRegistered) {
            $this->isRegistered = true;
            register_shutdown_function(function () {
                $app = require __DIR__.'/app/app.php';
                $migration = new \sonrac\FCoverage\OnceRunMigration($app);
                $migration->setBinDir(__DIR__.'/app/bin')
                    ->setSeedClassEnding('Seeds')
                    ->setSeedNamespace('sonrac\\\\FCoverage\\\\Tests\\\\Seeds\\\\');
                $migration->rollback();

                $exists = false;

                try {
                    $driver = \Doctrine\DBAL\DriverManager::getConnection([
                        'url' => 'sqlite:'.__DIR__.'/out/db.sqlite',
                    ]);
                    if (true === $driver->getSchemaManager()->tablesExist('users')) {
                        $exists = true;
                    }
                } catch (\Exception $e) {
                }

                if ($exists) {
                    throw new \Exception('Rollback not working');
                }
            });

            $migration = new \sonrac\FCoverage\OnceRunMigration(require __DIR__.'/app/app.php');
            $migration->setSeedClassEnding('Seeds')
                ->setSeedNamespace('sonrac\\\\FCoverage\\\\Tests\\\\Seeds\\\\');
            $this->setMigration($migration);
            $this->setUpMigration();
            $this->runMigration();
        }

        return require __DIR__.'/app/app.php';
    }

    public static function newInstance($seeds = [], $migration = null)
    {
        return parent::newInstance($seeds, $migration);
    }
}
