<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221219220455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, timestamp DATETIME NOT NULL, totalprice DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, productid_id INT NOT NULL, cartid_id INT NOT NULL, quantity INT NOT NULL, UNIQUE INDEX UNIQ_1F1B251EAF89CCED (productid_id), INDEX IDX_1F1B251ECD84EE75 (cartid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipt (id INT AUTO_INCREMENT NOT NULL, userid_id INT DEFAULT NULL, timestamp DATETIME NOT NULL, totalprice DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_B03891C558E0A285 (userid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EAF89CCED FOREIGN KEY (productid_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ECD84EE75 FOREIGN KEY (cartid_id) REFERENCES cart (id)');
        $this->addSql('ALTER TABLE recipt ADD CONSTRAINT FK_B03891C558E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE price CHANGE value value DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE user ADD cartid_id INT DEFAULT NULL, ADD address VARCHAR(255) NOT NULL, ADD nif INT NOT NULL, ADD phone INT NOT NULL, ADD firstname VARCHAR(255) NOT NULL, ADD lastname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CD84EE75 FOREIGN KEY (cartid_id) REFERENCES cart (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649CD84EE75 ON user (cartid_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CD84EE75');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EAF89CCED');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251ECD84EE75');
        $this->addSql('ALTER TABLE recipt DROP FOREIGN KEY FK_B03891C558E0A285');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE recipt');
        $this->addSql('ALTER TABLE price CHANGE value value INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D649CD84EE75 ON user');
        $this->addSql('ALTER TABLE user DROP cartid_id, DROP address, DROP nif, DROP phone, DROP firstname, DROP lastname');
    }
}
