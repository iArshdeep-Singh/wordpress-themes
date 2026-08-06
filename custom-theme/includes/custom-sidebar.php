<?php

function custom_sidebar()
{
    register_sidebar([
        'name' => 'Main Sidebar',
        'id' => 'main-sidebar',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ]);

    register_sidebar([
        'name' => 'Weather Sidebar',
        'id' => 'weather-sidebar',
        'before_widget' => '<div class="weather-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ]);
}