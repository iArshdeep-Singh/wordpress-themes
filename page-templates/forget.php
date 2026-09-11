<?php
/*
Template Name: Forget Username or Password 
*/

// If you write a template name, you have to select that template from the "Template" dropdown when creating a page in WordPress.
// If you don't specify a template name and instead create a file like `page-nameofpage.php`, you don't need to select a template from the "Template" dropdown. When you create a WordPress page with the `nameofpage` slug, WordPress will automatically use the `page-nameofpage.php` template for that page. (The correct format for a page-specific template is `page-nameofpage.php`. It should not be `nameofpage-page.php` or any other format.)

get_header();

if (is_user_logged_in()) {

    wp_redirect(
        home_url('/')
    );
    exit;
}


?>

<div id="forget-password-or-username">

    <form>
        <h1>Find your username and rest password</h1>

        <label for="email">Enter your registered email</label><br />
        <input type="text" id="email" name="email" />
        <P></P>

        <label for="email">What You forget?</label><br />
        <select name="username-or-password" id="username-or-password">
            <option value="username">Username</option>
            <option value="password">Password</option>
        </select>

        <input type="hidden" name="forget_nonce" value="<?= wp_create_nonce('forget_action'); ?>">

        <button type="submit">Find</button>

        <p id="message"></p>
    </form>

</div>

<?php
get_footer(); ?>