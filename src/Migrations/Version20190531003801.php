<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190531003801 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE test CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0CDE12AB56 FOREIGN KEY (created_by) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0C16FE72E1 FOREIGN KEY (updated_by) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_D87F7E0CDE12AB56 ON test (created_by)');
        $this->addSql('CREATE INDEX IDX_D87F7E0C16FE72E1 ON test (updated_by)');
        $this->addSql('ALTER TABLE functionality CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE functionality ADD CONSTRAINT FK_F83C5F44DE12AB56 FOREIGN KEY (created_by) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE functionality ADD CONSTRAINT FK_F83C5F4416FE72E1 FOREIGN KEY (updated_by) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_F83C5F44DE12AB56 ON functionality (created_by)');
        $this->addSql('CREATE INDEX IDX_F83C5F4416FE72E1 ON functionality (updated_by)');
        $this->addSql('ALTER TABLE user CHANGE salt salt VARCHAR(255) DEFAULT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL, CHANGE gender gender VARCHAR(1) DEFAULT NULL, CHANGE firstname firstname VARCHAR(50) DEFAULT NULL, CHANGE lastname lastname VARCHAR(80) DEFAULT NULL, CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DE12AB56 FOREIGN KEY (created_by) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64916FE72E1 FOREIGN KEY (updated_by) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649DE12AB56 ON user (created_by)');
        $this->addSql('CREATE INDEX IDX_8D93D64916FE72E1 ON user (updated_by)');
        $this->addSql('ALTER TABLE user_setting CHANGE user_id user_id INT DEFAULT NULL, CHANGE value value VARCHAR(255) DEFAULT NULL, CHANGE created_by created_by INT DEFAULT NULL, CHANGE updated_by updated_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_setting ADD CONSTRAINT FK_C779A692DE12AB56 FOREIGN KEY (created_by) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_setting ADD CONSTRAINT FK_C779A69216FE72E1 FOREIGN KEY (updated_by) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_C779A692DE12AB56 ON user_setting (created_by)');
        $this->addSql('CREATE INDEX IDX_C779A69216FE72E1 ON user_setting (updated_by)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE functionality DROP FOREIGN KEY FK_F83C5F44DE12AB56');
        $this->addSql('ALTER TABLE functionality DROP FOREIGN KEY FK_F83C5F4416FE72E1');
        $this->addSql('DROP INDEX IDX_F83C5F44DE12AB56 ON functionality');
        $this->addSql('DROP INDEX IDX_F83C5F4416FE72E1 ON functionality');
        $this->addSql('ALTER TABLE functionality CHANGE created_by created_by VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE updated_by updated_by VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE test DROP FOREIGN KEY FK_D87F7E0CDE12AB56');
        $this->addSql('ALTER TABLE test DROP FOREIGN KEY FK_D87F7E0C16FE72E1');
        $this->addSql('DROP INDEX IDX_D87F7E0CDE12AB56 ON test');
        $this->addSql('DROP INDEX IDX_D87F7E0C16FE72E1 ON test');
        $this->addSql('ALTER TABLE test CHANGE created_by created_by VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE updated_by updated_by VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649DE12AB56');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64916FE72E1');
        $this->addSql('DROP INDEX IDX_8D93D649DE12AB56 ON `user`');
        $this->addSql('DROP INDEX IDX_8D93D64916FE72E1 ON `user`');
        $this->addSql('ALTER TABLE `user` CHANGE created_by created_by VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE updated_by updated_by VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE salt salt VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE last_login last_login DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(180) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\', CHANGE gender gender VARCHAR(1) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE firstname firstname VARCHAR(50) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE lastname lastname VARCHAR(80) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user_setting DROP FOREIGN KEY FK_C779A692DE12AB56');
        $this->addSql('ALTER TABLE user_setting DROP FOREIGN KEY FK_C779A69216FE72E1');
        $this->addSql('DROP INDEX IDX_C779A692DE12AB56 ON user_setting');
        $this->addSql('DROP INDEX IDX_C779A69216FE72E1 ON user_setting');
        $this->addSql('ALTER TABLE user_setting CHANGE user_id user_id INT DEFAULT NULL, CHANGE created_by created_by VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE updated_by updated_by VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE value value VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
