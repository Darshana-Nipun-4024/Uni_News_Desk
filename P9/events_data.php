<?php
// Tell the browser this file returns JSON data
header('Content-Type: application/json');

// The news data (copied exactly from your image)
$news_data = [
    [
        "image" => "https://images.unsplash.com/photo-1461896836934-ffe607ba8211?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80",
        "title" => "Pakistan clinch ODI tri-series with 3-wicket victory over Sri Lanka in final",
        "content" => "Pakistan clinch ODI tri-series with 3-wicket victory over Sri Lanka in finalakistan defeated Sri Lanka by three wickets in the final on Saturday to clinch the Tri-Nations One Day International (ODI) series, which also featured England."
    ],
    [
        "image" => "https://images.unsplash.com/photo-1526232761682-d26e03ac148e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80",
        "title" => "Pakistan clinch ODI tri-series with 3-wicket victory over Sri Lanka in final",
        "content" => "Pakistan clinch ODI tri-series with 3-wicket victory over Sri Lanka in finalakistan defeated Sri Lanka by three wickets in the final on Saturday to clinch the Tri-Nations One Day International (ODI) series, which also featured England."
    ],
    [
        "image" => "https://images.unsplash.com/photo-1544098485-2a2ed6da40ba?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80",
        "title" => "Pakistan clinch ODI tri-series with 3-wicket victory over Sri Lanka in final",
        "content" => "Pakistan clinch ODI tri-series with 3-wicket victory over Sri Lanka in finalakistan defeated Sri Lanka by three wickets in the final on Saturday to clinch the Tri-Nations One Day International (ODI) series, which also featured England."
    ]
];

// Output the data as JSON
echo json_encode($news_data);
?>