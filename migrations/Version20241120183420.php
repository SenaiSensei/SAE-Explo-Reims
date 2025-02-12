<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241120183420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accessibility (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, place_id INT DEFAULT NULL, image LONGBLOB NOT NULL, INDEX IDX_C53D045FDA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, place_id INT DEFAULT NULL, user_id INT NOT NULL, note INT NOT NULL, INDEX IDX_CFBDFA14DA6A219 (place_id), INDEX IDX_CFBDFA14A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE path (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, image_id INT DEFAULT NULL, name VARCHAR(30) NOT NULL, description VARCHAR(255) DEFAULT NULL, time DOUBLE PRECISION DEFAULT NULL, distance DOUBLE PRECISION DEFAULT NULL, note DOUBLE PRECISION DEFAULT NULL, INDEX IDX_B548B0FA76ED395 (user_id), UNIQUE INDEX UNIQ_B548B0F3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE path_order (id INT AUTO_INCREMENT NOT NULL, place_id INT NOT NULL, path_id INT NOT NULL, position INT NOT NULL, INDEX IDX_4B3B46DA6A219 (place_id), INDEX IDX_4B3B46D96C566B (path_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(30) NOT NULL, description VARCHAR(255) DEFAULT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, postal_code INT NOT NULL, city VARCHAR(30) NOT NULL, address VARCHAR(100) NOT NULL, price_min DOUBLE PRECISION DEFAULT NULL, price_max DOUBLE PRECISION DEFAULT NULL, visit_time DOUBLE PRECISION DEFAULT NULL, note DOUBLE PRECISION DEFAULT NULL, INDEX IDX_741D53CD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place_accessibility (place_id INT NOT NULL, accessibility_id INT NOT NULL, INDEX IDX_BFF46C50DA6A219 (place_id), INDEX IDX_BFF46C508FEE2CA0 (accessibility_id), PRIMARY KEY(place_id, accessibility_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_table (id INT AUTO_INCREMENT NOT NULL, place_id INT NOT NULL, day VARCHAR(30) NOT NULL, opening DOUBLE PRECISION NOT NULL, closing DOUBLE PRECISION NOT NULL, INDEX IDX_B35B6E3ADA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, firstname VARCHAR(30) NOT NULL, lastname VARCHAR(30) NOT NULL, email VARCHAR(100) NOT NULL, phone INT DEFAULT NULL, pass VARCHAR(255) NOT NULL, admin TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D6493DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_path (user_id INT NOT NULL, path_id INT NOT NULL, INDEX IDX_71D5C7C6A76ED395 (user_id), INDEX IDX_71D5C7C6D96C566B (path_id), PRIMARY KEY(user_id, path_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_place (user_id INT NOT NULL, place_id INT NOT NULL, INDEX IDX_96DFA895A76ED395 (user_id), INDEX IDX_96DFA895DA6A219 (place_id), PRIMARY KEY(user_id, place_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FDA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE path ADD CONSTRAINT FK_B548B0FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE path ADD CONSTRAINT FK_B548B0F3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE path_order ADD CONSTRAINT FK_4B3B46DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE path_order ADD CONSTRAINT FK_4B3B46D96C566B FOREIGN KEY (path_id) REFERENCES path (id)');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE place_accessibility ADD CONSTRAINT FK_BFF46C50DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE place_accessibility ADD CONSTRAINT FK_BFF46C508FEE2CA0 FOREIGN KEY (accessibility_id) REFERENCES accessibility (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE time_table ADD CONSTRAINT FK_B35B6E3ADA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE user_path ADD CONSTRAINT FK_71D5C7C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_path ADD CONSTRAINT FK_71D5C7C6D96C566B FOREIGN KEY (path_id) REFERENCES path (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_place ADD CONSTRAINT FK_96DFA895A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_place ADD CONSTRAINT FK_96DFA895DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FDA6A219');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14DA6A219');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14A76ED395');
        $this->addSql('ALTER TABLE path DROP FOREIGN KEY FK_B548B0FA76ED395');
        $this->addSql('ALTER TABLE path DROP FOREIGN KEY FK_B548B0F3DA5256D');
        $this->addSql('ALTER TABLE path_order DROP FOREIGN KEY FK_4B3B46DA6A219');
        $this->addSql('ALTER TABLE path_order DROP FOREIGN KEY FK_4B3B46D96C566B');
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CD12469DE2');
        $this->addSql('ALTER TABLE place_accessibility DROP FOREIGN KEY FK_BFF46C50DA6A219');
        $this->addSql('ALTER TABLE place_accessibility DROP FOREIGN KEY FK_BFF46C508FEE2CA0');
        $this->addSql('ALTER TABLE time_table DROP FOREIGN KEY FK_B35B6E3ADA6A219');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493DA5256D');
        $this->addSql('ALTER TABLE user_path DROP FOREIGN KEY FK_71D5C7C6A76ED395');
        $this->addSql('ALTER TABLE user_path DROP FOREIGN KEY FK_71D5C7C6D96C566B');
        $this->addSql('ALTER TABLE user_place DROP FOREIGN KEY FK_96DFA895A76ED395');
        $this->addSql('ALTER TABLE user_place DROP FOREIGN KEY FK_96DFA895DA6A219');
        $this->addSql('DROP TABLE accessibility');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE path');
        $this->addSql('DROP TABLE path_order');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE place_accessibility');
        $this->addSql('DROP TABLE time_table');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_path');
        $this->addSql('DROP TABLE user_place');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
