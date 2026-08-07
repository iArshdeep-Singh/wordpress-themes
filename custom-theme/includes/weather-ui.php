<?php

$city = "saharanpur";

$key = $instance['key'];
$weather_data = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $city = $_GET['city'];
    call_api($city, $key);
    exit;
} else {
    // $city = $_GET['city'];
    $data = call_api($city, $key);

    $weather_data['local_time'] = gmdate("H:i A", $data['dt'] + $data['timezone']);
    $weather_data['sunrise'] = gmdate("H:i A", $data['sys']['sunrise'] + $data['timezone']);
    $weather_data['sunset'] = gmdate("H:i A", $data['sys']['sunset'] + $data['timezone']);
    $img = "https://openweathermap.org/img/wn/" . (isset($data['weather'][0]['icon']) ? $data['weather'][0]['icon'] : "") . "@2x.png";
}

function call_api($city, $key)
{

    $url = "http://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid=" . $key . "&units=metric";

    $response = wp_remote_request($url, [
        'method' => 'GET',
        'headers' => [
            'Content-Type' => 'application/json'
        ]
    ]);

    if (is_wp_error($response)) {
        echo "<h2 style='color:red;'>" . $response->get_error_message() . "</h2>";
    } else {
        $json_data = wp_remote_retrieve_body($response);
        $data = json_decode($json_data, true);

        echo "<pre>";
        print_r($data);
        echo "</pre>";

        echo "<h1>" . $data['weather'][0]['icon'] . "</h1>";

        return $data;
    }
}

?>

<div class="weather-card">
    <form action="" method="get">
        <input name="city" type="text" placeholder="Enter City">
        <button type="submit">&#10148;</button>
    </form>

    <div class="weather-details">
        <div id="time-location">
            <p>
                <?= isset($data['name']) ? $data['name'] : ""; ?>,
                <?= isset($data['sys']['country']) ? $data['sys']['country'] : ""; ?>
            </p>
            <p>Current weather</p>
            <p>
                <?= $weather_data['local_time']; ?>
            </p>
        </div>
        <div id="main">
            <img src=<?= $img; ?> alt="<?= isset($data['weather'][0]['main']) ? $data['weather'][0]['main'] : ""; ?>">
            <div>
                <span><?= isset($data['main']['temp']) ? $data['main']['temp'] : ""; ?>&deg;C</span><br>
                <span><?= isset($data['weather'][0]['main']) ? ucfirst($data['weather'][0]['main']) : ""; ?></span><br>
                <span>Feels Like:
                    <?= isset($data['main']['feels_like']) ? $data['main']['feels_like'] : ""; ?>&deg;C</span>
            </div>
        </div>

        <div id="other">
            <p><?= isset($data['weather'][0]['description']) ? ucfirst($data['weather'][0]['description']) : ""; ?></p>
            <p>Sunrise: <span> <?= isset($weather_data['sunrise']) ? $weather_data['sunrise'] : ""; ?></span>
                <br>
                Sunset:
                <span><?= isset($weather_data['sunset']) ? $weather_data['sunset'] : ""; ?>
                </span>
            </p>
        </div>
    </div>
</div>