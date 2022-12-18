<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221218013904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D9F920BBA2');
        $this->addSql('DROP INDEX IDX_CAC822D9F920BBA2 ON price');
        $this->addSql('ALTER TABLE price ADD value INT NOT NULL, CHANGE value_id productid_id INT NOT NULL');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D9AF89CCED FOREIGN KEY (productid_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_CAC822D9AF89CCED ON price (productid_id)');
        $this->addSql('ALTER TABLE product DROP idprod, CHANGE description description VARCHAR(255) NOT NULL, CHANGE imagepath imagepath VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D9AF89CCED');
        $this->addSql('DROP INDEX IDX_CAC822D9AF89CCED ON price');
        $this->addSql('ALTER TABLE price ADD value_id INT NOT NULL, DROP productid_id, DROP value');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D9F920BBA2 FOREIGN KEY (value_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_CAC822D9F920BBA2 ON price (value_id)');
        $this->addSql('ALTER TABLE product ADD idprod SMALLINT DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE imagepath imagepath VARCHAR(255) DEFAULT NULL');
    }
}
