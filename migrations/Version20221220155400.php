<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220155400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE receipt (id INT AUTO_INCREMENT NOT NULL, userid_id INT DEFAULT NULL, timestamp DATETIME NOT NULL, totalprice DOUBLE PRECISION NOT NULL, INDEX IDX_5399B64558E0A285 (userid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B64558E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recipt DROP FOREIGN KEY FK_B03891C558E0A285');
        $this->addSql('DROP TABLE recipt');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipt (id INT AUTO_INCREMENT NOT NULL, userid_id INT DEFAULT NULL, timestamp DATETIME NOT NULL, totalprice DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_B03891C558E0A285 (userid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE recipt ADD CONSTRAINT FK_B03891C558E0A285 FOREIGN KEY (userid_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE receipt DROP FOREIGN KEY FK_5399B64558E0A285');
        $this->addSql('DROP TABLE receipt');
    }
}
