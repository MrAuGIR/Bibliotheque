<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402202353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, published_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', isbn VARCHAR(255) DEFAULT NULL, added_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE writer (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(255) NOT NULL, century VARCHAR(3) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE writer_book (writer_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_597EE0131BC7E6B6 (writer_id), INDEX IDX_597EE01316A2B381 (book_id), PRIMARY KEY(writer_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE writer_book ADD CONSTRAINT FK_597EE0131BC7E6B6 FOREIGN KEY (writer_id) REFERENCES writer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE writer_book ADD CONSTRAINT FK_597EE01316A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE writer_book DROP FOREIGN KEY FK_597EE0131BC7E6B6');
        $this->addSql('ALTER TABLE writer_book DROP FOREIGN KEY FK_597EE01316A2B381');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE writer');
        $this->addSql('DROP TABLE writer_book');
    }
}
