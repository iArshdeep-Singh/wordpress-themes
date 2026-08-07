<?php


function on_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    register_nav_menus([
        'primary' => 'Primary Menu',
        'footer' => 'Footer Menu'
    ]);
}

function style_and_script()
{
    wp_enqueue_style(
        'style',
        get_stylesheet_uri(),
        [],
        null
    );

    wp_enqueue_script('jquery');

    wp_enqueue_script(
        'script',
        get_template_directory_uri() . '/assets/js/main.js',
        ['jquery'],
        '1.0',
        true
    );

    wp_enqueue_script(
        'weather-widget',
        get_template_directory_uri() . '/assets/js/weather-widget.js',
        ['jquery'],
        '1.0',
        true
    );
    wp_localize_script(
        'weather-widget',
        'ajax',
        [
            "endpoint" => admin_url('admin-ajax.php')
        ]
    );
}

require get_template_directory() . '/includes/custom-sidebar.php';
require get_template_directory() . '/includes/weather-widget.php';
// require_once get_template_directory() . '/includes/add-widget-to-sidebar.php';


function weather_api_endpoint()
{
    require get_template_directory() . "/includes/weather-ui.php";
}


add_action('wp_enqueue_scripts', 'style_and_script');
add_action('after_setup_theme', 'on_setup');
add_action('widgets_init', 'custom_sidebar');
add_action('widgets_init', 'register_weather_widget');
add_action('after_switch_theme', 'add_weather_widget_to_sidebar');
add_action('wp_ajax_weather_api_endpoint', 'weather_api_endpoint');