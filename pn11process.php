<?php
// Set response type to JSON
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request Method.']);
    exit;
}

// 1. Sanitize Inputs (Prevents XSS Attacks)
$title     = htmlspecialchars(trim($_POST['article_title'] ?? ''));
$category  = htmlspecialchars(trim($_POST['category'] ?? ''));
$author    = htmlspecialchars(trim($_POST['author'] ?? ''));
$image_url = filter_var(trim($_POST['image_url'] ?? ''), FILTER_SANITIZE_URL);
$content   = htmlspecialchars(trim($_POST['content'] ?? ''));
$agreement = isset($_POST['agreement']);

// 2. Server-Side Validation
if (empty($title) || empty($category) || empty($author) || empty($content)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
    exit;
}

if (!$agreement) {
    echo json_encode(['status' => 'error', 'message' => 'You must agree to the media policies.']);
    exit;
}

if (!empty($image_url) && !filter_var($image_url, FILTER_VALIDATE_URL)) {
    echo json_encode(['status' => 'error', 'message' => 'Please provide a valid URL for the image.']);
    exit;
}

// 3. Save to a Log File (Simulation of Database)
// This creates a 'submissions.log' file in the same folder
$log_entry = "----- NEW SUBMISSION -----\n";
$log_entry .= "Date: " . date('Y-m-d H:i:s') . "\n";
$log_entry .= "Title: $title\n";
$log_entry .= "Category: $category\n";
$log_entry .= "Author: $author\n";
$log_entry .= "Image: $image_url\n";
$log_entry .= "Content: $content\n\n";

// Try to write to log file
@file_put_contents('submissions.log', $log_entry, FILE_APPEND);

/* 
// 4. Example: Save to MySQL Database (Uncomment and configure to use)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=nsbm_db;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("INSERT INTO news_articles (title, category, author, image_url, content, status, created_at) 
                           VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
    $stmt->execute([$title, $category, $author, $image_url, $content]);
    
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}

// SQL to create the table:
--
CREATE TABLE news_articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    author VARCHAR(255) NOT NULL,
    image_url VARCHAR(500),
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
--
*/

// 5. Send Success Response back to JavaScript
echo json_encode([
    'status' => 'success', 
    'message' => '✓ Thank you! Your article "' . $title . '" has been submitted for review by the Faculty NewsDesk Admin.'
]);
exit;
?>