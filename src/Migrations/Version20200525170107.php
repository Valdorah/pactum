<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200525170107 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment ADD comment_id INT AUTO_INCREMENT NOT NULL, ADD comment_user INT NOT NULL, ADD comment_deal INT NOT NULL, DROP comment_user_id, DROP comment_deal_id, DROP PRIMARY KEY, ADD PRIMARY KEY (comment_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CABA574A5 FOREIGN KEY (comment_user) REFERENCES user (user_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CC5C863FA FOREIGN KEY (comment_deal) REFERENCES deal (deal_id)');
        $this->addSql('CREATE INDEX IDX_9474526CABA574A5 ON comment (comment_user)');
        $this->addSql('CREATE INDEX IDX_9474526CC5C863FA ON comment (comment_deal)');
        $this->addSql('ALTER TABLE rate ADD rate_id INT AUTO_INCREMENT NOT NULL, ADD rate_user INT NOT NULL, ADD rate_deal INT NOT NULL, DROP rate_user_id, DROP rate_deal_id, DROP PRIMARY KEY, ADD PRIMARY KEY (rate_id)');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F3972976446 FOREIGN KEY (rate_user) REFERENCES user (user_id)');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F391CFA7319 FOREIGN KEY (rate_deal) REFERENCES deal (deal_id)');
        $this->addSql('CREATE INDEX IDX_DFEC3F3972976446 ON rate (rate_user)');
        $this->addSql('CREATE INDEX IDX_DFEC3F391CFA7319 ON rate (rate_deal)');
        $this->addSql('ALTER TABLE user CHANGE user_pseudo user_username VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment MODIFY comment_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CABA574A5');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CC5C863FA');
        $this->addSql('DROP INDEX IDX_9474526CABA574A5 ON comment');
        $this->addSql('DROP INDEX IDX_9474526CC5C863FA ON comment');
        $this->addSql('ALTER TABLE comment DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE comment ADD comment_user_id INT NOT NULL, ADD comment_deal_id INT NOT NULL, DROP comment_id, DROP comment_user, DROP comment_deal');
        $this->addSql('ALTER TABLE comment ADD PRIMARY KEY (comment_user_id, comment_deal_id)');
        $this->addSql('ALTER TABLE rate MODIFY rate_id INT NOT NULL');
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F3972976446');
        $this->addSql('ALTER TABLE rate DROP FOREIGN KEY FK_DFEC3F391CFA7319');
        $this->addSql('DROP INDEX IDX_DFEC3F3972976446 ON rate');
        $this->addSql('DROP INDEX IDX_DFEC3F391CFA7319 ON rate');
        $this->addSql('ALTER TABLE rate DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE rate ADD rate_user_id INT NOT NULL, ADD rate_deal_id INT NOT NULL, DROP rate_id, DROP rate_user, DROP rate_deal');
        $this->addSql('ALTER TABLE rate ADD PRIMARY KEY (rate_user_id, rate_deal_id)');
        $this->addSql('ALTER TABLE user CHANGE user_username user_pseudo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
