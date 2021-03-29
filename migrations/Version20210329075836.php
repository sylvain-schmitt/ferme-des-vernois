<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210329075836 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD city VARCHAR(255) NOT NULL, ADD zip VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE products CHANGE bio bio TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE beef beef TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE logo logo TINYINT(1) DEFAULT \'1\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP city, DROP zip');
        $this->addSql('ALTER TABLE products CHANGE bio bio TINYINT(1) NOT NULL, CHANGE beef beef TINYINT(1) NOT NULL, CHANGE logo logo TINYINT(1) NOT NULL');
    }
}
