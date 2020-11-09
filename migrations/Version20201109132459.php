<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201109132459 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prize (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, item_id INT DEFAULT NULL, money INT DEFAULT NULL, scores INT DEFAULT NULL, INDEX IDX_51C88BC1A76ED395 (user_id), UNIQUE INDEX UNIQ_51C88BC1126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prize ADD CONSTRAINT FK_51C88BC1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prize ADD CONSTRAINT FK_51C88BC1126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prize DROP FOREIGN KEY FK_51C88BC1126F525E');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE prize');
    }
}
