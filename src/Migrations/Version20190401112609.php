<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190401112609 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE achievement DROP FOREIGN KEY FK_96737FF1A76ED395');
        $this->addSql('ALTER TABLE game_session DROP FOREIGN KEY FK_4586AAFBA76ED395');
        $this->addSql('ALTER TABLE game_sessions_per_month DROP FOREIGN KEY FK_82E16696A76ED395');
        $this->addSql('ALTER TABLE game_stats DROP FOREIGN KEY FK_65741E25A76ED395');
        $this->addSql('ALTER TABLE overall_game_stats DROP FOREIGN KEY FK_9D969260A76ED395');
        $this->addSql('ALTER TABLE playtime DROP FOREIGN KEY FK_FCDE7170A76ED395');
        $this->addSql('ALTER TABLE playtime_per_month DROP FOREIGN KEY FK_AC5E220FA76ED395');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_4586AAFBA76ED395 ON game_session');
        $this->addSql('ALTER TABLE game_session DROP user_id');
        $this->addSql('DROP INDEX IDX_96737FF1A76ED395 ON achievement');
        $this->addSql('ALTER TABLE achievement DROP user_id');
        $this->addSql('DROP INDEX IDX_FCDE7170A76ED395 ON playtime');
        $this->addSql('ALTER TABLE playtime DROP user_id');
        $this->addSql('DROP INDEX IDX_65741E25A76ED395 ON game_stats');
        $this->addSql('ALTER TABLE game_stats DROP user_id');
        $this->addSql('DROP INDEX IDX_82E16696A76ED395 ON game_sessions_per_month');
        $this->addSql('ALTER TABLE game_sessions_per_month DROP user_id');
        $this->addSql('DROP INDEX IDX_9D969260A76ED395 ON overall_game_stats');
        $this->addSql('ALTER TABLE overall_game_stats DROP user_id');
        $this->addSql('DROP INDEX IDX_AC5E220FA76ED395 ON playtime_per_month');
        $this->addSql('ALTER TABLE playtime_per_month DROP user_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, steam_id BIGINT NOT NULL, UNIQUE INDEX UNIQ_8D93D649F3FD4ECA (steam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE achievement ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE achievement ADD CONSTRAINT FK_96737FF1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_96737FF1A76ED395 ON achievement (user_id)');
        $this->addSql('ALTER TABLE game_session ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game_session ADD CONSTRAINT FK_4586AAFBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4586AAFBA76ED395 ON game_session (user_id)');
        $this->addSql('ALTER TABLE game_sessions_per_month ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game_sessions_per_month ADD CONSTRAINT FK_82E16696A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_82E16696A76ED395 ON game_sessions_per_month (user_id)');
        $this->addSql('ALTER TABLE game_stats ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game_stats ADD CONSTRAINT FK_65741E25A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_65741E25A76ED395 ON game_stats (user_id)');
        $this->addSql('ALTER TABLE overall_game_stats ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE overall_game_stats ADD CONSTRAINT FK_9D969260A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9D969260A76ED395 ON overall_game_stats (user_id)');
        $this->addSql('ALTER TABLE playtime ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE playtime ADD CONSTRAINT FK_FCDE7170A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FCDE7170A76ED395 ON playtime (user_id)');
        $this->addSql('ALTER TABLE playtime_per_month ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE playtime_per_month ADD CONSTRAINT FK_AC5E220FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AC5E220FA76ED395 ON playtime_per_month (user_id)');
    }
}
