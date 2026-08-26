<?php

?>

<div>
    <h1 id="category" style="margin-top: 0;" data-news-category=<?= $atts['category']; ?>>
        <?= ucfirst($atts['category']); ?>
    </h1>

    <div class="container"></div>

    <div class="load-more">
        <center><button>Load More</button></center>
    </div>
</div>