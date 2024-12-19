<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219114112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create carts and cart_products tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ');
        // Create carts table
        $this->addSql('
            CREATE TABLE carts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id)
            );
        ');

        // Create cart_products table
        $this->addSql('
            CREATE TABLE cart_products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                cart_id INT NOT NULL,
                product_id VARCHAR(255) NOT NULL,
                quantity INT NOT NULL,
                FOREIGN KEY (cart_id) REFERENCES carts(id)
            );
        ');
    }

    public function down(Schema $schema): void
    {
        // Drop cart_products table
        $this->addSql('DROP TABLE cart_products');

        // Drop carts table
        $this->addSql('DROP TABLE carts');
    }
}
