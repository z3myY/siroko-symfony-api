<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241217215323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE items (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE OrderItems DROP FOREIGN KEY OrderItems_ibfk_1');
        $this->addSql('ALTER TABLE OrderItems DROP FOREIGN KEY OrderItems_ibfk_2');
        $this->addSql('ALTER TABLE Orders DROP FOREIGN KEY Orders_ibfk_1');
        $this->addSql('ALTER TABLE CartItems DROP FOREIGN KEY CartItems_ibfk_1');
        $this->addSql('ALTER TABLE CartItems DROP FOREIGN KEY CartItems_ibfk_2');
        $this->addSql('ALTER TABLE Carts DROP FOREIGN KEY Carts_ibfk_1');
        $this->addSql('DROP TABLE OrderItems');
        $this->addSql('DROP TABLE Users');
        $this->addSql('DROP TABLE Orders');
        $this->addSql('DROP TABLE CartItems');
        $this->addSql('DROP TABLE Carts');
        $this->addSql('ALTER TABLE products DROP created_at, CHANGE description description VARCHAR(255) NOT NULL, CHANGE category category VARCHAR(255) NOT NULL, CHANGE sku sku VARCHAR(255) NOT NULL, CHANGE discount discount NUMERIC(10, 2) DEFAULT NULL, CHANGE rating rating NUMERIC(10, 2) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE OrderItems (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, price NUMERIC(10, 2) NOT NULL, INDEX order_id (order_id), INDEX product_id (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE Users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE Orders (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, total NUMERIC(10, 2) NOT NULL, status VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX user_id (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE CartItems (id INT AUTO_INCREMENT NOT NULL, cart_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, total_price NUMERIC(10, 2) NOT NULL, INDEX cart_id (cart_id), INDEX product_id (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE Carts (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX user_id (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE OrderItems ADD CONSTRAINT OrderItems_ibfk_1 FOREIGN KEY (order_id) REFERENCES Orders (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE OrderItems ADD CONSTRAINT OrderItems_ibfk_2 FOREIGN KEY (product_id) REFERENCES products (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE Orders ADD CONSTRAINT Orders_ibfk_1 FOREIGN KEY (user_id) REFERENCES Users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE CartItems ADD CONSTRAINT CartItems_ibfk_1 FOREIGN KEY (cart_id) REFERENCES Carts (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE CartItems ADD CONSTRAINT CartItems_ibfk_2 FOREIGN KEY (product_id) REFERENCES products (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE Carts ADD CONSTRAINT Carts_ibfk_1 FOREIGN KEY (user_id) REFERENCES Users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE items');
        $this->addSql('ALTER TABLE products ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE description description TEXT DEFAULT NULL, CHANGE category category VARCHAR(255) DEFAULT NULL, CHANGE sku sku VARCHAR(255) DEFAULT NULL, CHANGE discount discount NUMERIC(5, 2) DEFAULT NULL, CHANGE rating rating NUMERIC(3, 2) DEFAULT NULL');
    }
}
