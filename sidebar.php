<aside>

    <?php
    if (is_active_sidebar('weather-sidebar')) {
        dynamic_sidebar('weather-sidebar');
    }
    ?>
    <div id="language-based-posts">
        <h3>News in</h3>

        <ul>
            <?php
            $posts = get_posts([
                'post_type' => 'post',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'ASC',
            ]);

            foreach ($posts as $post):
                setup_postdata($post);
                ?>
                <li><a href="<?= esc_url(the_permalink()); ?>"><?= the_title(); ?></a></li>

                <?php
            endforeach;
            wp_reset_postdata();
            ?>

        </ul>
    </div>
</aside>