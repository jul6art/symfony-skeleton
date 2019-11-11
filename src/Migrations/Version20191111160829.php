<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191111160829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE maintenance (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, active TINYINT(1) NOT NULL, exception_ip_list LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2F84F8E9DE12AB56 (created_by), INDEX IDX_2F84F8E916FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E9DE12AB56 FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E916FE72E1 FOREIGN KEY (updated_by) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('INSERT INTO maintenance (active, exception_ip_list, created_at, updated_at) VALUES (0, \'[]\', NOW(), NOW())');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE maintenance');
    }
}
