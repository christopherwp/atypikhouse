<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305155335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rent DROP FOREIGN KEY FK_2784DCC9D86650F');
        $this->addSql('ALTER TABLE rent DROP FOREIGN KEY FK_2784DCCA4A739AF');
        $this->addSql('DROP INDEX IDX_2784DCCA4A739AF ON rent');
        $this->addSql('DROP INDEX IDX_2784DCC9D86650F ON rent');
        $this->addSql('ALTER TABLE rent ADD house_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, DROP house_id_id, DROP user_id_id');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCC6BB74515 FOREIGN KEY (house_id) REFERENCES house (id)');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2784DCC6BB74515 ON rent (house_id)');
        $this->addSql('CREATE INDEX IDX_2784DCCA76ED395 ON rent (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rent DROP FOREIGN KEY FK_2784DCC6BB74515');
        $this->addSql('ALTER TABLE rent DROP FOREIGN KEY FK_2784DCCA76ED395');
        $this->addSql('DROP INDEX IDX_2784DCC6BB74515 ON rent');
        $this->addSql('DROP INDEX IDX_2784DCCA76ED395 ON rent');
        $this->addSql('ALTER TABLE rent ADD house_id_id INT DEFAULT NULL, ADD user_id_id INT DEFAULT NULL, DROP house_id, DROP user_id');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCC9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCCA4A739AF FOREIGN KEY (house_id_id) REFERENCES house (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2784DCCA4A739AF ON rent (house_id_id)');
        $this->addSql('CREATE INDEX IDX_2784DCC9D86650F ON rent (user_id_id)');
    }
}
