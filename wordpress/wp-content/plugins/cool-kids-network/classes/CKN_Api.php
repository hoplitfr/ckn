<?php

class CKN_Api
{
    // Define your API key for validation
    private static $valid_api_key = '123456789';

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
