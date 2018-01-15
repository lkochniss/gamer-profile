<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180115143306 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE blog_post ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE game_session ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE purchase ADD slug VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE blog_post DROP slug');
        $this->addSql('ALTER TABLE game DROP slug');
        $this->addSql('ALTER TABLE game_session DROP slug');
        $this->addSql('ALTER TABLE purchase DROP slug');
    }
}
