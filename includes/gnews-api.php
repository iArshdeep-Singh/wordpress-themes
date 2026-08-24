<?php


$data = json_decode(file_get_contents('php://input'), true);

$api_key = "28808143c8d0b4c98f4ae610cf520863";

$url = '';

if ($data['endpoint'] == 'top-headlines') {
    $url = "https://gnews.io/api/v4/top-headlines?category=general&apikey=" . $api_key;
} else {
    $url = "https://gnews.io/api/v4/search?q=example&apikey=" . $api_key;
}

$response = wp_remote_request($url, [
    'method' => 'GET',
    'headers' => [
        'content-type' => 'application/json'
    ]
]);


if (is_wp_error($response)) {
    echo $response->get_error_message();
} else {
    echo wp_remote_retrieve_body($response);
}

exit;
