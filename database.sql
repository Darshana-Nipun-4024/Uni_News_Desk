CREATE DATABASE IF NOT EXISTS nsbm_newsdesk;
USE nsbm_newsdesk;

--Users Table (Admin + Students)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','student') DEFAULT 'student',
    student_id VARCHAR(20),
    faculty VARCHAR(100),
    profile_pic VARCHAR(255) DEFAULT 'default.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);