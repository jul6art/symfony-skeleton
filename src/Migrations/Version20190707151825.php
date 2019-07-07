<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190707151825 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE functionality (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(80) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_F83C5F445E237E06 (name), INDEX IDX_F83C5F44DE12AB56 (created_by), INDEX IDX_F83C5F4416FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_6DC044C55E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_9F74B898DE12AB56 (created_by), INDEX IDX_9F74B89816FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_D87F7E0CDE12AB56 (created_by), INDEX IDX_D87F7E0C16FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_audit (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL, object_id VARCHAR(255) NOT NULL, diffs LONGTEXT, blame_id INT UNSIGNED DEFAULT NULL, blame_user VARCHAR(255) DEFAULT NULL, blame_user_fqdn VARCHAR(255) DEFAULT NULL, blame_user_firewall VARCHAR(100) DEFAULT NULL, ip VARCHAR(45) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX type_1d720c2a2fe592fd5b48d737944b52ae_idx (type), INDEX object_id_1d720c2a2fe592fd5b48d737944b52ae_idx (object_id), INDEX blame_id_1d720c2a2fe592fd5b48d737944b52ae_idx (blame_id), INDEX created_at_1d720c2a2fe592fd5b48d737944b52ae_idx (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', gender VARCHAR(1) DEFAULT NULL, firstname VARCHAR(50) DEFAULT NULL, lastname VARCHAR(80) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), INDEX IDX_8D93D649DE12AB56 (created_by), INDEX IDX_8D93D64916FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_8F02BF9DA76ED395 (user_id), INDEX IDX_8F02BF9DFE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_audit (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL, object_id VARCHAR(255) NOT NULL, diffs LONGTEXT, blame_id INT UNSIGNED DEFAULT NULL, blame_user VARCHAR(255) DEFAULT NULL, blame_user_fqdn VARCHAR(255) DEFAULT NULL, blame_user_firewall VARCHAR(100) DEFAULT NULL, ip VARCHAR(45) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX type_e06395edc291d0719bee26fd39a32e8a_idx (type), INDEX object_id_e06395edc291d0719bee26fd39a32e8a_idx (object_id), INDEX blame_id_e06395edc291d0719bee26fd39a32e8a_idx (blame_id), INDEX created_at_e06395edc291d0719bee26fd39a32e8a_idx (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_setting (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C779A692A76ED395 (user_id), INDEX IDX_C779A692DE12AB56 (created_by), INDEX IDX_C779A69216FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE functionality ADD CONSTRAINT FK_F83C5F44DE12AB56 FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE functionality ADD CONSTRAINT FK_F83C5F4416FE72E1 FOREIGN KEY (updated_by) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE setting ADD CONSTRAINT FK_9F74B898DE12AB56 FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE setting ADD CONSTRAINT FK_9F74B89816FE72E1 FOREIGN KEY (updated_by) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0CDE12AB56 FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0C16FE72E1 FOREIGN KEY (updated_by) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649DE12AB56 FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64916FE72E1 FOREIGN KEY (updated_by) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE user_setting ADD CONSTRAINT FK_C779A692A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_setting ADD CONSTRAINT FK_C779A692DE12AB56 FOREIGN KEY (created_by) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_setting ADD CONSTRAINT FK_C779A69216FE72E1 FOREIGN KEY (updated_by) REFERENCES `user` (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9DFE54D947');
        $this->addSql('ALTER TABLE functionality DROP FOREIGN KEY FK_F83C5F44DE12AB56');
        $this->addSql('ALTER TABLE functionality DROP FOREIGN KEY FK_F83C5F4416FE72E1');
        $this->addSql('ALTER TABLE setting DROP FOREIGN KEY FK_9F74B898DE12AB56');
        $this->addSql('ALTER TABLE setting DROP FOREIGN KEY FK_9F74B89816FE72E1');
        $this->addSql('ALTER TABLE test DROP FOREIGN KEY FK_D87F7E0CDE12AB56');
        $this->addSql('ALTER TABLE test DROP FOREIGN KEY FK_D87F7E0C16FE72E1');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649DE12AB56');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64916FE72E1');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9DA76ED395');
        $this->addSql('ALTER TABLE user_setting DROP FOREIGN KEY FK_C779A692A76ED395');
        $this->addSql('ALTER TABLE user_setting DROP FOREIGN KEY FK_C779A692DE12AB56');
        $this->addSql('ALTER TABLE user_setting DROP FOREIGN KEY FK_C779A69216FE72E1');
        $this->addSql('DROP TABLE functionality');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE test_audit');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE user_audit');
        $this->addSql('DROP TABLE user_setting');
    }
}
