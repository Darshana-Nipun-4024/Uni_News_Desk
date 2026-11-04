<?php
header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

// Sanitize and collect inputs
$title     = trim(htmlspecialchars($_POST['title'] ?? ''));
$category  = trim(htmlspecialchars($_POST['category'] ?? ''));
$author    = trim(htmlspecialchars($_POST['author'] ?? ''));
$imageUrl  = trim(filter_var($_POST['imageUrl'] ?? '', FILTER_SANITIZE_URL));
$content   = trim(htmlspecialchars($_POST['content'] ?? ''));
$compliance = isset($_POST['compliance']) ? 'Yes' : 'No';

// Validate required fields
if (empty($title) || empty($category) || empty($author) || empty($content)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
    exit;
}

if ($compliance !== 'Yes') {
    echo json_encode(['status' => 'error', 'message' => 'You must confirm policy compliance.']);
    exit;
}

// Prepare submission entry
$submission = [
    'id'         => uniqid('news_'),
    'title'      => $title,
    'category'   => $category,
    'author'     => $author,
    'image_url'  => $imageUrl,
    'content'    => $content,
    'compliance' => $compliance,
    'status'     => 'Pending Review',
    'submitted_at' => date('Y-m-d H:i:s')
];

// --- OPTION 1: Save to a JSON file (simple storage) ---
$file = 'submissions.json';
$existingData = [];

if (file_exists($file)) {
    $jsonContent = file_get_contents($file);
    $existingData = json_decode($jsonContent, true) ?: [];
}

$existingData[] = $submission;

if (file_put_contents($file, json_encode($existingData, JSON_PRETTY_PRINT))) {
    echo json_encode([
        'status' => 'success', 
        'message' => 'Your article has been submitted for approval!'
    ]);
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Could not save submission. Check folder permissions.'
    ]);
}

/*
--- OPTION 2: Save to MySQL Database (Uncomment & configure to use) ---

$conn = new mysqli("localhost", "root", "", "nsbm_newsdesk");
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed.']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO news_submissions (title, category, author, image_url, content, status, submitted_at) VALUES (?, ?, ?, ?, ?, 'Pending', NOW())");
$stmt->bind_param("sssss", $title, $category, $author, $imageUrl, $content);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Submitted successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
*/
?>