<?php

$data = json_decode(file_get_contents('php://input'), true);

if ($data['action'] == 'signup') {
    if (!isset($data['data']['signup_nonce']) || !wp_verify_nonce($data['data']['signup_nonce'], 'signup_action')) {
        wp_send_json_error(['message' => 'Security check failed.']);
    }

    $isThereAnyError = false;
    $errorMessage = [];

    $username = $data['data']['username'];
    $email = sanitize_email($data['data']['email']);
    $name = sanitize_text_field($data['data']['name']);
    $password = $data['data']['password'];

    if (email_exists($email)) {
        $errorMessage['email'] = 'Email already registered.';
        $isThereAnyError = true;
    }

    if (username_exists($username)) {
        $errorMessage['username'] = 'Username already exists.';
        $isThereAnyError = true;
    }

    if (!is_email($email)) {
        $errorMessage['email'] = 'Invalid email address.';
        $isThereAnyError = true;
    }

    if ($isThereAnyError) {
        wp_send_json_error($errorMessage);
    } else {
        // generate OTP
        $otp = wp_rand(1000, 9999);

        $results = sendMail($email, $name, $otp, 'otp');

        if ($results) {
            $user_id = wp_create_user($username, $password, $email);
            wp_set_auth_cookie($user_id);

            // verify email
            update_user_meta($user_id, 'is_email_verified', 0);

            // save OTP
            update_user_meta($user_id, 'otp', $otp);  // update_user_meta() updates an existing value if that user/meta key already exists; otherwise, it creates it.

            // OTP expiry
            update_user_meta($user_id, 'expiry_otp', time() + (2 * 60));  // 120 = 2 minutes

            // Create user
            if (is_wp_error($user_id)) {
                wp_send_json_error([['message' => $user_id->get_error_message()]]);
            }

            // save name
            update_user_meta($user_id, 'name', $name);

            wp_send_json_success([
                'message' => "4 digits OTP sent to {$email}.",
                'redirect' => home_url('/verify-email?user_id=' . $user_id),
                'results' => $results
            ]);
        } else {
            wp_send_json_error([
                'message' => 'Email failed to send.',
                'results' => $results
            ]);
        }
    }
} else if ($data['action'] == 'verify-email') {
    if (!isset($data['data']['otp_nonce']) || !wp_verify_nonce($data['data']['otp_nonce'], 'otp_action')) {
        wp_send_json_error(['message' => 'Security check failed.']);
    }

    $user_id = $data['data']['user_id'];
    $user = get_userdata($user_id);

    $name = get_user_meta($user_id, 'name', true);
    $email = $user->user_email;

    if ($data['data']['regenerate_otp'] == 'true') {
        $otp = wp_rand(1000, 9999);

        update_user_meta($user_id, 'otp', $otp);
        update_user_meta($user_id, 'expiry_otp', time() + (2 * 60));

        $results = sendMail($email, $name, $otp, 'otp');

        if ($results && $data['data']['later_generate'] == 'true') {
            wp_send_json_success([
                'message' => "A 4 digits OTP sent to {$email}.",
                'redirect' => home_url('/verify-email?user_id=' . $user_id),
                'results' => $results
            ]);
        } else {
            wp_send_json_error([
                'message' => 'Email failed to send.',
                'results' => $results
            ]);
        }
    }

    $otp = get_user_meta($user_id, 'otp', true);  // without true returns an array and with true returns the actual value
    $expiry_otp = get_user_meta($user_id, 'expiry_otp', true);

    if (time() > $expiry_otp) {
        wp_send_json_error(['otp' => 'Your OTP has been expired, please regenerate new one.', 'expired' => 'true']);
    }

    if ((string) $otp !== (string) $data['data']['otp']) {
        wp_send_json_error(['otp' => 'Invalid OTP.']);
    }

    update_user_meta($user_id, 'is_email_verified', 1);

    wp_send_json_success([
        'message' => 'Your email is verified.',
        'redirect' => home_url('/dashboard/'),
        'results' => $results
    ]);
} else if ($data['action'] == 'login') {
    if (!isset($data['data']['login_nonce']) || !wp_verify_nonce($data['data']['login_nonce'], 'login_action')) {
        wp_send_json_error(['message' => 'Security check failed.']);
    }

    $username = $data['data']['username'];
    $password = $data['data']['password'];

    $user = get_user_by('login', $username);

    if (!$user) {
        wp_send_json_error(['message' => 'Invalid username or password.']);
    }

    // check password
    if (!wp_check_password($password, $user->user_pass, $user->ID)) {
        wp_send_json_error(['message' => 'Invalid username or password.']);
    }

    wp_set_auth_cookie($user->ID, true);  // true means "remember this user".

    wp_send_json_success([
        'message' => 'Login successful.',
        'redirect' => home_url('/dashboard/')
    ]);
} else if ($data['action'] == 'forget') {
    if (!isset($data['data']['forget_nonce']) || !wp_verify_nonce($data['data']['forget_nonce'], 'forget_action')) {
        wp_send_json_error(['message' => 'Security check failed.']);
    }

    $email = $data['data']['email'];

    $user = get_user_by('email', $email);

    if (!$user) {
        wp_send_json_error(['email' => 'No account found with this email.']);
    }

    $email_verification = get_user_meta($user->ID, 'is_email_verified', true);

    if ($email_verification == 0) {
        wp_send_json_error(['email' => 'Your email is not verified.']);
    }

    if ($data['data']['username_or_password'] == 'username') {
        $result = sendMail($email, $user->user_nicename, $user->user_login, 'username');

        if ($result) {
            wp_send_json_error(['message' => "Your username has been sent to {$email}.", 'data' => $user]);
        } else {
            wp_send_json_error(['message' => 'Email failed to send.']);
        }
    } else if ($data['data']['username_or_password'] == 'password') {
        $reset_password_key = wp_rand(0, 9999999999);

        update_user_meta($user->ID, 'reset_password_key', $reset_password_key);

        $result = sendMail($email, $user->user_nicename, home_url('/reset-password?reset_password_key=' . $reset_password_key . '&id=' . $user->ID), 'password');

        if ($result) {
            wp_send_json_error(['message' => "Reset password link has been sent to {$email}."]);
        } else {
            wp_send_json_error(['message' => 'Email failed to send.']);
        }
    } else {
        wp_send_json_error(['message' => 'Wrong selection.']);
    }
} else if ($data['action'] == 'set-new-password') {
    if (!isset($data['data']['reset_nonce']) || !wp_verify_nonce($data['data']['reset_nonce'], 'reset_action')) {
        wp_send_json_error(['message' => 'Security check failed.']);
    }

    $reset_password_key = get_user_meta($data['data']['id'], 'reset_password_key', true);

    if ($reset_password_key == $data['data']['reset_password_key']) {
        $result = wp_update_user([
            'ID' => $data['data']['id'],
            'user_pass' => $data['data']['password']
        ]);

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => 'Password cannot be updated.']);
        } else {
            wp_send_json_success(['message' => 'Password is updated successfully.', 'redirect' => home_url('/login/')]);
        }
    } else {
        wp_send_json_error(['message' => 'Security check failed.']);
    }
} else if ($data['action'] == 'edit-profile') {
    $user = get_user_by('ID', $data['data']['id']);

    $isThereAnyError = false;
    $errorMessage = [];

    if (username_exists($data['data']['username']) && $data['data']['username'] != $user->user_login) {
        $isThereAnyError = true;
        $errorMessage['username'] = 'Username already exists.';
    }

    if (email_exists($data['data']['email']) && $data['data']['email'] != $user->user_email) {
        $isThereAnyError = true;
        $errorMessage['email'] = 'Email is already registered.';
    }

    if (!is_email($data['data']['email'])) {
        $isThereAnyError = true;
        $errorMessage['email'] = 'Invalid email address.';
    }

    if ($isThereAnyError) {
        wp_send_json_error($errorMessage);
    } else {
        $result = wp_update_user([
            'ID' => $user->ID,
            'user_login' => $data['data']['username'],
            'user_email' => $data['data']['email']
        ]);

        update_user_meta($user->ID, 'name', $data['data']['name']);
        update_user_meta($user->ID, 'is_email_verified', 0);

        if (is_wp_error($result)) {
            wp_send_json_error(['message' => 'Profile cannot be updated.']);
        } else {
            wp_send_json_success([
                'message' => 'Profile updated successfully.',
                'redirect' => home_url('/dashboard/')
            ]);
        }
    }
} else if ($data['action'] == 'delete-profile') {
    $result = wp_delete_user($data['data']['id']);

    if (is_wp_error($result)) {
        wp_send_json_error(['message' => 'Profile cannot be deleted.', 'result' => $result]);
    } else {
        wp_send_json_success([
            'message' => 'Profile deleted.',
            'redirect' => home_url('/')
        ]);
    }
} else {
    wp_send_json_error(['message' => 'unknown route.']);
}

function sendMail($email, $name, $data, $type)
{
    $subject = 'Get Your Username';

    if ($type == 'otp') {
        $subject = 'Verify Your Email';
    } else if ($type == 'password') {
        $subject = 'Reset Your Password';
    }

    $email_username = $type == 'otp' ? "Your email verification OTP is:<br/><br/><b style='font-size: large;'>{$data}</b></br><br/>This OTP will expire in 2 minutes." : "Your username is:<br/><br/><b style='font-size: large;'>{$data}</b></br><br/>";

    $line = $type == 'password' ? "Your password reset link is:<br/><br/><a href='{$data}'>Click Here</a></br><br/>" : "{$email_username}";
    $message = "
            Hello {$name},

            {$line}
        ";

    $headers = [
        'From: <iarshdeephans@gmail.com>',
        'Content-Type: text/html; charset=UTF-8'
    ];

    return wp_mail(
        $email,
        $subject,
        $message,
        $headers
    );
}
