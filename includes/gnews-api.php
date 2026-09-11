<?php


$data = json_decode(file_get_contents('php://input'), true);

$api_keys = [
    "28808143c8d0b4c98f4ae610cf520863",
    "21f022a02808e45a6c0afcb24f11e192",
    "a6a69395d2f0de8acbd7eae3c5539a09",
    "fe2adc7b5026ee1643885a998d67d168",
    "19f272f4ee2566f7697e764ac02aa21e",
    "61668d6231cebefd143c3f4ccf31e2d4",
    "618561dad1d511f85d27553f0a4c9d0e",
    "4303c399c5272eb01b2ffc19544a6b15",
    "1a9e9c81d8669f3d2cc3173b8584026b",
    "f94c928ea3187cec71e56f536277cf21",
    "1faadf9b8097d2bef1f9028cd1f214de"
];


$random = array_rand($api_keys);

$url = '';

if ($data['endpoint'] == 'top-headlines') {
    $url = "https://gnews.io/api/v4/top-headlines?category=" . $data['category'] . "&lang=" . $data['lang'] . "&page=" . $data['page'] . "&country=" . $data['country'] . "&max=8&apikey=" . $api_keys[$random];
} else {
    $url = "https://gnews.io/api/v4/search?q=" . trim($data['query']) . "&page=" . $data['page'] . "&apikey=" . $api_keys[$random];
}

$response = wp_remote_request($url, [
    'method' => 'GET',
    'headers' => [
        'content-type' => 'application/json'
    ],
    // 'timeout' => 10 // default timeout is 5 seconds
]);


if (is_wp_error($response)) {
    wp_send_json_error($response->get_error_message());
} else {

    wp_send_json_success(wp_remote_retrieve_body($response));
}

