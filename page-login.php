<?php
/*
 * If you don't specify a template name and instead create a file like `page-nameofpage.php`
 * You don't need to select a template from the "Template" dropdown.
 * When you create a WordPress page with the `nameofpage` slug, WordPress will automatically use the `page-nameofpage.php` template for that page.
 * The correct format for a page-specific template is `page-nameofpage.php`. It should not be `nameofpage-page.php` or any other format.
 */

get_header();


if (is_user_logged_in()) {

    wp_redirect(
        home_url('/')
    );
    exit;
}


?>

<div id="login">

    <form>
        <h1>Login</h1>

        <label for="username">Username</label><br />
        <input type="text" id="username" name="username" />
        <P></P>

        <label for="password">Password</label><br />
        <input type="password" id="password" name="password" />
        <P></P>

        <input type="hidden" name="login_nonce" value="<?= wp_create_nonce('login_action'); ?>">

        <button type="submit">Login</button>

        <p id="message"></p>
        <p>Don't have an account? <a href="<?= esc_url(home_url('/signup/')); ?>">Signup</a></p>
        <p>Forgot password or username? <a href="<?= esc_url(home_url('/reset-password-or-find-username/')); ?>">Click
                here</a></p>
    </form>

</div>


<?php
get_footer();