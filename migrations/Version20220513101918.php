<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220513101918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add log table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(file_get_contents(__DIR__ . '/sql/add_log_table.sql'));
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `log`');
    }
}
