<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage\Tests;

use PHPUnit\Framework\TestCase;
use sonrac\FCoverage\OnceMigrationUnitTest;

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
     * {@inheritdoc}
     */
    public static function getApplication()
    {
        // TODO: Implement getApplication() method.
    }
}
