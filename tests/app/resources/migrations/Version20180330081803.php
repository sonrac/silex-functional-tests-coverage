<?php

declare(strict_types=1);

namespace TestMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180330081803 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $user = $schema->createTable('users');

        $user->addColumn('id', Type::INTEGER)
            ->setAutoincrement(true);

        $user->addColumn('username', Type::STRING)
            ->setLength(255)
            ->setDefault('')
            ->setNotnull(true);

        $user->addColumn('password', Type::STRING)
            ->setLength(2000)
            ->setDefault('')
            ->setNotnull(true);
    }

    public function down(Schema $schema)
    {
        $schema->dropTable('users');
    }
}
