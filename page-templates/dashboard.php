<?php
/*
Template Name: Dashboard 
*/

// If you write a template name, you have to select that template from the "Template" dropdown when creating a page in WordPress.
// If you don't specify a template name and instead create a file like `page-nameofpage.php`, you don't need to select a template from the "Template" dropdown. When you create a WordPress page with the `nameofpage` slug, WordPress will automatically use the `page-nameofpage.php` template for that page. (The correct format for a page-specific template is `page-nameofpage.php`. It should not be `nameofpage-page.php` or any other format.)

get_header();

if (!is_user_logged_in()) {

    wp_redirect(
        home_url('/login/')
    );
    exit;
}

$current_user = wp_get_current_user();

$isEmailVerified = get_user_meta($current_user->ID, 'is_email_verified', true);

?>
<h3><a href="<?= home_url('/'); ?>">View News</a></h3>
<h1>Dashboard Template</h1>

<div id="dashboard">
    <form>
        <label for="name">Name</label>
        <div class="current_user" id="name"><?= $current_user->display_name; ?></div>

        <label for="email">Email</label>
        <div class="current_user" id="email"><?= $current_user->user_email; ?></div>

        <button type="submit" style="display: none;">Submit</button>
    </form>
</div>


<div id="verify-email-2" style="<?= !$isEmailVerified ? 'display:block;' : 'display:none;' ?>">

    <p>Your email is not verified yet.</p>

    <form>
        <input type="hidden" name="otp_nonce" value="<?= wp_create_nonce('otp_action'); ?>">
        <input type="hidden" name="user_id" value="<?= $current_user->ID; ?>">
        <input type="hidden" name="regenerate_otp" value="true">
        <input type="hidden" name="later_generate" value="true">

        <button type="submit">Verify Now</button>

        <p id="message"></p>
    </form>
</div>


<a href="<?= esc_url(wp_logout_url(home_url('/'))); ?>"><button>Logout</button></a>
<a href="<?= esc_url(home_url('/edit-profile/')); ?>"><button>Edit Profile</button></a>

<?php
get_footer(); ?>