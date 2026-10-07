<?php

/*
 * Template Name: Edit Profile
 */

/*
 * If you don't specify a template name and instead create a file like `page-nameofpage.php`
 * You don't need to select a template from the "Template" dropdown.
 * When you create a WordPress page with the `nameofpage` slug, WordPress will automatically use the `page-nameofpage.php` template for that page.
 * The correct format for a page-specific template is `page-nameofpage.php`. It should not be `nameofpage-page.php` or any other format.
 */

get_header();

if (!is_user_logged_in()) {
    wp_redirect(
        home_url('/')
    );
    exit;
}

$current_user = wp_get_current_user();

$name = get_user_meta($current_user->ID, 'name', true);

?>

<div id="edit">

    <form>
        <h1>Edit Profile</h1>

        <label for="name">Name</label><br />
        <input type="text" id="name" name="name" value="<?= $name; ?>"/>
        <P></P>

        <label for="username">Username</label><br />
        <input type="text" id="username" name="username" value="<?= $current_user->user_login; ?>"/>
        <P></P>

        <label for="email">Email</label><br />
        <input type="email" id="email" name="email" value="<?= $current_user->user_email; ?>" />
        <P></P>

        <input type="hidden" name="edit_nonce" value="<?= wp_create_nonce('edit_action'); ?>">
        <input type="hidden" name="id" value="<?= $_GET['id']; ?>">

        <button type="submit">Update</button>
       
        <p id="message"></p>
    </form>

    <p>Do you want to delete profile? <a href="<?= esc_url(home_url('/delete?id=' . $_GET['id'])); ?>">Delete Profile</a></p>

</div>


<?php
get_footer();
