<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211208183310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO categorie(id, titre, description) VALUE (1,"categorie n°1", "blablabla")');
        $this->addSql('INSERT INTO categorie(id, titre, description) VALUE (2,"categorie n°2", "blablablaesuie")');
        $this->addSql('INSERT INTO categorie(id, titre, description) VALUE (3,"categorie n°3", "blablablaqdds ff")');


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
