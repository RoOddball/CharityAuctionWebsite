<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200408103132 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auction DROP FOREIGN KEY FK_DEE4F5931DD1EB67');
        $this->addSql('DROP INDEX IDX_DEE4F5931DD1EB67 ON auction');
        $this->addSql('ALTER TABLE auction DROP is_bid_id, CHANGE winner winner INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auction ADD is_bid_id INT DEFAULT NULL, CHANGE winner winner INT NOT NULL');
        $this->addSql('ALTER TABLE auction ADD CONSTRAINT FK_DEE4F5931DD1EB67 FOREIGN KEY (is_bid_id) REFERENCES is_bid (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_DEE4F5931DD1EB67 ON auction (is_bid_id)');
    }
}
