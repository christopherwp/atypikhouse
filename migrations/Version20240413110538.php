<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413110538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rent DROP FOREIGN KEY FK_2784DCCD8A38199');
        $this->addSql('DROP INDEX IDX_2784DCCD8A38199 ON rent');
        $this->addSql('ALTER TABLE rent DROP locataire_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rent ADD locataire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCCD8A38199 FOREIGN KEY (locataire_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2784DCCD8A38199 ON rent (locataire_id)');
    }
}
