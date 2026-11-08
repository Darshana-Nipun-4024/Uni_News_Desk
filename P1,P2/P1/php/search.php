<?php
// Sends news back as JSON. Called by js/search.js
header("Content-Type: application/json");
// Sample news (replace with database data later)
$news = [
  ["title" => "Inter-faculty Rugby Finals",      "category" => "Sport",    "text" => "The final match takes place this Saturday."],
  ["title" => "Semester 2 Exam Timetable",       "category" => "Academic", "text" => "The exam timetable is now available."],
  ["title" => "Annual Music Night",              "category" => "Event",    "text" => "Join us at the main auditorium at 6 PM."],
  ["title" => "Library Opening Hours Updated",   "category" => "General",  "text" => "The library now opens until 10 PM."],
  ["title" => "Cultural Club Dance Performance", "category" => "Clubs",    "text" => "The dance club won first place."]
];

$q        = isset($_GET["q"]) ? trim($_GET["q"]) : "";
$category = isset($_GET["category"]) ? $_GET["category"] : "";

$results = [];
foreach ($news as $item) {
  $matchText = ($q == "") || stripos($item["title"] . " " . $item["text"], $q) !== false;
  $matchCat  = ($category == "") || $item["category"] == $category;
  if ($matchText && $matchCat) {
    $results[] = $item;
  }
}

echo json_encode($results);
?>