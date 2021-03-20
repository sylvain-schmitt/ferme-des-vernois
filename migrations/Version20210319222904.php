<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210319222904 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A99387CE8 FOREIGN KEY (units_id) REFERENCES unit (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5A989D9B62 ON products (slug)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A99387CE8 ON products (units_id)');
        $this->addSql('ALTER TABLE products RENAME INDEX fk_b3ba5a5a12469de2 TO IDX_B3BA5A5A12469DE2');
        $this->addSql('ALTER TABLE products RENAME INDEX fk_b3ba5a5a4d79775f TO IDX_B3BA5A5A4D79775F');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `order`');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A99387CE8');
        $this->addSql('DROP INDEX UNIQ_B3BA5A5A989D9B62 ON products');
        $this->addSql('DROP INDEX IDX_B3BA5A5A99387CE8 ON products');
        $this->addSql('ALTER TABLE products RENAME INDEX idx_b3ba5a5a12469de2 TO FK_B3BA5A5A12469DE2');
        $this->addSql('ALTER TABLE products RENAME INDEX idx_b3ba5a5a4d79775f TO FK_B3BA5A5A4D79775F');
    }
}
