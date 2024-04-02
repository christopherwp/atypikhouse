<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402081141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399D7E3C61F97E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_67D5399D7E3C61F9 ON house (owner_id)');
        $this->addSql('ALTER TABLE rent ADD reservation TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD is_active TINYINT(1) NOT NULL, ADD type VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE house DROP FOREIGN KEY FK_67D5399D7E3C61F97E3C61F9');
        $this->addSql('DROP INDEX IDX_67D5399D7E3C61F9 ON house');
        $this->addSql('ALTER TABLE rent DROP reservation');
        $this->addSql('ALTER TABLE user DROP is_active, DROP type');
    }
}
