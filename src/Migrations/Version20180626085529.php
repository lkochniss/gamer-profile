<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180626085529 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE game_stats (id INT AUTO_INCREMENT NOT NULL, achievement_id INT DEFAULT NULL, playtime_id INT DEFAULT NULL, user_id INT DEFAULT NULL, game_id INT DEFAULT NULL, INDEX IDX_65741E25B3EC99FE (achievement_id), INDEX IDX_65741E2565FF1AF8 (playtime_id), INDEX IDX_65741E25A76ED395 (user_id), INDEX IDX_65741E25E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_stats ADD CONSTRAINT FK_65741E25B3EC99FE FOREIGN KEY (achievement_id) REFERENCES achievement (id)');
        $this->addSql('ALTER TABLE game_stats ADD CONSTRAINT FK_65741E2565FF1AF8 FOREIGN KEY (playtime_id) REFERENCES playtime (id)');
        $this->addSql('ALTER TABLE game_stats ADD CONSTRAINT FK_65741E25A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game_stats ADD CONSTRAINT FK_65741E25E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('ALTER TABLE game_session ADD game_stats_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game_session ADD CONSTRAINT FK_4586AAFBDBEECBA4 FOREIGN KEY (game_stats_id) REFERENCES game_session (id)');
        $this->addSql('CREATE INDEX IDX_4586AAFBDBEECBA4 ON game_session (game_stats_id)');
        $this->addSql('ALTER TABLE purchase ADD game_stats_id INT DEFAULT NULL, CHANGE notice notice VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BDBEECBA4 FOREIGN KEY (game_stats_id) REFERENCES game_stats (id)');
        $this->addSql('CREATE INDEX IDX_6117D13BDBEECBA4 ON purchase (game_stats_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13BDBEECBA4');
        $this->addSql('CREATE TABLE blog_post (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, content LONGTEXT NOT NULL COLLATE utf8_unicode_ci, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, slug VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX IDX_BA5AE01DE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_post ADD CONSTRAINT FK_BA5AE01DE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('DROP TABLE game_stats');
        $this->addSql('ALTER TABLE game_session DROP FOREIGN KEY FK_4586AAFBDBEECBA4');
        $this->addSql('DROP INDEX IDX_4586AAFBDBEECBA4 ON game_session');
        $this->addSql('ALTER TABLE game_session DROP game_stats_id');
        $this->addSql('DROP INDEX IDX_6117D13BDBEECBA4 ON purchase');
        $this->addSql('ALTER TABLE purchase DROP game_stats_id, CHANGE notice notice VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci');
    }
}
