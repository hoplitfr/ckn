<?php

class CKN_User_Display
{
    /**
     * Display informations on user interface depending on the user role.
     *
     * @return string HTML with user data matching the role of the current user.
     */
    public static function user_interface(): string
    {
        if (!is_user_logged_in()) {
            return "You can't access this without logging in.";
        }

        // Get the current user
        $current_user = wp_get_current_user();

        // Check if the user has the role 'Cool Kid'
        if (in_array('cool_kid', (array) $current_user->roles)) {
            return self::render_user_info_table($current_user);
        } else {
            // Return nothing if the user is not a 'Cool Kid'
            return '<p>You are already logged in, but no data is available for your role.</p>';
        }
    }

    /**
     * Render a table with the logged-in user's information if their role is 'Cool Kid'.
     *
     * @return string The HTML table with user data or an empty string if the role does not match.
     */
    public static function render_user_info_table(): string
    {
        // Get the current user
        $current_user = wp_get_current_user();
        // Retrieve user meta data
        $first_name = get_user_meta($current_user->ID, 'first_name', true);
        $last_name = get_user_meta($current_user->ID, 'last_name', true);
        $country = get_user_meta($current_user->ID, 'country', true);
        $role = implode(', ', $current_user->roles);
        $email = $current_user->user_email;

        // Output the data in an HTML table
        $output = '<table class="cool_kid_table">';
        $output .= '<tr><th>First Name</th><td>' . esc_html($first_name) . '</td></tr>';
        $output .= '<tr><th>Last Name</th><td>' . esc_html($last_name) . '</td></tr>';
        $output .= '<tr><th>Role</th><td>' . esc_html($role) . '</td></tr>';
        $output .= '<tr><th>Email</th><td>' . esc_html($email) . '</td></tr>';
        $output .= '<tr><th>Country</th><td>' . esc_html($country) . '</td></tr>';
        $output .= '</table>';

        return $output;
    }
}
