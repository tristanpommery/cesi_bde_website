<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191109180408 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE association (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, product_id INT DEFAULT NULL, fake_user_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526C71F7E88B (event_id), INDEX IDX_9474526C4584665A (product_id), INDEX IDX_9474526CA4179092 (fake_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, period_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, date DATETIME NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, duration DATETIME NOT NULL, INDEX IDX_3BAE0AA7EC8B7ADE (period_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fake_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fake_user_event (fake_user_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_2EAE3227A4179092 (fake_user_id), INDEX IDX_2EAE322771F7E88B (event_id), PRIMARY KEY(fake_user_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fake_user_association (fake_user_id INT NOT NULL, association_id INT NOT NULL, INDEX IDX_6889A7C8A4179092 (fake_user_id), INDEX IDX_6889A7C8EFB9C8A5 (association_id), PRIMARY KEY(fake_user_id, association_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, fake_user_id INT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_472B783A71F7E88B (event_id), INDEX IDX_472B783AA4179092 (fake_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE period (id INT AUTO_INCREMENT NOT NULL, time DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, association_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, stock INT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), INDEX IDX_D34A04ADEFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA4179092 FOREIGN KEY (fake_user_id) REFERENCES fake_user (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7EC8B7ADE FOREIGN KEY (period_id) REFERENCES period (id)');
        $this->addSql('ALTER TABLE fake_user_event ADD CONSTRAINT FK_2EAE3227A4179092 FOREIGN KEY (fake_user_id) REFERENCES fake_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fake_user_event ADD CONSTRAINT FK_2EAE322771F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fake_user_association ADD CONSTRAINT FK_6889A7C8A4179092 FOREIGN KEY (fake_user_id) REFERENCES fake_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fake_user_association ADD CONSTRAINT FK_6889A7C8EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783A71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783AA4179092 FOREIGN KEY (fake_user_id) REFERENCES fake_user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fake_user_association DROP FOREIGN KEY FK_6889A7C8EFB9C8A5');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADEFB9C8A5');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C71F7E88B');
        $this->addSql('ALTER TABLE fake_user_event DROP FOREIGN KEY FK_2EAE322771F7E88B');
        $this->addSql('ALTER TABLE gallery DROP FOREIGN KEY FK_472B783A71F7E88B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA4179092');
        $this->addSql('ALTER TABLE fake_user_event DROP FOREIGN KEY FK_2EAE3227A4179092');
        $this->addSql('ALTER TABLE fake_user_association DROP FOREIGN KEY FK_6889A7C8A4179092');
        $this->addSql('ALTER TABLE gallery DROP FOREIGN KEY FK_472B783AA4179092');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7EC8B7ADE');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4584665A');
        $this->addSql('DROP TABLE association');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE fake_user');
        $this->addSql('DROP TABLE fake_user_event');
        $this->addSql('DROP TABLE fake_user_association');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE period');
        $this->addSql('DROP TABLE product');
    }
}
