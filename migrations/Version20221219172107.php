<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221219172107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660F920BBA2');
        $this->addSql('DROP INDEX IDX_4B365660F920BBA2 ON stock');
        $this->addSql('ALTER TABLE stock ADD value INT NOT NULL, ADD status TINYINT(1) NOT NULL, CHANGE value_id productid_id INT NOT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660AF89CCED FOREIGN KEY (productid_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_4B365660AF89CCED ON stock (productid_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660AF89CCED');
        $this->addSql('DROP INDEX IDX_4B365660AF89CCED ON stock');
        $this->addSql('ALTER TABLE stock ADD value_id INT NOT NULL, DROP productid_id, DROP value, DROP status');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660F920BBA2 FOREIGN KEY (value_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4B365660F920BBA2 ON stock (value_id)');
    }
}
