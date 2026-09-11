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

<div id="signup">

    <form>
        <h1>Create Account</h1>

        <label for="name">Name</label><br />
        <input type="text" id="name" name="name" />
        <P></P>

        <label for="username">Username</label><br />
        <input type="text" id="username" name="username" />
        <P></P>

        <label for="email">Email</label><br />
        <input type="email" id="email" name="email" />
        <P></P>

        <label for="password">Set Password</label><br />
        <input type="password" id="password" name="password" />
        <P></P>

        <label for="confirm-password">Confirm Password</label><br />
        <input type="password" id="confirm-password" name="confirm-password" />
        <P></P>

        <input type="hidden" name="signup_nonce" value="<?= wp_create_nonce('signup_action'); ?>">

        <button type="submit">Sign Up</button>

        <p id="message"></p>
        <p>Already have an account? <a href="<?= esc_url(home_url('/login/')); ?>">Login</a></p>
    </form>

</div>

<?php
get_footer();