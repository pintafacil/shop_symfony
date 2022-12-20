<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220031625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_cart (cart_source INT NOT NULL, cart_target INT NOT NULL, INDEX IDX_E446888E510E1D2C (cart_source), INDEX IDX_E446888E48EB4DA3 (cart_target), PRIMARY KEY(cart_source, cart_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart_cart ADD CONSTRAINT FK_E446888E510E1D2C FOREIGN KEY (cart_source) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_cart ADD CONSTRAINT FK_E446888E48EB4DA3 FOREIGN KEY (cart_target) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE2527126F525E');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25271AD5CDBF');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('ALTER TABLE item DROP INDEX UNIQ_1F1B251EAF89CCED, ADD INDEX IDX_1F1B251EAF89CCED (productid_id)');
        $this->addSql('ALTER TABLE item CHANGE productid_id productid_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_item (cart_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_F0FE2527126F525E (item_id), INDEX IDX_F0FE25271AD5CDBF (cart_id), PRIMARY KEY(cart_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25271AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_cart DROP FOREIGN KEY FK_E446888E510E1D2C');
        $this->addSql('ALTER TABLE cart_cart DROP FOREIGN KEY FK_E446888E48EB4DA3');
        $this->addSql('DROP TABLE cart_cart');
        $this->addSql('ALTER TABLE item DROP INDEX IDX_1F1B251EAF89CCED, ADD UNIQUE INDEX UNIQ_1F1B251EAF89CCED (productid_id)');
        $this->addSql('ALTER TABLE item CHANGE productid_id productid_id INT DEFAULT NULL');
    }
}
