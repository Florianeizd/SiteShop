<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211219162908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attachment CHANGE name name VARCHAR(255) NOT NULL, CHANGE size size INT NOT NULL, CHANGE type_mime type_mime VARCHAR(255) NOT NULL');
        $this->addSql('CREATE TABLE article_attachment (article_id INT NOT NULL, attachment_id INT NOT NULL, INDEX IDX_4586083A7294869C (article_id), INDEX IDX_4586083A464E68B (attachment_id), PRIMARY KEY(article_id, attachment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_attachment ADD CONSTRAINT FK_4586083A7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_attachment ADD CONSTRAINT FK_4586083A464E68B FOREIGN KEY (attachment_id) REFERENCES attachment (id)');
        $this->addSql('ALTER TABLE attachment DROP FOREIGN KEY FK_795FD9BB7294869C');
        $this->addSql('DROP INDEX IDX_795FD9BB7294869C ON attachment');
        $this->addSql('ALTER TABLE attachment DROP article_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attachment CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE size size INT DEFAULT NULL, CHANGE type_mime type_mime VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP TABLE article_attachment');
        $this->addSql('ALTER TABLE attachment ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BB7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_795FD9BB7294869C ON attachment (article_id)');
    }
}
