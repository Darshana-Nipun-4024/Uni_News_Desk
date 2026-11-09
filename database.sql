CREATE DATABASE IF NOT EXISTS nsbm_newsdesk;
USE nsbm_newsdesk;

-- Users Table (Admin + Students)
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

-- Categories Table
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL,
    description TEXT
);

-- Articles Table
CREATE TABLE articles (
    article_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    category_id INT,
    author_id INT,
    author_name VARCHAR(150),
    image_url VARCHAR(500),
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    is_breaking BOOLEAN DEFAULT FALSE,
    is_featured BOOLEAN DEFAULT FALSE,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- Comments Table
CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT,
    user_id INT,
    comment_text TEXT NOT NULL,
    status ENUM('visible','hidden') DEFAULT 'visible',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(article_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Bookmarks Table
CREATE TABLE bookmarks (
    bookmark_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    article_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_bookmark (user_id, article_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (article_id) REFERENCES articles(article_id) ON DELETE CASCADE
);

-- Subscriptions Table
CREATE TABLE subscriptions (
    sub_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_sub (user_id, category_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

-- Default Categories
INSERT INTO categories (name, slug, description) VALUES
('Sports', 'sports', 'Campus sports events and achievements'),
('Academic', 'academic', 'Academic announcements and news'),
('Events', 'events', 'Upcoming campus events'),
('General', 'general', 'General campus news'),
('Clubs', 'clubs', 'Club and society updates');

-- Default Admin (password: admin123)
INSERT INTO users (full_name, email, password, role) VALUES
('Super Admin', 'admin@nsbm.ac.lk', '$2y$10$e0NRxP5Rk0CgEVZ8kVfLyeqGDz6fSgRxKr9fQ3qXjEjKbF.UQZrEi', 'admin');