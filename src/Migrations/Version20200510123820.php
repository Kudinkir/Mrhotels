<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200510123820 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quotes (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, hotel_id INT NOT NULL, reservation_id INT NOT NULL, date DATE NOT NULL, INDEX IDX_A1B588C554177093 (room_id), INDEX IDX_A1B588C53243BB18 (hotel_id), INDEX IDX_A1B588C5B83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, entry_date DATE DEFAULT NULL, exit_date DATE DEFAULT NULL, guest_email VARCHAR(255) DEFAULT NULL, guest_phone VARCHAR(255) DEFAULT NULL, guests_quantity INT DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, INDEX IDX_42C8495554177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotels (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, status TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rooms (id INT AUTO_INCREMENT NOT NULL, hotel_id INT NOT NULL, category VARCHAR(255) NOT NULL, square DOUBLE PRECISION DEFAULT NULL, smoking TINYINT(1) DEFAULT NULL, INDEX IDX_7CA11A963243BB18 (hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quotes ADD CONSTRAINT FK_A1B588C554177093 FOREIGN KEY (room_id) REFERENCES rooms (id)');
        $this->addSql('ALTER TABLE quotes ADD CONSTRAINT FK_A1B588C53243BB18 FOREIGN KEY (hotel_id) REFERENCES hotels (id)');
        $this->addSql('ALTER TABLE quotes ADD CONSTRAINT FK_A1B588C5B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495554177093 FOREIGN KEY (room_id) REFERENCES rooms (id)');
        $this->addSql('ALTER TABLE rooms ADD CONSTRAINT FK_7CA11A963243BB18 FOREIGN KEY (hotel_id) REFERENCES hotels (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quotes DROP FOREIGN KEY FK_A1B588C5B83297E7');
        $this->addSql('ALTER TABLE quotes DROP FOREIGN KEY FK_A1B588C53243BB18');
        $this->addSql('ALTER TABLE rooms DROP FOREIGN KEY FK_7CA11A963243BB18');
        $this->addSql('ALTER TABLE quotes DROP FOREIGN KEY FK_A1B588C554177093');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495554177093');
        $this->addSql('DROP TABLE quotes');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE hotels');
        $this->addSql('DROP TABLE rooms');
    }
}
