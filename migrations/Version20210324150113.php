<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324150113 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actuality (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description CLOB NOT NULL, image_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, active BOOLEAN DEFAULT \'1\' NOT NULL)');
        $this->addSql('CREATE TABLE "order" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, order_id VARCHAR(255) DEFAULT NULL, quantity INTEGER DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_F52993984584665A ON "order" (product_id)');
        $this->addSql('DROP INDEX IDX_B3BA5A5A99387CE8');
        $this->addSql('DROP INDEX IDX_B3BA5A5A4D79775F');
        $this->addSql('DROP INDEX IDX_B3BA5A5A12469DE2');
        $this->addSql('DROP INDEX UNIQ_B3BA5A5A989D9B62');
        $this->addSql('CREATE TEMPORARY TABLE __temp__products AS SELECT id, category_id, tva_id, units_id, title, description, price, created_at, updated_at, pound, quantity, active, slug, image_name FROM products');
        $this->addSql('DROP TABLE products');
        $this->addSql('CREATE TABLE products (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, tva_id INTEGER DEFAULT NULL, units_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB NOT NULL COLLATE BINARY, price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, pound DOUBLE PRECISION NOT NULL, quantity INTEGER NOT NULL, active BOOLEAN DEFAULT \'1\' NOT NULL, slug VARCHAR(128) NOT NULL COLLATE BINARY, image_name VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_B3BA5A5A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B3BA5A5A4D79775F FOREIGN KEY (tva_id) REFERENCES tva (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B3BA5A5A99387CE8 FOREIGN KEY (units_id) REFERENCES unit (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO products (id, category_id, tva_id, units_id, title, description, price, created_at, updated_at, pound, quantity, active, slug, image_name) SELECT id, category_id, tva_id, units_id, title, description, price, created_at, updated_at, pound, quantity, active, slug, image_name FROM __temp__products');
        $this->addSql('DROP TABLE __temp__products');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A99387CE8 ON products (units_id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A4D79775F ON products (tva_id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A12469DE2 ON products (category_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5A989D9B62 ON products (slug)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A2B36786B6DE44026 ON products (title, description)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE actuality');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP INDEX UNIQ_B3BA5A5A989D9B62');
        $this->addSql('DROP INDEX IDX_B3BA5A5A12469DE2');
        $this->addSql('DROP INDEX IDX_B3BA5A5A4D79775F');
        $this->addSql('DROP INDEX IDX_B3BA5A5A99387CE8');
        $this->addSql('DROP INDEX IDX_B3BA5A5A2B36786B6DE44026');
        $this->addSql('CREATE TEMPORARY TABLE __temp__products AS SELECT id, category_id, tva_id, units_id, title, description, price, image_name, slug, created_at, updated_at, quantity, pound, active FROM products');
        $this->addSql('DROP TABLE products');
        $this->addSql('CREATE TABLE products (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, tva_id INTEGER DEFAULT NULL, units_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, description CLOB NOT NULL, price DOUBLE PRECISION NOT NULL, image_name VARCHAR(255) DEFAULT NULL, slug VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, quantity INTEGER NOT NULL, pound DOUBLE PRECISION NOT NULL, active BOOLEAN DEFAULT \'1\' NOT NULL)');
        $this->addSql('INSERT INTO products (id, category_id, tva_id, units_id, title, description, price, image_name, slug, created_at, updated_at, quantity, pound, active) SELECT id, category_id, tva_id, units_id, title, description, price, image_name, slug, created_at, updated_at, quantity, pound, active FROM __temp__products');
        $this->addSql('DROP TABLE __temp__products');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5A989D9B62 ON products (slug)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A12469DE2 ON products (category_id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A4D79775F ON products (tva_id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A99387CE8 ON products (units_id)');
    }
}
