<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage\Tests\app;

use Silex\Application;

/**
 * Class DoctrineServiceProvider.
 *
 * @author Donii Sergii <s.donii@infomir.com>
 */
class DoctrineServiceProvider
{
    public function register(Application $app)
    {
        $app['migrations.namespace'] = 'DoctrineMigrations';
        $app['migrations.path'] = null;
        $app['migrations.table_name'] = null;
        $app['migrations.name'] = null;
    }
}
