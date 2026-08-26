<?php get_header(); ?>
<main>

    <?php if (have_posts()): ?>

        <?php while (have_posts()): ?>

            <?php the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; ?>

    <?php endif;

    if (is_active_sidebar('weather-sidebar')) {

        dynamic_sidebar('weather-sidebar');
    }
    ?>
</main>
<?php get_footer(); ?>