<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402120217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facility ADD house_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facility ADD CONSTRAINT FK_105994B26BB74515 FOREIGN KEY (house_id) REFERENCES house (id)');
        $this->addSql('CREATE INDEX IDX_105994B26BB74515 ON facility (house_id)');
        $this->addSql('ALTER TABLE house ADD actif TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE rent ADD reservation TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facility DROP FOREIGN KEY FK_105994B26BB74515');
        $this->addSql('DROP INDEX IDX_105994B26BB74515 ON facility');
        $this->addSql('ALTER TABLE facility DROP house_id');
        $this->addSql('ALTER TABLE house DROP actif');
        $this->addSql('ALTER TABLE rent DROP reservation');
    }
}
