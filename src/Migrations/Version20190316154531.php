<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190316154531 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_session DROP FOREIGN KEY FK_4586AAFBDBEECBA4');
        $this->addSql('DROP INDEX IDX_4586AAFBDBEECBA4 ON game_session');
        $this->addSql('ALTER TABLE game_session DROP game_stats_id');
        $this->addSql('ALTER TABLE game DROP release_date');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game ADD release_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE game_session ADD game_stats_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game_session ADD CONSTRAINT FK_4586AAFBDBEECBA4 FOREIGN KEY (game_stats_id) REFERENCES game_stats (id)');
        $this->addSql('CREATE INDEX IDX_4586AAFBDBEECBA4 ON game_session (game_stats_id)');
    }
}
