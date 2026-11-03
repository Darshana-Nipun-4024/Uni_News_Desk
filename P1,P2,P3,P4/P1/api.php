<?php
/*
 * NSBM NewsDesk sample backend
 * Run this project using a PHP-enabled server (XAMPP/WAMP/Laragon or PHP built-in server).
 * This starter version uses sample in-memory data. Submitted stories are validated and
 * acknowledged, but are not permanently stored. Connect MySQL for persistent storage.
 */
header('Content-Type: application/json; charset=utf-8');

$news = [
    [
        'id' => 1,
        'category' => 'Sport',
        'title' => 'Campus sports updates',
        'description' => 'Catch up on sporting activities, competitions and achievements from the NSBM community.',
        'date' => 'November 2026',
        'image' => 'https://images.unsplash.com/photo-1531415074968-036ba1b575da?auto=format&fit=crop&w=900&q=80'
    ],
    [
        'id' => 2,
        'category' => 'Academic',
        'title' => 'Academic notices and updates',
        'description' => 'Find important academic announcements, learning opportunities and student information.',
        'date' => 'November 2026',
        'image' => 'https://images.unsplash.com/photo-1456324504439-367cee3b3c32?auto=format&fit=crop&w=900&q=80'
    ],
    [
        'id' => 3,
        'category' => 'Event',
        'title' => 'What is happening on campus?',
        'description' => 'Discover upcoming events, workshops, celebrations and activities around the university.',
        'date' => 'November 2026',
        'image' => 'https://images.unsplash.com/photo-1506157786151-b8491531f063?auto=format&fit=crop&w=900&q=80'
    ],
    [
        'id' => 4,
        'category' => 'General',
        'title' => 'Stories from the NSBM community',
        'description' => 'Student stories, campus notices and other updates from the NSBM Green University community.',
        'date' => 'November 2026',
        'image' => 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=900&q=80'
    ],
    [
        'id' => 5,
        'category' => 'Clubs',
        'title' => 'Clubs and societies',
        'description' => 'Explore student clubs, society activities and ways to get involved in campus life.',
        'date' => 'November 2026',
        'image' => 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&w=900&q=80'
    ]
];

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'GET') {
    $category = trim($_GET['category'] ?? '');
    $query = trim($_GET['q'] ?? '');

    $filtered = array_filter($news, function ($item) use ($category, $query) {
        $matchesCategory = ($category === '' || strcasecmp($item['category'], $category) === 0);
        $haystack = $item['title'] . ' ' . $item['description'] . ' ' . $item['category'];
        $matchesQuery = ($query === '' || stripos($haystack, $query) !== false);
        return $matchesCategory && $matchesQuery;
    });

    echo json_encode([
        'success' => true,
        'news' => array_values($filtered)
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid request data.']);
        exit;
    }

    $title = trim($input['title'] ?? '');
    $category = trim($input['category'] ?? '');
    $description = trim($input['description'] ?? '');
    $allowedCategories = ['Sport', 'Academic', 'Event', 'General', 'Clubs'];

    if ($title === '' || $description === '' || !in_array($category, $allowedCategories, true)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Please provide a title, description and valid category.']);
        exit;
    }

    // TODO: Save the submitted article in a MySQL database after admin approval.
    echo json_encode([
        'success' => true,
        'message' => 'Your news submission has been received for review.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
?>
