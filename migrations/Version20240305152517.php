<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305152517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rent (id INT AUTO_INCREMENT NOT NULL, house_id_id INT DEFAULT NULL, user_id_id INT DEFAULT NULL, start_date DATE NOT NULL, num_days INT NOT NULL, total_price INT NOT NULL, INDEX IDX_2784DCCA4A739AF (house_id_id), INDEX IDX_2784DCC9D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCCA4A739AF FOREIGN KEY (house_id_id) REFERENCES house (id)');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCC9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rent DROP FOREIGN KEY FK_2784DCCA4A739AF');
        $this->addSql('ALTER TABLE rent DROP FOREIGN KEY FK_2784DCC9D86650F');
        $this->addSql('DROP TABLE rent');
    }
}
