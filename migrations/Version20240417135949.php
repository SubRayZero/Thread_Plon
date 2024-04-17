<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417135949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE thread_category (thread_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_9FD5A1DE2904019 (thread_id), INDEX IDX_9FD5A1D12469DE2 (category_id), PRIMARY KEY(thread_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE thread_category ADD CONSTRAINT FK_9FD5A1DE2904019 FOREIGN KEY (thread_id) REFERENCES thread (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE thread_category ADD CONSTRAINT FK_9FD5A1D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE thread_category DROP FOREIGN KEY FK_9FD5A1DE2904019');
        $this->addSql('ALTER TABLE thread_category DROP FOREIGN KEY FK_9FD5A1D12469DE2');
        $this->addSql('DROP TABLE thread_category');
    }
}
