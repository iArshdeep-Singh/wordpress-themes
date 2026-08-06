<?php
/* CUSTOM WIDGET */

class Weather_widget extends WP_Widget
{
    // constructor
    public function __construct()
    {
        parent::__construct(
            'weather-widget',   // widget id (unique id)
            'Weather Widget',   // widget name shown in admin
            [
                'description' => 'Shows weather from API.'  // description shown in admin
            ]
        );
    }



    // widget() is built-in method displays widget on the frontend, but $args and $instance are variables, can be used as $x for $args and $y for $instance
    public function widget($args, $instance)
    {
        echo $args['before_widget'];    // before widget in sidebar - widget ਸ਼ੁਰੂ ਕਰਦਾ ਹੈ (ਜਿਵੇਂ <div class="widget">)


        echo "<h3 style='color:green;'>Weather Forecast</h3>";

        $city = "Ludhiana";

        $key = $instance['key'];
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
        }

        echo $args['after_widget'];     // after widget in sidebar - widget ਨੂੰ ਬੰਦ ਕਰਦਾ ਹੈ (ਜਿਵੇਂ </div>) (These come from register_sidebar())
    }


    // form() creates the setting form in Appearance -> Widgets, can be omitted (can be empty) depending on whether widget needs settings. And then also don not need to write update()
    public function form($instance)
    {
        $key = !empty($instance['key']) ? $instance['key'] : '';

        ?>
        <p>
            <label for="">Key</label>
            <input type="text" name="<?= $this->get_field_name('key'); ?>" value="<?= esc_attr($key); ?>">
        </p>
        <?php
    }



    // update() saves the widget settings when the user clicks save in the WordPress Settings
    public function update($new_instance, $old_instance)
    {
        $instance = [];

        $instance['key'] = sanitize_text_field($new_instance['key']);

        return $instance;
    }

}

function register_weather_widget()
{
    register_widget('Weather_widget');
}