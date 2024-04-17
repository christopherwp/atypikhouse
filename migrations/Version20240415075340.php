<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240415075340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE house DROP FOREIGN KEY FK_67D5399D7E3C61F97E3C61F9');
        $this->addSql('DROP INDEX IDX_67D5399D7E3C61F9 ON house');
        $this->addSql('ALTER TABLE house CHANGE owner_id proprietaire_id INT NOT NULL');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399D76C50E4A76C50E4A FOREIGN KEY (proprietaire_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_67D5399D76C50E4A ON house (proprietaire_id)');
        $this->addSql('ALTER TABLE rent ADD proprietaire_id INT DEFAULT NULL, ADD transaction_id VARCHAR(36) DEFAULT NULL, DROP reservation');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCC76C50E4A FOREIGN KEY (proprietaire_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2784DCC76C50E4A ON rent (proprietaire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE house DROP FOREIGN KEY FK_67D5399D76C50E4A76C50E4A');
        $this->addSql('DROP INDEX IDX_67D5399D76C50E4A ON house');
        $this->addSql('ALTER TABLE house CHANGE proprietaire_id owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE house ADD CONSTRAINT FK_67D5399D7E3C61F97E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_67D5399D7E3C61F9 ON house (owner_id)');
        $this->addSql('ALTER TABLE rent DROP FOREIGN KEY FK_2784DCC76C50E4A');
        $this->addSql('DROP INDEX IDX_2784DCC76C50E4A ON rent');
        $this->addSql('ALTER TABLE rent ADD reservation TINYINT(1) DEFAULT NULL, DROP proprietaire_id, DROP transaction_id');
    }
}
