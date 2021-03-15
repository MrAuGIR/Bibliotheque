<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210315215206 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE biblio (id INT AUTO_INCREMENT NOT NULL, user_owner_id INT NOT NULL, created_at DATETIME NOT NULL, title VARCHAR(100) DEFAULT NULL, count_view INT DEFAULT NULL, UNIQUE INDEX UNIQ_D90CBB259EB185F9 (user_owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE biblio_book (biblio_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_C723DF756407ABA (biblio_id), INDEX IDX_C723DF716A2B381 (book_id), PRIMARY KEY(biblio_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, editor_id INT DEFAULT NULL, publishing_house_id INT DEFAULT NULL, cover_id INT DEFAULT NULL, title VARCHAR(200) NOT NULL, description LONGTEXT DEFAULT NULL, published_at DATE DEFAULT NULL, isbn VARCHAR(255) DEFAULT NULL, added_at DATETIME NOT NULL, INDEX IDX_CBE5A3316995AC4C (editor_id), INDEX IDX_CBE5A33167402924 (publishing_house_id), UNIQUE INDEX UNIQ_CBE5A331922726E9 (cover_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, parents_id INT DEFAULT NULL, book_id INT DEFAULT NULL, author_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_5F9E962AB706B6D3 (parents_id), INDEX IDX_5F9E962A16A2B381 (book_id), INDEX IDX_5F9E962AF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cover (id INT AUTO_INCREMENT NOT NULL, book_id INT DEFAULT NULL, file_name VARCHAR(255) NOT NULL, path_file VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D0886C516A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publishing_house (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, country VARCHAR(255) DEFAULT NULL, info LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, logo VARCHAR(255) DEFAULT NULL, color VARCHAR(8) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_book (type_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_1CEA6639C54C8C93 (type_id), INDEX IDX_1CEA663916A2B381 (book_id), PRIMARY KEY(type_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, register_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE writer (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, century VARCHAR(3) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE writer_book (writer_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_597EE0131BC7E6B6 (writer_id), INDEX IDX_597EE01316A2B381 (book_id), PRIMARY KEY(writer_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE biblio ADD CONSTRAINT FK_D90CBB259EB185F9 FOREIGN KEY (user_owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE biblio_book ADD CONSTRAINT FK_C723DF756407ABA FOREIGN KEY (biblio_id) REFERENCES biblio (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE biblio_book ADD CONSTRAINT FK_C723DF716A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A3316995AC4C FOREIGN KEY (editor_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A33167402924 FOREIGN KEY (publishing_house_id) REFERENCES publishing_house (id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331922726E9 FOREIGN KEY (cover_id) REFERENCES cover (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AB706B6D3 FOREIGN KEY (parents_id) REFERENCES comments (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE cover ADD CONSTRAINT FK_8D0886C516A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE type_book ADD CONSTRAINT FK_1CEA6639C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_book ADD CONSTRAINT FK_1CEA663916A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE writer_book ADD CONSTRAINT FK_597EE0131BC7E6B6 FOREIGN KEY (writer_id) REFERENCES writer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE writer_book ADD CONSTRAINT FK_597EE01316A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biblio_book DROP FOREIGN KEY FK_C723DF756407ABA');
        $this->addSql('ALTER TABLE biblio_book DROP FOREIGN KEY FK_C723DF716A2B381');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A16A2B381');
        $this->addSql('ALTER TABLE cover DROP FOREIGN KEY FK_8D0886C516A2B381');
        $this->addSql('ALTER TABLE type_book DROP FOREIGN KEY FK_1CEA663916A2B381');
        $this->addSql('ALTER TABLE writer_book DROP FOREIGN KEY FK_597EE01316A2B381');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AB706B6D3');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331922726E9');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A33167402924');
        $this->addSql('ALTER TABLE type_book DROP FOREIGN KEY FK_1CEA6639C54C8C93');
        $this->addSql('ALTER TABLE biblio DROP FOREIGN KEY FK_D90CBB259EB185F9');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A3316995AC4C');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AF675F31B');
        $this->addSql('ALTER TABLE writer_book DROP FOREIGN KEY FK_597EE0131BC7E6B6');
        $this->addSql('DROP TABLE biblio');
        $this->addSql('DROP TABLE biblio_book');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE cover');
        $this->addSql('DROP TABLE publishing_house');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE type_book');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE writer');
        $this->addSql('DROP TABLE writer_book');
    }
}
