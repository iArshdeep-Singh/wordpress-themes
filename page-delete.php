<?php
get_header();

if (!is_user_logged_in()) {
    wp_redirect(
        home_url('/')
    );
    exit;
}
?>

<div id="delete">
    <form>
        <h1>Do you really want to delete your profile?</h1>
        <label>
            <input type="radio" name="delete" value="true">
            Yes
        </label>
        <br>
        <label>
            <input type="radio" name="delete" value="false">
            No
        </label>

        <br>
        <br>
        <input type="hidden" name="id" value="<?= $_GET['id']; ?>">
        <button type="submit">Proceed</button>
    </form>
</div>

<?php
get_footer();
