<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210404143850 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cover DROP FOREIGN KEY FK_8D0886C516A2B381');
        $this->addSql('DROP INDEX UNIQ_8D0886C516A2B381 ON cover');
        $this->addSql('ALTER TABLE cover DROP book_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cover ADD book_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cover ADD CONSTRAINT FK_8D0886C516A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D0886C516A2B381 ON cover (book_id)');
    }
}
