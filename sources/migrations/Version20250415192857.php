<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250415192857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE biblio_tag (biblio_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_43BFC5F856407ABA (biblio_id), INDEX IDX_43BFC5F8BAD26311 (tag_id), PRIMARY KEY(biblio_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE biblio_tag ADD CONSTRAINT FK_43BFC5F856407ABA FOREIGN KEY (biblio_id) REFERENCES biblio (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE biblio_tag ADD CONSTRAINT FK_43BFC5F8BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biblio_tag DROP FOREIGN KEY FK_43BFC5F856407ABA');
        $this->addSql('ALTER TABLE biblio_tag DROP FOREIGN KEY FK_43BFC5F8BAD26311');
        $this->addSql('DROP TABLE biblio_tag');
    }
}
