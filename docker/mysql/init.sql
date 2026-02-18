-- MySQL Initialization Script
-- This script runs when the MySQL container is first created

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `be_hanacaraka` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Grant privileges
GRANT ALL PRIVILEGES ON `be_hanacaraka`.* TO 'hanacaraka'@'%';
FLUSH PRIVILEGES;

-- Set timezone
SET GLOBAL time_zone = '+07:00';
