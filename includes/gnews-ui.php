<?php

?>

<div>
    <div class="container" data-news-category=<?= $atts['category']; ?> data-news-endpoint=<?= $atts['endpoint']; ?>
        data-news-lang=<?= $atts['language']; ?>></div>

    <div class="load-more">

        <?=
            is_user_logged_in() ? "<center><button>Load More</button></center>" : "<center style='margin-top: 1vw;'><a href=" . home_url('/login/') . ">" . "Please Sign In To Load More</a></center>";
        ?>

    </div>
</div>