<!DOCTYPE html>
<html <?= language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

    <nav class="site-navigation">

        <a href="<?= esc_url(home_url('/')); ?>" class="home-link">
            Home
        </a>

        <?php wp_nav_menu([
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => 'main-menu',
            'fallback_cb' => false
        ]); ?>

        <form class="search-form" method="get" action="<?= esc_url(home_url('/')); ?>">
            <input type="search" name="s" placeholder="Search News" />
            <button type="submit">Search</button>
        </form>

        <a href="<?= esc_url(wp_login_url()); ?>" class="account-link">
            <span class="account-icon">👤</span>
            Account
        </a>

    </nav>



    <header>
        <h1 style="color: #0b1f3f;"><?php if (!empty(get_search_query())) {
            echo "Results for \"" . get_search_query() . "\"";
        } ?></h1>
    </header>