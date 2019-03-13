<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190313123417 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE game_session (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, game_id INT DEFAULT NULL, game_stats_id INT DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, date DATETIME NOT NULL, duration INT NOT NULL, day DATETIME DEFAULT NULL, INDEX IDX_4586AAFBA76ED395 (user_id), INDEX IDX_4586AAFBE48FD905 (game_id), INDEX IDX_4586AAFBDBEECBA4 (game_stats_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE achievement (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, game_id INT DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, player_achievements INT NOT NULL, overall_achievements INT DEFAULT NULL, INDEX IDX_96737FF1A76ED395 (user_id), INDEX IDX_96737FF1E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, steam_id BIGINT NOT NULL, UNIQUE INDEX UNIQ_8D93D649F3FD4ECA (steam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, steam_app_id INT NOT NULL, name VARCHAR(100) NOT NULL, header_image_path VARCHAR(255) NOT NULL, release_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playtime (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, game_id INT DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, recent_playtime INT NOT NULL, overall_playtime INT DEFAULT NULL, INDEX IDX_FCDE7170A76ED395 (user_id), INDEX IDX_FCDE7170E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_stats (id INT AUTO_INCREMENT NOT NULL, achievement_id INT DEFAULT NULL, playtime_id INT DEFAULT NULL, user_id INT DEFAULT NULL, game_id INT DEFAULT NULL, INDEX IDX_65741E25B3EC99FE (achievement_id), INDEX IDX_65741E2565FF1AF8 (playtime_id), INDEX IDX_65741E25A76ED395 (user_id), INDEX IDX_65741E25E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_sessions_per_month (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, game_id INT DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, month DATETIME NOT NULL, duration INT NOT NULL, INDEX IDX_82E16696A76ED395 (user_id), INDEX IDX_82E16696E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE overall_game_stats (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, overall_achievements INT NOT NULL, player_achievements INT NOT NULL, recent_playtime INT NOT NULL, overall_playtime INT NOT NULL, game_sessions INT NOT NULL, INDEX IDX_9D969260A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playtime_per_month (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, game_stats_id INT DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, month DATETIME NOT NULL, duration INT NOT NULL, sessions INT NOT NULL, INDEX IDX_AC5E220FA76ED395 (user_id), INDEX IDX_AC5E220FDBEECBA4 (game_stats_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_session ADD CONSTRAINT FK_4586AAFBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game_session ADD CONSTRAINT FK_4586AAFBE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE game_session ADD CONSTRAINT FK_4586AAFBDBEECBA4 FOREIGN KEY (game_stats_id) REFERENCES game_stats (id)');
        $this->addSql('ALTER TABLE achievement ADD CONSTRAINT FK_96737FF1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE achievement ADD CONSTRAINT FK_96737FF1E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE playtime ADD CONSTRAINT FK_FCDE7170A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE playtime ADD CONSTRAINT FK_FCDE7170E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE game_stats ADD CONSTRAINT FK_65741E25B3EC99FE FOREIGN KEY (achievement_id) REFERENCES achievement (id)');
        $this->addSql('ALTER TABLE game_stats ADD CONSTRAINT FK_65741E2565FF1AF8 FOREIGN KEY (playtime_id) REFERENCES playtime (id)');
        $this->addSql('ALTER TABLE game_stats ADD CONSTRAINT FK_65741E25A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game_stats ADD CONSTRAINT FK_65741E25E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE game_sessions_per_month ADD CONSTRAINT FK_82E16696A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game_sessions_per_month ADD CONSTRAINT FK_82E16696E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE overall_game_stats ADD CONSTRAINT FK_9D969260A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE playtime_per_month ADD CONSTRAINT FK_AC5E220FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE playtime_per_month ADD CONSTRAINT FK_AC5E220FDBEECBA4 FOREIGN KEY (game_stats_id) REFERENCES game_stats (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_stats DROP FOREIGN KEY FK_65741E25B3EC99FE');
        $this->addSql('ALTER TABLE game_session DROP FOREIGN KEY FK_4586AAFBA76ED395');
        $this->addSql('ALTER TABLE achievement DROP FOREIGN KEY FK_96737FF1A76ED395');
        $this->addSql('ALTER TABLE playtime DROP FOREIGN KEY FK_FCDE7170A76ED395');
        $this->addSql('ALTER TABLE game_stats DROP FOREIGN KEY FK_65741E25A76ED395');
        $this->addSql('ALTER TABLE game_sessions_per_month DROP FOREIGN KEY FK_82E16696A76ED395');
        $this->addSql('ALTER TABLE overall_game_stats DROP FOREIGN KEY FK_9D969260A76ED395');
        $this->addSql('ALTER TABLE playtime_per_month DROP FOREIGN KEY FK_AC5E220FA76ED395');
        $this->addSql('ALTER TABLE game_session DROP FOREIGN KEY FK_4586AAFBE48FD905');
        $this->addSql('ALTER TABLE achievement DROP FOREIGN KEY FK_96737FF1E48FD905');
        $this->addSql('ALTER TABLE playtime DROP FOREIGN KEY FK_FCDE7170E48FD905');
        $this->addSql('ALTER TABLE game_stats DROP FOREIGN KEY FK_65741E25E48FD905');
        $this->addSql('ALTER TABLE game_sessions_per_month DROP FOREIGN KEY FK_82E16696E48FD905');
        $this->addSql('ALTER TABLE game_stats DROP FOREIGN KEY FK_65741E2565FF1AF8');
        $this->addSql('ALTER TABLE game_session DROP FOREIGN KEY FK_4586AAFBDBEECBA4');
        $this->addSql('ALTER TABLE playtime_per_month DROP FOREIGN KEY FK_AC5E220FDBEECBA4');
        $this->addSql('DROP TABLE game_session');
        $this->addSql('DROP TABLE achievement');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE playtime');
        $this->addSql('DROP TABLE game_stats');
        $this->addSql('DROP TABLE game_sessions_per_month');
        $this->addSql('DROP TABLE overall_game_stats');
        $this->addSql('DROP TABLE playtime_per_month');
    }
}
