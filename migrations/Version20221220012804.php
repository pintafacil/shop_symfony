<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220012804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_cart (item_id INT NOT NULL, cart_id INT NOT NULL, INDEX IDX_C393CC4C126F525E (item_id), INDEX IDX_C393CC4C1AD5CDBF (cart_id), PRIMARY KEY(item_id, cart_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_cart ADD CONSTRAINT FK_C393CC4C126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_cart ADD CONSTRAINT FK_C393CC4C1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251ECD84EE75');
        $this->addSql('DROP INDEX IDX_1F1B251ECD84EE75 ON item');
        $this->addSql('ALTER TABLE item DROP cartid_id, CHANGE productid_id productid_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_cart DROP FOREIGN KEY FK_C393CC4C126F525E');
        $this->addSql('ALTER TABLE item_cart DROP FOREIGN KEY FK_C393CC4C1AD5CDBF');
        $this->addSql('DROP TABLE item_cart');
        $this->addSql('ALTER TABLE item ADD cartid_id INT NOT NULL, CHANGE productid_id productid_id INT NOT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251ECD84EE75 FOREIGN KEY (cartid_id) REFERENCES cart (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1F1B251ECD84EE75 ON item (cartid_id)');
    }
}
