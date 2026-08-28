<?php get_header(); ?>

<?php if (have_posts()): ?>

    <?php while (have_posts()):
        the_post(); ?>

        <h1 style="color: #0b1f3f;"><?php the_title(); ?></h1>
        <main>

            <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail('medium'); ?>
            <?php endif; ?>

            <?php the_content(); ?>

        <?php endwhile; ?>

    <?php endif;

get_sidebar();

?>
</main>

<?php get_footer(); ?>