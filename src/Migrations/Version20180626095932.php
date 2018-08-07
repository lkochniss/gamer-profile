<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180626095932 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_session DROP FOREIGN KEY FK_4586AAFBDBEECBA4');
        $this->addSql('ALTER TABLE game_session ADD CONSTRAINT FK_4586AAFBDBEECBA4 FOREIGN KEY (game_stats_id) REFERENCES game_stats (id)');
        $this->addSql('ALTER TABLE playtime_per_month ADD game_stats_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE playtime_per_month ADD CONSTRAINT FK_AC5E220FDBEECBA4 FOREIGN KEY (game_stats_id) REFERENCES game_stats (id)');
        $this->addSql('CREATE INDEX IDX_AC5E220FDBEECBA4 ON playtime_per_month (game_stats_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_session DROP FOREIGN KEY FK_4586AAFBDBEECBA4');
        $this->addSql('ALTER TABLE game_session ADD CONSTRAINT FK_4586AAFBDBEECBA4 FOREIGN KEY (game_stats_id) REFERENCES game_session (id)');
        $this->addSql('ALTER TABLE playtime_per_month DROP FOREIGN KEY FK_AC5E220FDBEECBA4');
        $this->addSql('DROP INDEX IDX_AC5E220FDBEECBA4 ON playtime_per_month');
        $this->addSql('ALTER TABLE playtime_per_month DROP game_stats_id');
    }
}
