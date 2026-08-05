<?php get_header(); ?>

<main>

    <?php if (have_posts()): ?>

        <?php while (have_posts()):
            the_post(); ?>

            <h1><?php the_title(); ?></h1>

            <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail('medium'); ?>
            <?php endif; ?>

            <?php the_content(); ?>

        <?php endwhile; ?>

    <?php endif; ?>
</main>

<?php get_footer(); ?>