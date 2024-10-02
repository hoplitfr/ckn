<?php

class CKN_Api
{
    // Define your API key for validation
    private static $valid_api_key = '123456789';

    /**
     * Update the role of a user via a REST API request, authenticated by an API key.
     *
     * @param WP_REST_Request $request The request object containing user info and the new role.
     * @return WP_REST_Response The response indicating success or failure.
     */
    public static function update_user_role_via_api(WP_REST_Request $request): WP_REST_Response
    {
        // Verify the API key from the request
        $api_key = sanitize_text_field($request->get_param('api_key'));
        if (!self::is_valid_api_key($api_key)) {
            return new WP_REST_Response('Invalid API key', 403);
        }

        // Retrieve user by email or first name + last name
        $email = sanitize_email($request->get_param('email'));
        $first_name = sanitize_text_field($request->get_param('first_name'));
        $last_name = sanitize_text_field($request->get_param('last_name'));

        $user = null;

        if ($email) {
            $user = get_user_by('email', $email);
        } elseif ($first_name && $last_name) {
            $user = self::get_user_by_name($first_name, $last_name);
        }

        if (!$user) {
            return new WP_REST_Response('User not found', 404);
        }

        // Get the new role from the request
        $new_role = sanitize_text_field($request->get_param('role'));

        // Validate the new role
        $valid_roles = ['cool_kid', 'cooler_kid', 'coolest_kid'];
        if (in_array($new_role, $valid_roles, true)) {
            // Update user role if valid
            $user->set_role($new_role);
            return new WP_REST_Response('Role updated successfully', 200);
        }

        return new WP_REST_Response('Invalid role provided', 400);
    }

    /**
     * Check if the provided API key is valid.
     *
     * @param string $api_key The API key to validate.
     * @return bool True if the API key is valid, false otherwise.
     */
    private static function is_valid_api_key(string $api_key): bool
    {
        return hash_equals(self::$valid_api_key, $api_key);
    }

    /**
     * Retrieve a user by their first name and last name.
     *
     * @param string $first_name The user's first name.
     * @param string $last_name The user's last name.
     * @return WP_User|null The user object if found, or null if not found.
     */
    private static function get_user_by_name(string $first_name, string $last_name): ?WP_User
    {
        $args = array(
            'meta_query' => array(
                array(
                    'key' => 'first_name',
                    'value' => $first_name,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'last_name',
                    'value' => $last_name,
                    'compare' => 'LIKE',
                ),
            ),
        );

        $user_query = new WP_User_Query($args);
        $users = $user_query->get_results();

        // Return the first matched user if available
        return !empty($users) ? $users[0] : null;
    }

    /**
     * Register the REST API route for updating user roles.
     */
    public static function register_role_update_route(): void
    {
        register_rest_route('coolkids/v1', '/update-role/', array(
            'methods' => 'POST',
            'callback' => [self::class, 'update_user_role_via_api'],
            'permission_callback' => '__return_true', // No need for logged-in user, we handle security with the API key
        ));
    }
}

// Hook to initialize the custom REST route
add_action('rest_api_init', [CKN_Api::class, 'register_role_update_route']);
