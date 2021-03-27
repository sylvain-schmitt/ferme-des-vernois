<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210327110037 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A4D79775F');
        $this->addSql('DROP TABLE tva');
        $this->addSql('DROP INDEX IDX_B3BA5A5A4D79775F ON products');
        $this->addSql('ALTER TABLE products ADD bio TINYINT(1) NOT NULL, ADD beef TINYINT(1) NOT NULL, ADD logo TINYINT(1) NOT NULL, DROP tva_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tva (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE products ADD tva_id INT DEFAULT NULL, DROP bio, DROP beef, DROP logo');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A4D79775F FOREIGN KEY (tva_id) REFERENCES tva (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A4D79775F ON products (tva_id)');
    }
}
