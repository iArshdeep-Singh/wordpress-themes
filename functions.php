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

    if (is_page(['signup', 'login', 'dashboard', 'verify-email', 'forget'])) {

        wp_enqueue_script(
            'script',
            get_template_directory_uri() . '/assets/js/main.js',
            ['jquery'],
            '1.0',
            true
        );
    }


    if (!is_page(['signup', 'login', 'dashboard', 'verify-email', 'forget'])) {

        wp_enqueue_script(
            'gnews',
            get_template_directory_uri() . '/assets/js/gnews.js',
            ['jquery'],
            '1.0',
            true
        );
    }

    wp_localize_script(
        'gnews',
        'ajax',
        [
            'endpoint' => admin_url('admin-ajax.php')
        ]
    );

    wp_localize_script(
        'script',
        'ajax',
        [
            'endpoint' => admin_url('admin-ajax.php')
        ]
    );
}

function get_news()
{
    require get_template_directory() . '/includes/gnews-api.php';
}

require get_template_directory() . '/includes/custom-sidebar.php';
require get_template_directory() . '/includes/weather-widget.php';

function news_content($atts)
{
    ob_start();
    $atts = shortcode_atts(['category' => "general", 'language' => "en", 'endpoint' => 'top-headlines'], $atts);

    require get_template_directory() . '/includes/gnews-ui.php';

    return ob_get_clean();
}


function setSMTP($phpmailer)
{

    $phpmailer->isSMTP();

    $phpmailer->Host = 'smtp.gmail.com';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 587;

    $phpmailer->Username = 'iarshdeephans@gmail.com';
    $phpmailer->Password = 'smdhcqomslvlapvy';
    $phpmailer->SMTPSecure = 'tls';

    $phpmailer->From = 'iarshdeephans@gmail.com';
    $phpmailer->FromName = 'WordPress News';
}


function auth()
{
    require get_template_directory() . "/includes/auth.php";
}

add_action('wp_enqueue_scripts', 'style_and_script');
add_action('after_setup_theme', 'on_setup');
add_action('widgets_init', 'custom_sidebar');
add_action('widgets_init', 'register_weather_widget');
add_action('wp_ajax_get_news', 'get_news');
add_action('wp_ajax_nopriv_get_news', 'get_news');
add_action('wp_ajax_auth', 'auth');
add_action('wp_ajax_nopriv_auth', 'auth');
add_shortcode('news_content', 'news_content');
add_action('phpmailer_init', 'setSMTP');
// add_action('show_admin_bar', '__return_false');
add_action('show_admin_bar', function ($show) {

    // Keep admin bar for administrators
    if (current_user_can('manage_options')) {
        return true;
    }

    // Hide for other users
    return false;
});