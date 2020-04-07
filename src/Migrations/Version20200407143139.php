<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200407143139 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auction ADD state_id INT DEFAULT NULL, ADD is_bid_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE auction ADD CONSTRAINT FK_DEE4F5935D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE auction ADD CONSTRAINT FK_DEE4F5931DD1EB67 FOREIGN KEY (is_bid_id) REFERENCES is_bid (id)');
        $this->addSql('CREATE INDEX IDX_DEE4F5935D83CC1 ON auction (state_id)');
        $this->addSql('CREATE INDEX IDX_DEE4F5931DD1EB67 ON auction (is_bid_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auction DROP FOREIGN KEY FK_DEE4F5935D83CC1');
        $this->addSql('ALTER TABLE auction DROP FOREIGN KEY FK_DEE4F5931DD1EB67');
        $this->addSql('DROP INDEX IDX_DEE4F5935D83CC1 ON auction');
        $this->addSql('DROP INDEX IDX_DEE4F5931DD1EB67 ON auction');
        $this->addSql('ALTER TABLE auction DROP state_id, DROP is_bid_id');
    }
}
