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
 * Test application
 *
 * @author Donii Sergii <s.donii@infomir.com>
 */
class TApp extends \sonrac\FCoverage\TestApplication {
    /**
     * @inheritDoc
     */
    public function createApplication()
    {
        return require __DIR__.'/app/app.php';
    }

    /**
     * @inheritDoc
     */
    public function setUpMigration()
    {
        $this->getMigration()
            ->setBinDir(__DIR__.'/app/bin');
    }
}
