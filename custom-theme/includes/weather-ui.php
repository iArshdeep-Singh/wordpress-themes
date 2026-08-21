<?php

$message = '';
$is_error = false;
$city = '';
$key = $instance['key'];
$weather_data = [];

if (empty($_GET['city'])) {
    $res = wp_remote_request('https://ipapi.co/json/', [
        'method' => 'GET',
    ]);

    if (is_wp_error($res)) {
        $message = $res->get_error_message();

        $is_error = true;
    } else {
        $json = wp_remote_retrieve_body($res);
        $location_data = json_decode($json, true);

        $city = isset($location_data['city']) && !empty($location_data['city']) ? $location_data['city'] : '';
    }
} else {
    $city = $_GET['city'];
}

$url = 'http://api.openweathermap.org/data/2.5/weather?q=' . urlencode($city) . '&appid=' . $key . '&units=metric';

$response = wp_remote_request($url, [
    'method' => 'GET',
    'headers' => [
        'Content-Type' => 'application/json'
    ]
]);

if (is_wp_error($response)) {
    $message = $response->get_error_message();
    $is_error = true;
} else {
    $json_data = wp_remote_retrieve_body($response);
    $data = json_decode($json_data, true);

    if (isset($data['message']) && $data['message']) {
        $message = $data['message'];
        $is_error = true;
    } else {
        $weather_data['local_time'] = gmdate('H:i A', $data['dt'] + $data['timezone']);
        $weather_data['sunrise'] = gmdate('H:i A', $data['sys']['sunrise'] + $data['timezone']);
        $weather_data['sunset'] = gmdate('H:i A', $data['sys']['sunset'] + $data['timezone']);
        $img = 'https://openweathermap.org/img/wn/' . (isset($data['weather'][0]['icon']) ? $data['weather'][0]['icon'] : '') . '@2x.png';
    }
}

?>

<div class="weather-card">
    <form action="" method="get">
        <input name="city" type="text" placeholder="Enter City" value=<?= isset($_GET['city']) && $_GET['city'] ? $_GET['city'] : ''; ?>>
        <button type="submit">&#10148;</button>
    </form>


    <div class="weather-details">
        <div id="time-location">
            <p>
                <span style="color: red; font-size: 0.75vw"><?php echo isset($message) && !empty($message) ? $message : ''; ?></span>
                <?= isset($data['name']) ? $data['name'] : ''; ?>,
                <?= isset($data['sys']['country']) ? $data['sys']['country'] : ''; ?>
            </p>
            <p>Current weather</p>
            
            <p>
                <?= isset($weather_data['local_time']) && $weather_data['local_time'] ? $weather_data['local_time'] : ''; ?>
            </p>
        </div>
        <div id="main">
            <img src=<?= isset($img) && $img ? $img : ''; ?> alt="<?= isset($data['weather'][0]['main']) ? $data['weather'][0]['main'] : ''; ?>">
            <div>
                <span><?= isset($data['main']['temp']) ? $data['main']['temp'] : ''; ?>&deg;C</span><br>
                <span><?= isset($data['weather'][0]['main']) ? ucfirst($data['weather'][0]['main']) : ''; ?></span><br>
                <span>Feels Like:
                    <?= isset($data['main']['feels_like']) ? $data['main']['feels_like'] : ''; ?>&deg;C</span>
            </div>
        </div>

        <div id="other">
            <p><?= isset($data['weather'][0]['description']) ? ucfirst($data['weather'][0]['description']) : ''; ?></p>
            <p>Sunrise: <span> <?= isset($weather_data['sunrise']) ? $weather_data['sunrise'] : ''; ?></span>
                <br>
                Sunset:
                <span><?= isset($weather_data['sunset']) ? $weather_data['sunset'] : ''; ?>
                </span>
            </p>
        </div>
    </div>
</div>
