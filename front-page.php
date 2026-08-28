<?php get_header(); ?>
<main>
    <?php
    echo do_shortcode('[news_content category="general"]');

    get_sidebar(); ?>
</main>
<?php
get_footer();