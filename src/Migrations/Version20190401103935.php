<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190401103935 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_session ADD steam_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE achievement ADD steam_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE playtime ADD steam_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE game_stats ADD steam_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE game_sessions_per_month ADD steam_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE overall_game_stats ADD steam_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE playtime_per_month DROP FOREIGN KEY FK_AC5E220FDBEECBA4');
        $this->addSql('DROP INDEX IDX_AC5E220FDBEECBA4 ON playtime_per_month');
        $this->addSql('ALTER TABLE playtime_per_month ADD steam_user_id INT NOT NULL, DROP game_stats_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE achievement DROP steam_user_id');
        $this->addSql('ALTER TABLE game_session DROP steam_user_id');
        $this->addSql('ALTER TABLE game_sessions_per_month DROP steam_user_id');
        $this->addSql('ALTER TABLE game_stats DROP steam_user_id');
        $this->addSql('ALTER TABLE overall_game_stats DROP steam_user_id');
        $this->addSql('ALTER TABLE playtime DROP steam_user_id');
        $this->addSql('ALTER TABLE playtime_per_month ADD game_stats_id INT DEFAULT NULL, DROP steam_user_id');
        $this->addSql('ALTER TABLE playtime_per_month ADD CONSTRAINT FK_AC5E220FDBEECBA4 FOREIGN KEY (game_stats_id) REFERENCES game_stats (id)');
        $this->addSql('CREATE INDEX IDX_AC5E220FDBEECBA4 ON playtime_per_month (game_stats_id)');
    }
}
