-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `app` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the app database
USE `app`;

-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `uuid` CHAR(36) NOT NULL,
    `first_name` VARCHAR(100) NOT NULL,
    `last_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('user', 'admin') DEFAULT 'user',
    `email_verified_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX `idx_users_email` (`email`),
    INDEX `idx_users_uuid` (`uuid`),
    CONSTRAINT `uk_users_email` UNIQUE (`email`),
    CONSTRAINT `uk_users_uuid` UNIQUE (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create posts table
CREATE TABLE IF NOT EXISTS `posts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `uuid` CHAR(36) NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `status` ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    `published_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX `idx_posts_user_id` (`user_id`),
    INDEX `idx_posts_status` (`status`),
    INDEX `idx_posts_published_at` (`published_at`),
    CONSTRAINT `fk_posts_user_id_users` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO `users` (`uuid`, `first_name`, `last_name`, `email`, `password_hash`, `role`, `email_verified_at`) VALUES
('550e8400-e29b-41d4-a716-446655440000', 'Admin', 'User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW()),
('550e8400-e29b-41d4-a716-446655440001', 'John', 'Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NOW());

INSERT INTO `posts` (`uuid`, `user_id`, `title`, `content`, `status`, `published_at`) VALUES
('550e8400-e29b-41d4-a716-446655440002', 1, 'Welcome to Our App', 'This is the first post in our application.', 'published', NOW()),
('550e8400-e29b-41d4-a716-446655440003', 2, 'Getting Started', 'Learn how to use our application effectively.', 'published', NOW());
