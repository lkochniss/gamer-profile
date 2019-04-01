<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190401110721 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_session CHANGE steam_user_id steam_user_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE achievement CHANGE steam_user_id steam_user_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE playtime CHANGE steam_user_id steam_user_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE game_stats CHANGE steam_user_id steam_user_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE game_sessions_per_month CHANGE steam_user_id steam_user_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE overall_game_stats CHANGE steam_user_id steam_user_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE playtime_per_month CHANGE steam_user_id steam_user_id BIGINT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE achievement CHANGE steam_user_id steam_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE game_session CHANGE steam_user_id steam_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE game_sessions_per_month CHANGE steam_user_id steam_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE game_stats CHANGE steam_user_id steam_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE overall_game_stats CHANGE steam_user_id steam_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE playtime CHANGE steam_user_id steam_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE playtime_per_month CHANGE steam_user_id steam_user_id INT NOT NULL');
    }
}
