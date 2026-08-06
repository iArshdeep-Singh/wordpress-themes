<?php

function add_weather_widget_to_sidebar()
{
    $widgets = get_option('sidebars_widgets');

    $widgets['weather-sidebar'] = []; // Empty array
    $widgets['weather-sidebar'][] = "weather-widget-2";

    update_option('sidebars_widgets', $widgets);

}

// May it helps, but not good approach, sometimes works and sometimes doesn't