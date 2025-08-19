<?php

declare(strict_types=1);

namespace Database\Migrations;

/**
 * Create users table migration
 */
final class CreateUsersTable extends Migration
{
    public function getVersion(): int
    {
        return 1;
    }
    
    public function getDescription(): string
    {
        return 'Create users table';
    }
    
    public function up(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                uuid CHAR(36) NOT NULL UNIQUE,
                first_name VARCHAR(100) NOT NULL,
                last_name VARCHAR(100) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                role ENUM('user', 'admin', 'moderator') DEFAULT 'user',
                email_verified_at TIMESTAMP NULL,
                email_verification_token VARCHAR(255) NULL,
                password_reset_token VARCHAR(255) NULL,
                password_reset_expires_at TIMESTAMP NULL,
                is_active BOOLEAN DEFAULT TRUE,
                last_login_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                INDEX idx_email (email),
                INDEX idx_uuid (uuid),
                INDEX idx_role (role),
                INDEX idx_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        $this->db->exec($sql);
    }
    
    public function down(): void
    {
        $sql = 'DROP TABLE IF EXISTS users';
        $this->db->exec($sql);
    }
}
