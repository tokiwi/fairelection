<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210301110358 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, full_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, ulid BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', UNIQUE INDEX UNIQ_88BDF3E9E7927C74 (email), UNIQUE INDEX UNIQ_88BDF3E9C288C859 (ulid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assignment (id INT AUTO_INCREMENT NOT NULL, election_criteria_id INT NOT NULL, item_id INT NOT NULL, percent SMALLINT NOT NULL, ulid BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', UNIQUE INDEX UNIQ_30C544BAC288C859 (ulid), INDEX IDX_30C544BAE744A4E (election_criteria_id), INDEX IDX_30C544BA126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assignment_candidate (assignment_id INT NOT NULL, candidate_id INT NOT NULL, INDEX IDX_5A18AA3D19302F8 (assignment_id), INDEX IDX_5A18AA391BD8781 (candidate_id), PRIMARY KEY(assignment_id, candidate_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidate (id INT AUTO_INCREMENT NOT NULL, election_id INT NOT NULL, name VARCHAR(255) NOT NULL, number_of_votes SMALLINT DEFAULT NULL, position INT NOT NULL, ulid BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', UNIQUE INDEX UNIQ_C8B28E44C288C859 (ulid), INDEX IDX_C8B28E44A708DAFF (election_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE criteria (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, election_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, pictogram VARCHAR(255) NOT NULL, ulid BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', UNIQUE INDEX UNIQ_B61F9B81C288C859 (ulid), INDEX IDX_B61F9B81A76ED395 (user_id), INDEX IDX_B61F9B81A708DAFF (election_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE criteria_item (id INT AUTO_INCREMENT NOT NULL, criteria_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, acronym VARCHAR(5) NOT NULL, ulid BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', UNIQUE INDEX UNIQ_11C47F1C288C859 (ulid), INDEX IDX_11C47F1990BEA15 (criteria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE criteria_item_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE criteria_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE election (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, number_of_people_to_elect SMALLINT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, ulid BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', UNIQUE INDEX UNIQ_DCA03800C288C859 (ulid), INDEX IDX_DCA038007E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE election_criteria (id INT AUTO_INCREMENT NOT NULL, election_id INT NOT NULL, criteria_id INT NOT NULL, ulid BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', UNIQUE INDEX UNIQ_ECA09913C288C859 (ulid), INDEX IDX_ECA09913A708DAFF (election_id), INDEX IDX_ECA09913990BEA15 (criteria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE password_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(50) NOT NULL, expires_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_BEAB6C245F37A13B (token), INDEX IDX_BEAB6C24A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, election_id INT NOT NULL, candidate_id INT NOT NULL, is_elected TINYINT(1) NOT NULL, ulid BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', UNIQUE INDEX UNIQ_136AC113C288C859 (ulid), INDEX IDX_136AC113A708DAFF (election_id), UNIQUE INDEX UNIQ_136AC11391BD8781 (candidate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BAE744A4E FOREIGN KEY (election_criteria_id) REFERENCES election_criteria (id)');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BA126F525E FOREIGN KEY (item_id) REFERENCES criteria_item (id)');
        $this->addSql('ALTER TABLE assignment_candidate ADD CONSTRAINT FK_5A18AA3D19302F8 FOREIGN KEY (assignment_id) REFERENCES assignment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignment_candidate ADD CONSTRAINT FK_5A18AA391BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44A708DAFF FOREIGN KEY (election_id) REFERENCES election (id)');
        $this->addSql('ALTER TABLE criteria ADD CONSTRAINT FK_B61F9B81A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE criteria ADD CONSTRAINT FK_B61F9B81A708DAFF FOREIGN KEY (election_id) REFERENCES election (id)');
        $this->addSql('ALTER TABLE criteria_item ADD CONSTRAINT FK_11C47F1990BEA15 FOREIGN KEY (criteria_id) REFERENCES criteria (id)');
        $this->addSql('ALTER TABLE election ADD CONSTRAINT FK_DCA038007E3C61F9 FOREIGN KEY (owner_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE election_criteria ADD CONSTRAINT FK_ECA09913A708DAFF FOREIGN KEY (election_id) REFERENCES election (id)');
        $this->addSql('ALTER TABLE election_criteria ADD CONSTRAINT FK_ECA09913990BEA15 FOREIGN KEY (criteria_id) REFERENCES criteria (id)');
        $this->addSql('ALTER TABLE password_token ADD CONSTRAINT FK_BEAB6C24A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113A708DAFF FOREIGN KEY (election_id) REFERENCES election (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC11391BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE criteria DROP FOREIGN KEY FK_B61F9B81A76ED395');
        $this->addSql('ALTER TABLE election DROP FOREIGN KEY FK_DCA038007E3C61F9');
        $this->addSql('ALTER TABLE password_token DROP FOREIGN KEY FK_BEAB6C24A76ED395');
        $this->addSql('ALTER TABLE assignment_candidate DROP FOREIGN KEY FK_5A18AA3D19302F8');
        $this->addSql('ALTER TABLE assignment_candidate DROP FOREIGN KEY FK_5A18AA391BD8781');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC11391BD8781');
        $this->addSql('ALTER TABLE criteria_item DROP FOREIGN KEY FK_11C47F1990BEA15');
        $this->addSql('ALTER TABLE election_criteria DROP FOREIGN KEY FK_ECA09913990BEA15');
        $this->addSql('ALTER TABLE assignment DROP FOREIGN KEY FK_30C544BA126F525E');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E44A708DAFF');
        $this->addSql('ALTER TABLE criteria DROP FOREIGN KEY FK_B61F9B81A708DAFF');
        $this->addSql('ALTER TABLE election_criteria DROP FOREIGN KEY FK_ECA09913A708DAFF');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113A708DAFF');
        $this->addSql('ALTER TABLE assignment DROP FOREIGN KEY FK_30C544BAE744A4E');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE assignment');
        $this->addSql('DROP TABLE assignment_candidate');
        $this->addSql('DROP TABLE candidate');
        $this->addSql('DROP TABLE criteria');
        $this->addSql('DROP TABLE criteria_item');
        $this->addSql('DROP TABLE criteria_item_translations');
        $this->addSql('DROP TABLE criteria_translations');
        $this->addSql('DROP TABLE election');
        $this->addSql('DROP TABLE election_criteria');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE password_token');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
