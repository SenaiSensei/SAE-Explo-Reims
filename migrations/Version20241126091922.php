<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241126091922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE path_order DROP FOREIGN KEY FK_4B3B46D96C566B');
        $this->addSql('CREATE TABLE itinerary (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, image_id INT DEFAULT NULL, name VARCHAR(30) NOT NULL, description VARCHAR(255) DEFAULT NULL, time DOUBLE PRECISION DEFAULT NULL, distance DOUBLE PRECISION DEFAULT NULL, note DOUBLE PRECISION DEFAULT NULL, INDEX IDX_FF2238F6A76ED395 (user_id), UNIQUE INDEX UNIQ_FF2238F63DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_itinerary (user_id INT NOT NULL, itinerary_id INT NOT NULL, INDEX IDX_FFC2B512A76ED395 (user_id), INDEX IDX_FFC2B51215F737B2 (itinerary_id), PRIMARY KEY(user_id, itinerary_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE itinerary ADD CONSTRAINT FK_FF2238F6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE itinerary ADD CONSTRAINT FK_FF2238F63DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE user_itinerary ADD CONSTRAINT FK_FFC2B512A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_itinerary ADD CONSTRAINT FK_FFC2B51215F737B2 FOREIGN KEY (itinerary_id) REFERENCES itinerary (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE path DROP FOREIGN KEY FK_B548B0FA76ED395');
        $this->addSql('ALTER TABLE path DROP FOREIGN KEY FK_B548B0F3DA5256D');
        $this->addSql('ALTER TABLE user_path DROP FOREIGN KEY FK_71D5C7C6D96C566B');
        $this->addSql('ALTER TABLE user_path DROP FOREIGN KEY FK_71D5C7C6A76ED395');
        $this->addSql('DROP TABLE path');
        $this->addSql('DROP TABLE user_path');
        $this->addSql('DROP INDEX IDX_4B3B46D96C566B ON path_order');
        $this->addSql('ALTER TABLE path_order CHANGE path_id itinerary_id INT NOT NULL');
        $this->addSql('ALTER TABLE path_order ADD CONSTRAINT FK_4B3B4615F737B2 FOREIGN KEY (itinerary_id) REFERENCES itinerary (id)');
        $this->addSql('CREATE INDEX IDX_4B3B4615F737B2 ON path_order (itinerary_id)');
        $this->addSql('ALTER TABLE user ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', DROP admin, CHANGE pass password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE path_order DROP FOREIGN KEY FK_4B3B4615F737B2');
        $this->addSql('CREATE TABLE path (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, image_id INT DEFAULT NULL, name VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, time DOUBLE PRECISION DEFAULT NULL, distance DOUBLE PRECISION DEFAULT NULL, note DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_B548B0F3DA5256D (image_id), INDEX IDX_B548B0FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_path (user_id INT NOT NULL, path_id INT NOT NULL, INDEX IDX_71D5C7C6A76ED395 (user_id), INDEX IDX_71D5C7C6D96C566B (path_id), PRIMARY KEY(user_id, path_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE path ADD CONSTRAINT FK_B548B0FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE path ADD CONSTRAINT FK_B548B0F3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE user_path ADD CONSTRAINT FK_71D5C7C6D96C566B FOREIGN KEY (path_id) REFERENCES path (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_path ADD CONSTRAINT FK_71D5C7C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE itinerary DROP FOREIGN KEY FK_FF2238F6A76ED395');
        $this->addSql('ALTER TABLE itinerary DROP FOREIGN KEY FK_FF2238F63DA5256D');
        $this->addSql('ALTER TABLE user_itinerary DROP FOREIGN KEY FK_FFC2B512A76ED395');
        $this->addSql('ALTER TABLE user_itinerary DROP FOREIGN KEY FK_FFC2B51215F737B2');
        $this->addSql('DROP TABLE itinerary');
        $this->addSql('DROP TABLE user_itinerary');
        $this->addSql('DROP INDEX IDX_4B3B4615F737B2 ON path_order');
        $this->addSql('ALTER TABLE path_order CHANGE itinerary_id path_id INT NOT NULL');
        $this->addSql('ALTER TABLE path_order ADD CONSTRAINT FK_4B3B46D96C566B FOREIGN KEY (path_id) REFERENCES path (id)');
        $this->addSql('CREATE INDEX IDX_4B3B46D96C566B ON path_order (path_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD admin TINYINT(1) NOT NULL, DROP roles, CHANGE password pass VARCHAR(255) NOT NULL');
    }
}
