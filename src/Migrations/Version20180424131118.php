<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180424131118 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE overall_game_stats (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, identifier VARCHAR(32) NOT NULL, overall_achievements INT NOT NULL, player_achievements INT NOT NULL, recently_played INT NOT NULL, time_played INT NOT NULL, game_sessions INT NOT NULL, invested_money DOUBLE PRECISION NOT NULL, wasted_money DOUBLE PRECISION NOT NULL, currency VARCHAR(4) NOT NULL, UNIQUE INDEX UNIQ_9D969260772E836A (identifier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playtime_per_month (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, month DATETIME NOT NULL, duration INT NOT NULL, sessions INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_sessions_per_month (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, modified_at DATETIME NOT NULL, month DATETIME NOT NULL, duration INT NOT NULL, INDEX IDX_82E16696E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_sessions_per_month ADD CONSTRAINT FK_82E16696E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE overall_game_stats');
        $this->addSql('DROP TABLE playtime_per_month');
        $this->addSql('DROP TABLE game_sessions_per_month');
    }
}
