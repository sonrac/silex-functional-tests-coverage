<?php

namespace sonrac\FCoverage;

/**
 * Trait MigrationsTrait
 * Migrations trait for auto execute migrations during test running.
 * Additionally running seed command. Seed command based on `sonrac/symfony-seed-command` package.
 *
 * Init property `$seed` in your test class for seed execute.
 * Property is list seed classes.
 *
 * @example
 * All seeds located by namespace `Tests\Seeds` and ending with TableSeeder
 * Seeds List are:
 * - Tests\Seeds\UsersTableSeeder
 * - Tests\Seeds\RolesTableSeeder
 *
 * In test adding property $seeds:
 * <code lang="php">
 * class ExampleTest extends TestCase {
 *      use BootTraits, MigrationsTrait;
 *      protected function setUp() {
 *          parent::setUp();
 *          $this->setSeedClassEnding('TableSeeder')->_boot();
 *      }
 *      protected $seeds = ['users', 'roles']
 * }
 * </code>
 *
 * All seeds running in inclusion order.
 */
trait MigrationsTrait
{
    /**
     * Path to console command dir.
     *
     * @var string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $binDir;

    /**
     * Php executor name.
     *
     * @var string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $phpExecutor = 'php';

    /**
     * Console command name.
     *
     * @var string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $consoleCommand = 'console';

    /**
     * Seed classes namespace.
     *
     * @var null|string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $seedNamespace;

    /**
     * Seed class ending name.
     *
     * @var string|null
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $seedClassEnding;

    /**
     * Migration table name.
     *
     * @var string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $migrationTable = 'migration_versions';

    /**
     * Migration command name.
     *
     * @var string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $migrationCommand = 'migrations:migrate --no-interaction';

    /**
     * Rollback migration command name.
     *
     * @var string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $rollbackMigrationCommand = 'migrations:execute --down --no-interaction';

    /**
     * Command which will be running before. By default is `'export APP_ENV=testing'`.
     *
     * @var string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $preCommand = 'export APP_ENV=testing';

    /**
     * If true will be continue on command execute failure status.
     *
     * @var bool
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $continueOnFailure = false;

    /**
     * Seed command. By default is `seed:run --class=` from package `sonrac/symfony-seed-command`.
     *
     * @var string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $seedCommand = 'seed:run --class=';

    /**
     * Run migrations.
     *
     * @throws \Exception
     */
    public function bootMigrationsTrait()
    {
        $this->runCommand($this->getMigrationCommand());

        if (property_exists($this, 'seeds')) {
            foreach ($this->seeds as $seed) {
                $class = class_exists($seed) ? $seed : $this->getSeedNamespace().ucfirst($seed).
                                                       ($this->seedClassEnding ?: '');
                $this->runCommand($this->getSeedCommand().$class);
            }
        }
    }

    /**
     * Rollback all migrations.
     *
     * @throws \Exception
     *
     * @return null|false
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function rollback()
    {
        $migrations = $this->getMigrationsList();

        if (empty($migrations)) {
            return false;
        }

        foreach ($migrations as $migration) {
            $this->runCommand("{$this->getRollbackMigrationCommand()} {$migration}");
        }
    }

    /**
     * Get console command directory.
     *
     * @return string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getBinDir()
    {
        return $this->binDir;
    }

    /**
     * Set console command directory.
     *
     * @param string $binDir
     *
     * @return $this
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setBinDir($binDir)
    {
        $this->binDir = $binDir;

        return $this;
    }

    /**
     * Get php executor.
     *
     * @return string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getPhpExecutor()
    {
        return $this->phpExecutor;
    }

    /**
     * Set php executor.
     *
     * @param string $phpExecutor
     *
     * @return $this
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setPhpExecutor($phpExecutor)
    {
        $this->phpExecutor = $phpExecutor;

        return $this;
    }

    /**
     * Get console command name. By default is `console`.
     *
     * @return string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getConsoleCommand()
    {
        return $this->consoleCommand;
    }

    /**
     * Set console command name. By default is `console`.
     *
     * @param string $consoleCommand
     *
     * @return MigrationsTrait
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setConsoleCommand($consoleCommand)
    {
        $this->consoleCommand = $consoleCommand;

        return $this;
    }

    /**
     * Get seeds classes namespace.
     *
     * @return null|string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getSeedNamespace()
    {
        return $this->seedNamespace;
    }

    /**
     * Set seed classes namespace.
     *
     * @param null|string $seedNamespace
     *
     * @return MigrationsTrait
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setSeedNamespace($seedNamespace)
    {
        $this->seedNamespace = $seedNamespace;

        return $this;
    }

    /**
     * Get seed class repeat ending name (will be concatenated with origin name).
     *
     * @return null|string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getSeedClassEnding()
    {
        return $this->seedClassEnding;
    }

    /**
     * Set seed class repeat ending name (will be concatenated with origin name).
     *
     * @param null|string $seedClassEnding
     *
     * @return MigrationsTrait
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setSeedClassEnding($seedClassEnding)
    {
        $this->seedClassEnding = $seedClassEnding;

        return $this;
    }

    /**
     * Get migration table. By default is `migration_versions`.
     *
     * @return string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getMigrationTable()
    {
        return $this->migrationTable;
    }

    /**
     * Set migration table. By default is `migration_versions`.
     *
     * @param string $migrationTable
     *
     * @return MigrationsTrait
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setMigrationTable($migrationTable)
    {
        $this->migrationTable = $migrationTable;

        return $this;
    }

    /**
     * Get migration command name. By default is `migrations:migrate --no-interaction`.
     *
     * @return string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getMigrationCommand()
    {
        return $this->migrationCommand;
    }

    /**
     * Set migration command name. By default is `migrations:migrate --no-interaction`.
     *
     * @param string $migrationCommand
     *
     * @return MigrationsTrait
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setMigrationCommand($migrationCommand)
    {
        $this->migrationCommand = $migrationCommand;

        return $this;
    }

    /**
     * Get rollback migration command name. By default is `migrations:execute --down --no-interaction`.
     *
     * @return string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getRollbackMigrationCommand()
    {
        return $this->rollbackMigrationCommand;
    }

    /**
     * Set rollback migration command name. By default is `migrations:execute --down --no-interaction`.
     *
     * @param string $rollbackMigrationCommand
     *
     * @return MigrationsTrait
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setRollbackMigrationCommand($rollbackMigrationCommand)
    {
        $this->rollbackMigrationCommand = $rollbackMigrationCommand;

        return $this;
    }

    /**
     * Get command which will be running before. By default is `'export APP_ENV=testing'`.
     *
     * @return string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getPreCommand()
    {
        return $this->preCommand;
    }

    /**
     * Set command which will be running before. By default is `'export APP_ENV=testing'`.
     *
     * @param string $preCommand
     *
     * @return MigrationsTrait
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setPreCommand($preCommand)
    {
        $this->preCommand = $preCommand;

        return $this;
    }

    /**
     * Check flag continue on command execute failure status.
     *
     * @return bool
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function isContinueOnFailure()
    {
        return $this->continueOnFailure;
    }

    /**
     * Set flag continue on command execute failure status.
     *
     * @param bool $continueOnFailure
     *
     * @return MigrationsTrait
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setContinueOnFailure($continueOnFailure)
    {
        $this->continueOnFailure = $continueOnFailure;

        return $this;
    }

    /**
     * Get seed command. By default is `seed:run --class=` from package `sonrac/symfony-seed-command`.
     *
     * @return string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getSeedCommand()
    {
        return $this->seedCommand;
    }

    /**
     * Set seed command. By default is `seed:run --class=` from package `sonrac/symfony-seed-command`.
     *
     * @param string $seedCommand
     *
     * @return MigrationsTrait
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setSeedCommand($seedCommand)
    {
        $this->seedCommand = $seedCommand;

        return $this;
    }

    /**
     * Fetch migrations list for rollback.
     *
     * @return bool|array
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function getMigrationsList()
    {
        /**
         * @var \Doctrine\DBAL\Query\QueryBuilder
         */
        $builder = $this->app['db']->createQueryBuilder();

        return $builder->select(['version'])
            ->from('migration_versions')
            ->orderBy('version', 'desc')
            ->execute()->fetchColumn();
    }

    /**
     * Run command.
     *
     * @param string $command
     *
     * @throws \Exception
     */
    private function runCommand($command)
    {
        $command = PHP_EOL.$this->getPreCommand().
                   "; cd {$this->getBinDir()}; {$this->getPhpExecutor()} {$this->getConsoleCommand()} {$command}".PHP_EOL;

        ob_start();
        exec($command, $out, $code);
        ob_end_clean();

        if ((int) $code !== 0 && !$this->continueOnFailure) {
            throw new \Exception(
                "Command \n {$command} \n run with code {$code} with out: \n ".
                implode(PHP_EOL, $out)
            );
        }
    }
}
