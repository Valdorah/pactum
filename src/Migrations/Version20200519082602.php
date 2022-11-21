<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200519082602 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment (comment_user_id INT NOT NULL, comment_deal_id INT NOT NULL, comment_text LONGTEXT NOT NULL, PRIMARY KEY(comment_user_id, comment_deal_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deal (deal_id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, deal_title VARCHAR(255) NOT NULL, deal_description LONGTEXT NOT NULL, deal_url VARCHAR(255) DEFAULT NULL, deal_price DOUBLE PRECISION DEFAULT NULL, deal_normal_price DOUBLE PRECISION DEFAULT NULL, deal_delivery_cost DOUBLE PRECISION DEFAULT NULL, deal_discount_code VARCHAR(255) DEFAULT NULL, INDEX IDX_E3FEC116C54C8C93 (type_id), PRIMARY KEY(deal_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deal_group (deal_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_2B1F87D9F60E2305 (deal_id), INDEX IDX_2B1F87D9FE54D947 (group_id), PRIMARY KEY(deal_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deal_type (id INT AUTO_INCREMENT NOT NULL, type_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (group_id INT AUTO_INCREMENT NOT NULL, group_name VARCHAR(255) NOT NULL, PRIMARY KEY(group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rate (rate_user_id INT NOT NULL, rate_deal_id INT NOT NULL, rate_mark INT NOT NULL, PRIMARY KEY(rate_user_id, rate_deal_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (user_id INT AUTO_INCREMENT NOT NULL, user_pseudo VARCHAR(255) NOT NULL, user_email VARCHAR(255) NOT NULL, user_password VARCHAR(255) NOT NULL, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE deal ADD CONSTRAINT FK_E3FEC116C54C8C93 FOREIGN KEY (type_id) REFERENCES deal_type (id)');
        $this->addSql('ALTER TABLE deal_group ADD CONSTRAINT FK_2B1F87D9F60E2305 FOREIGN KEY (deal_id) REFERENCES deal (deal_id)');
        $this->addSql('ALTER TABLE deal_group ADD CONSTRAINT FK_2B1F87D9FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE deal_group DROP FOREIGN KEY FK_2B1F87D9F60E2305');
        $this->addSql('ALTER TABLE deal DROP FOREIGN KEY FK_E3FEC116C54C8C93');
        $this->addSql('ALTER TABLE deal_group DROP FOREIGN KEY FK_2B1F87D9FE54D947');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE deal');
        $this->addSql('DROP TABLE deal_group');
        $this->addSql('DROP TABLE deal_type');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE rate');
        $this->addSql('DROP TABLE user');
    }
}
