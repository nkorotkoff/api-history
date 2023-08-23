<?php

declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230723191642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create reviews table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('reviews');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('rating', 'integer', ['notnull' => true]);
        $table->addColumn('type', 'string', ['length' => 50, 'notnull' => true]);
        $table->addColumn('user_id', 'integer', ['notnull' => true]);
        $table->addColumn('created_at', 'integer', ['notnull' => true]);
        $table->addColumn('review', 'string', ['length' => 255, 'notnull' => false]);
        $table->addColumn('author', 'string', ['length' => 255, 'notnull' => false]);;
        $table->setPrimaryKey(['id']);
        $table->addForeignKeyConstraint('users', ['user_id'], ['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('reviews');
    }
}
