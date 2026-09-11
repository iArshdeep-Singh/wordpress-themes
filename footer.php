<footer
    style="<?= is_page(['signup', 'login', 'verify-email', 'dashboard', 'forget']) ? 'display:none;' : 'display:block;'; ?>">
    <!-- <button id="goTop">↑ Go to Top</button> -->
    <p style="color:#0b1f3f;">&copy; News <?= date('Y'); ?></p>
</footer>

<?php wp_footer(); ?>

</body>

</html>