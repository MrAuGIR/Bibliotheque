<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417202209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_biblio (user_id INT NOT NULL, biblio_id INT NOT NULL, INDEX IDX_BC5EA018A76ED395 (user_id), INDEX IDX_BC5EA01856407ABA (biblio_id), PRIMARY KEY(user_id, biblio_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_biblio ADD CONSTRAINT FK_BC5EA018A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_biblio ADD CONSTRAINT FK_BC5EA01856407ABA FOREIGN KEY (biblio_id) REFERENCES biblio (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_biblio DROP FOREIGN KEY FK_BC5EA018A76ED395');
        $this->addSql('ALTER TABLE user_biblio DROP FOREIGN KEY FK_BC5EA01856407ABA');
        $this->addSql('DROP TABLE user_biblio');
    }
}
