<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220034009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE2527126F525E');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25271AD5CDBF');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('ALTER TABLE item ADD cartid_id INT DEFAULT NULL, CHANGE productid_id productid_id INT NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ECD84EE75 FOREIGN KEY (cartid_id) REFERENCES cart (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251ECD84EE75 ON item (cartid_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_item (cart_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_F0FE2527126F525E (item_id), INDEX IDX_F0FE25271AD5CDBF (cart_id), PRIMARY KEY(cart_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25271AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251ECD84EE75');
        $this->addSql('DROP INDEX IDX_1F1B251ECD84EE75 ON item');
        $this->addSql('ALTER TABLE item DROP cartid_id, CHANGE productid_id productid_id INT DEFAULT NULL');
    }
}
