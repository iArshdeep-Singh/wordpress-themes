<?php get_header(); ?>
<main>
    <?php if (have_posts()): ?>
        <?php while (have_posts()): ?>

            <?php the_post(); ?>

            <?php if (has_post_thumbnail()):
                the_post_thumbnail('thumbnail');
            endif; ?>

            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br>

        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php
// get_sidebar();

if (is_active_sidebar('weather-sidebar')) {

    dynamic_sidebar('weather-sidebar');
}

?>


<?php get_footer(); ?>