<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200414102920 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE auction (id INT AUTO_INCREMENT NOT NULL, state_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, deadline DATETIME NOT NULL, winner INT DEFAULT NULL, INDEX IDX_DEE4F5935D83CC1 (state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bid (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, auction_id INT NOT NULL, ammount INT NOT NULL, INDEX IDX_4AF2B3F3A76ED395 (user_id), INDEX IDX_4AF2B3F357B8F0DE (auction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE state (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE auction ADD CONSTRAINT FK_DEE4F5935D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F357B8F0DE FOREIGN KEY (auction_id) REFERENCES auction (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F357B8F0DE');
        $this->addSql('ALTER TABLE auction DROP FOREIGN KEY FK_DEE4F5935D83CC1');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F3A76ED395');
        $this->addSql('DROP TABLE auction');
        $this->addSql('DROP TABLE bid');
        $this->addSql('DROP TABLE state');
        $this->addSql('DROP TABLE user');
    }
}
