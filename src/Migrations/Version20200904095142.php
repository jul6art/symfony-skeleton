<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200904095142 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maintenance_audit ADD discriminator VARCHAR(255) DEFAULT NULL, ADD transaction_hash VARCHAR(40) DEFAULT NULL, CHANGE diffs diffs LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE blame_id blame_id VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE INDEX discriminator_f712c5393eed16a248d653649fd4bb63_idx ON maintenance_audit (discriminator)');
        $this->addSql('CREATE INDEX transaction_hash_f712c5393eed16a248d653649fd4bb63_idx ON maintenance_audit (transaction_hash)');
        $this->addSql('ALTER TABLE test_audit ADD discriminator VARCHAR(255) DEFAULT NULL, ADD transaction_hash VARCHAR(40) DEFAULT NULL, CHANGE diffs diffs LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE blame_id blame_id VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE INDEX discriminator_1d720c2a2fe592fd5b48d737944b52ae_idx ON test_audit (discriminator)');
        $this->addSql('CREATE INDEX transaction_hash_1d720c2a2fe592fd5b48d737944b52ae_idx ON test_audit (transaction_hash)');
        $this->addSql('ALTER TABLE user_audit ADD discriminator VARCHAR(255) DEFAULT NULL, ADD transaction_hash VARCHAR(40) DEFAULT NULL, CHANGE diffs diffs LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE blame_id blame_id VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE INDEX discriminator_e06395edc291d0719bee26fd39a32e8a_idx ON user_audit (discriminator)');
        $this->addSql('CREATE INDEX transaction_hash_e06395edc291d0719bee26fd39a32e8a_idx ON user_audit (transaction_hash)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX discriminator_f712c5393eed16a248d653649fd4bb63_idx ON maintenance_audit');
        $this->addSql('DROP INDEX transaction_hash_f712c5393eed16a248d653649fd4bb63_idx ON maintenance_audit');
        $this->addSql('ALTER TABLE maintenance_audit DROP discriminator, DROP transaction_hash, CHANGE diffs diffs LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE blame_id blame_id INT UNSIGNED DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('DROP INDEX discriminator_1d720c2a2fe592fd5b48d737944b52ae_idx ON test_audit');
        $this->addSql('DROP INDEX transaction_hash_1d720c2a2fe592fd5b48d737944b52ae_idx ON test_audit');
        $this->addSql('ALTER TABLE test_audit DROP discriminator, DROP transaction_hash, CHANGE diffs diffs LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE blame_id blame_id INT UNSIGNED DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('DROP INDEX discriminator_e06395edc291d0719bee26fd39a32e8a_idx ON user_audit');
        $this->addSql('DROP INDEX transaction_hash_e06395edc291d0719bee26fd39a32e8a_idx ON user_audit');
        $this->addSql('ALTER TABLE user_audit DROP discriminator, DROP transaction_hash, CHANGE diffs diffs LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE blame_id blame_id INT UNSIGNED DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL');
    }
}
