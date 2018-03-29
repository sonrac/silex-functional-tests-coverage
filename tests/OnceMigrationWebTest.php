<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage\Tests;

use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use sonrac\FCoverage\BaseControllerTest;
use sonrac\FCoverage\MaxRedirectException;
use sonrac\FCoverage\OnceMigrationUnitTest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

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
     * Test controller instance.
     *
     * @var \Silex\Tests\ControllerTest
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $controller;
}

class OnceMigrationTests extends OnceMigrationUnitTest
{
    /**
     * @inheritDoc
     */
    public static function getApplication()
    {
        // TODO: Implement getApplication() method.
    }

}
