<?php

declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230824192449 extends AbstractMigration
{

    const TABLE = 'reviews';

    const COLUMN = 'whereStay';

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $query = 'ALTER TABLE %s ADD %s TEXT DEFAULT NULL';
        $this->addSql(sprintf($query, self::TABLE, self::COLUMN));
    }

    public function down(Schema $schema): void
    {
        $query = 'ALTER TABLE %s DROP %s';
        $this->addSql(sprintf($query, self::TABLE, self::COLUMN));

    }
}
