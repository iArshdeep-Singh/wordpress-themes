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
}


add_action('wp_enqueue_scripts', 'style_and_script');
add_action('after_setup_theme', 'on_setup');