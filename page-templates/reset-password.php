<?php
/*
Template Name: Reset Password 
*/

// If you write a template name, you have to select that template from the "Template" dropdown when creating a page in WordPress.
// If you don't specify a template name and instead create a file like `page-nameofpage.php`, you don't need to select a template from the "Template" dropdown. When you create a WordPress page with the `nameofpage` slug, WordPress will automatically use the `page-nameofpage.php` template for that page. (The correct format for a page-specific template is `page-nameofpage.php`. It should not be `nameofpage-page.php` or any other format.)

get_header();

if (is_user_logged_in() || (!isset($_GET['reset_password_key']) || !isset($_GET['id'])) || (empty($_GET['reset_password_key']) || empty($_GET['id']))) {

    wp_redirect(
        home_url('/')
    );
    exit;
}


?>

<div id="set-new-password">

    <form>
        <h1>Set your new password</h1>

        <label for="password">Set Password</label><br />
        <input type="password" id="password" name="password" />
        <P></P>

        <label for="confirm-password">Confirm Password</label><br />
        <input type="password" id="confirm-password" name="confirm-password" />
        <P></P>


        <input type="hidden" name="reset_nonce" value="<?= wp_create_nonce('reset_action'); ?>">
        <input type="hidden" name="reset_password_key" value="<?= $_GET['reset_password_key']; ?>">
        <input type="hidden" name="id" value="<?= $_GET['id']; ?>">

        <button type="submit">Submit</button>

        <p id="message"></p>
    </form>

</div>

<?php
get_footer(); ?>