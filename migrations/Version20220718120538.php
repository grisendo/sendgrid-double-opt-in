<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220718120538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create initial tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE contacts (id CHAR(36) NOT NULL, list_id CHAR(36) NOT NULL, name VARCHAR(50) DEFAULT NULL, surname VARCHAR(50) DEFAULT NULL, confirmed TINYINT(1) NOT NULL, token VARCHAR(64) NOT NULL, email VARCHAR(254) NOT NULL, INDEX idx_contacts_list (list_id), INDEX idx_contacts_email (email), UNIQUE INDEX uk_contacts_list_email (list_id, email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE lists (id CHAR(36) NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contacts');
        $this->addSql('DROP TABLE lists');
    }
}
