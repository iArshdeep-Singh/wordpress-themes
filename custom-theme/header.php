<!DOCTYPE html>
<html <?= language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

    <nav>
        <?php wp_nav_menu([
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => 'main-menu',
            'fallback_cb' => false
        ]); ?>
    </nav>

    <header>
        <h1><?php bloginfo('name'); ?></h1>
    </header>