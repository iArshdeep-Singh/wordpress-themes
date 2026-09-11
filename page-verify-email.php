<?php
get_header();

if (!is_user_logged_in()) {

    wp_redirect(
        home_url('/login/')
    );
    exit;
}

$current_user = wp_get_current_user();

$isEmailVerified = get_user_meta($current_user->ID, 'is_email_verified', true);

if ($isEmailVerified) {
    wp_redirect(
        home_url('/login/')
    );
    exit;
}

?>

<div id="verify-email">

    <form>
        <h1>Verify Email</h1>

        <label for="otp">Enter 4 Digit OTP</label><br />
        <input type="text" id="otp" name="otp" />
        <P></P>

        <input type="hidden" name="otp_nonce" value="<?= wp_create_nonce('otp_action'); ?>">
        <input type="hidden" name="user_id" value="<?= $_GET['user_id']; ?>">
        <input type="hidden" name="regenerate_otp" value="false">
        <input type="hidden" name="later_generate" value="true">

        <button type="submit">Verify</button>

        <p id="message"></p>
    </form>
    <a href="<?= home_url('/dashboard/'); ?>">
        <button>Skip for Later</button>
    </a>
</div>

<?php
get_footer();