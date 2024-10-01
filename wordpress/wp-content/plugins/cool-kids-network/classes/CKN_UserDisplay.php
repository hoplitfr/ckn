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

        // Check the user's role and display the appropriate data
        if (in_array('cool_kid', (array) $current_user->roles)) {
            return self::render_user_info_table($current_user);
        } elseif (in_array('cooler_kid', (array) $current_user->roles)) {
            $user_info = self::render_user_info_table($current_user);
            $render_cooler_tab = self::render_user_list(false); // Don't show email and role
            return $user_info . $render_cooler_tab;
        } elseif (in_array('coolest_kid', (array) $current_user->roles)) {
            $user_info = self::render_user_info_table($current_user);
            $render_coolest_tab = self::render_user_list(true); // Show email and role
            return $user_info . $render_coolest_tab;
        } else {
            // Return nothing if the user is not a 'Cool(er)(est) Kid'
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
        $output = '<h2>Character Data</h2>';
        $output .= '<table class="cool_kid_table">';
        $output .= '<tr><th>First Name</th><td>' . esc_html($first_name) . '</td></tr>';
        $output .= '<tr><th>Last Name</th><td>' . esc_html($last_name) . '</td></tr>';
        $output .= '<tr><th>Role</th><td>' . esc_html($role) . '</td></tr>';
        $output .= '<tr><th>Email</th><td>' . esc_html($email) . '</td></tr>';
        $output .= '<tr><th>Country</th><td>' . esc_html($country) . '</td></tr>';
        $output .= '</table>';

        return $output;
    }

    /**
     * Render a list of users with their basic info for Cooler Kid and Coolest Kid roles.
     *
     * @param bool $show_email_and_role Whether to display email and role information.
     * @return string The HTML table with the list of users.
     */
    public static function render_user_list(bool $show_email_and_role): string
    {
        // Get users with roles 'cool_kid', 'cooler_kid', or 'coolest_kid'
        $args = array(
            'role__in' => array('cool_kid', 'cooler_kid', 'coolest_kid') // Filter by specific roles
        );
        // Get all users
        $users = get_users($args);

        $output = '<h2>Users List</h2>';
        $output .= '<table class="cool_kid_userlist">';
        $output .= '<tr><th>First Name</th><th>Last Name</th><th>Country</th>';
        if ($show_email_and_role) {
            $output .= '<th>Email</th><th>Role</th>';
        }
        $output .= '</tr>';

        // Loop through all users and display their information
        foreach ($users as $user) {
            $first_name = get_user_meta($user->ID, 'first_name', true);
            $last_name = get_user_meta($user->ID, 'last_name', true);
            $country = get_user_meta($user->ID, 'country', true);
            $role = implode(', ', $user->roles);
            $email = $user->user_email;

            $output .= '<tr>';
            $output .= '<td>' . esc_html($first_name) . '</td>';
            $output .= '<td>' . esc_html($last_name) . '</td>';
            $output .= '<td>' . esc_html($country) . '</td>';
            if ($show_email_and_role) {
                $output .= '<td>' . esc_html($email) . '</td>';
                $output .= '<td>' . esc_html($role) . '</td>';
            }
            $output .= '</tr>';
        }

        $output .= '</table>';

        return $output;
    }
}
