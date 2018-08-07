<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180622074617 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE achievement (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, game_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, player_achievements INT NOT NULL, overall_achievements INT DEFAULT NULL, INDEX IDX_96737FF1A76ED395 (user_id), INDEX IDX_96737FF1E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playtime (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, game_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, recent_playtime INT NOT NULL, overall_playtime INT DEFAULT NULL, INDEX IDX_FCDE7170A76ED395 (user_id), INDEX IDX_FCDE7170E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achievement ADD CONSTRAINT FK_96737FF1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE achievement ADD CONSTRAINT FK_96737FF1E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE playtime ADD CONSTRAINT FK_FCDE7170A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE playtime ADD CONSTRAINT FK_FCDE7170E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('DROP TABLE settings');
        $this->addSql('ALTER TABLE game_session ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game_session ADD CONSTRAINT FK_4586AAFBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4586AAFBA76ED395 ON game_session (user_id)');
        $this->addSql('ALTER TABLE game_sessions_per_month ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game_sessions_per_month ADD CONSTRAINT FK_82E16696A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_82E16696A76ED395 ON game_sessions_per_month (user_id)');
        $this->addSql('DROP INDEX UNIQ_9D969260772E836A ON overall_game_stats');
        $this->addSql('ALTER TABLE overall_game_stats ADD user_id INT DEFAULT NULL, DROP identifier');
        $this->addSql('ALTER TABLE overall_game_stats ADD CONSTRAINT FK_9D969260A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9D969260A76ED395 ON overall_game_stats (user_id)');
        $this->addSql('ALTER TABLE playtime_per_month ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE playtime_per_month ADD CONSTRAINT FK_AC5E220FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AC5E220FA76ED395 ON playtime_per_month (user_id)');
        $this->addSql('ALTER TABLE purchase ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6117D13BA76ED395 ON purchase (user_id)');
        $this->addSql('ALTER TABLE user DROP slug, DROP created_at, DROP modified_at');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, settings_key VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, settings_value VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_E545A0C527E5CB0 (settings_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE achievement');
        $this->addSql('DROP TABLE playtime');
        $this->addSql('ALTER TABLE game_session DROP FOREIGN KEY FK_4586AAFBA76ED395');
        $this->addSql('DROP INDEX IDX_4586AAFBA76ED395 ON game_session');
        $this->addSql('ALTER TABLE game_session DROP user_id');
        $this->addSql('ALTER TABLE game_sessions_per_month DROP FOREIGN KEY FK_82E16696A76ED395');
        $this->addSql('DROP INDEX IDX_82E16696A76ED395 ON game_sessions_per_month');
        $this->addSql('ALTER TABLE game_sessions_per_month DROP user_id');
        $this->addSql('ALTER TABLE overall_game_stats DROP FOREIGN KEY FK_9D969260A76ED395');
        $this->addSql('DROP INDEX IDX_9D969260A76ED395 ON overall_game_stats');
        $this->addSql('ALTER TABLE overall_game_stats ADD identifier VARCHAR(32) NOT NULL COLLATE utf8_unicode_ci, DROP user_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9D969260772E836A ON overall_game_stats (identifier)');
        $this->addSql('ALTER TABLE playtime_per_month DROP FOREIGN KEY FK_AC5E220FA76ED395');
        $this->addSql('DROP INDEX IDX_AC5E220FA76ED395 ON playtime_per_month');
        $this->addSql('ALTER TABLE playtime_per_month DROP user_id');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13BA76ED395');
        $this->addSql('DROP INDEX IDX_6117D13BA76ED395 ON purchase');
        $this->addSql('ALTER TABLE purchase DROP user_id');
        $this->addSql('ALTER TABLE user ADD slug VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD created_at DATETIME NOT NULL, ADD modified_at DATETIME NOT NULL');
    }
}
