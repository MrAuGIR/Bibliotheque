<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403213557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE biblio (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_D90CBB25A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE biblio_book (biblio_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_C723DF756407ABA (biblio_id), INDEX IDX_C723DF716A2B381 (book_id), PRIMARY KEY(biblio_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notice (id INT AUTO_INCREMENT NOT NULL, book_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', up INT DEFAULT NULL, down INT DEFAULT NULL, INDEX IDX_480D45C216A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publishing_house (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, country VARCHAR(100) DEFAULT NULL, short_description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE biblio ADD CONSTRAINT FK_D90CBB25A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE biblio_book ADD CONSTRAINT FK_C723DF756407ABA FOREIGN KEY (biblio_id) REFERENCES biblio (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE biblio_book ADD CONSTRAINT FK_C723DF716A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notice ADD CONSTRAINT FK_480D45C216A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE book ADD publishing_house_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A33167402924 FOREIGN KEY (publishing_house_id) REFERENCES publishing_house (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A33167402924 ON book (publishing_house_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A33167402924');
        $this->addSql('ALTER TABLE biblio DROP FOREIGN KEY FK_D90CBB25A76ED395');
        $this->addSql('ALTER TABLE biblio_book DROP FOREIGN KEY FK_C723DF756407ABA');
        $this->addSql('ALTER TABLE biblio_book DROP FOREIGN KEY FK_C723DF716A2B381');
        $this->addSql('ALTER TABLE notice DROP FOREIGN KEY FK_480D45C216A2B381');
        $this->addSql('DROP TABLE biblio');
        $this->addSql('DROP TABLE biblio_book');
        $this->addSql('DROP TABLE notice');
        $this->addSql('DROP TABLE publishing_house');
        $this->addSql('DROP INDEX IDX_CBE5A33167402924 ON book');
        $this->addSql('ALTER TABLE book DROP publishing_house_id');
    }
}
