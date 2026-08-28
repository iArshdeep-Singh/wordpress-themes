<?php get_header(); ?>

<main>
    <?php

    $query = get_search_query();

    echo do_shortcode('[news_content endpoint="search" q="' . $query . '"]');

    // if (is_active_sidebar('weather-sidebar')) {
    
    //     dynamic_sidebar('weather-sidebar');
    // }
    ?>
</main>

<?php get_footer(); ?>