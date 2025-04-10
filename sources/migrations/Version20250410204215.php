<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410204215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE star (id INT AUTO_INCREMENT NOT NULL, book_id INT NOT NULL, owner_id INT NOT NULL, value DOUBLE PRECISION NOT NULL, notice VARCHAR(255) DEFAULT NULL, INDEX IDX_C9DB5A1416A2B381 (book_id), INDEX IDX_C9DB5A147E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE star ADD CONSTRAINT FK_C9DB5A1416A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE star ADD CONSTRAINT FK_C9DB5A147E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE star DROP FOREIGN KEY FK_C9DB5A1416A2B381');
        $this->addSql('ALTER TABLE star DROP FOREIGN KEY FK_C9DB5A147E3C61F9');
        $this->addSql('DROP TABLE star');
    }
}
