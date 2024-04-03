<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403080849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD img_id INT DEFAULT NULL, DROP img');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DC06A9F55 FOREIGN KEY (img_id) REFERENCES upload_file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8DC06A9F55 ON post (img_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DC06A9F55');
        $this->addSql('DROP INDEX UNIQ_5A8A6C8DC06A9F55 ON post');
        $this->addSql('ALTER TABLE post ADD img VARCHAR(255) NOT NULL, DROP img_id');
    }
}
