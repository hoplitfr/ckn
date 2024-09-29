<?php

class CoolKidsNetwork
{
    public function __construct()
    {

        register_activation_hook(COOL_KIDS_PLUGIN_PATH . 'index.php', array($this, 'activate_plugin'));

        register_deactivation_hook(COOL_KIDS_PLUGIN_PATH . 'index.php', array($this, 'deactivate_plugin'));

        add_action('init', array($this, 'register_shortcode'));

        add_action('init', array($this, 'handle_signup_form_submission'));

        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));

    }

    /**
     * Create custom roles when activating the plugin.
     *
     * @return void
     */
    public function activate_plugin(): void
    {
        add_role('cool_kid', 'Cool Kid', array('read' => true));
        add_role('cooler_kid', 'Cooler Kid', array('read' => true));
        add_role('coolest_kid', 'Coolest Kid', array('read' => true));
    }

    /**
     * Delete custom roles when deactivating the plugin.
     *
     * @return void
     */
    public function deactivate_plugin(): void
    {
        remove_role('cool_kid');
        remove_role('cooler_kid');
        remove_role('coolest_kid');
    }

    /**
     * Enqueue custom styles for the plugin.
     * Loads the CSS files required.
     *
     * @return void
     */
    public function enqueue_styles(): void
    {
        wp_enqueue_style('coolkids-style', COOL_KIDS_PLUGIN_URL . 'css/form.css');
    }

    /**
     * Register the "signin_signup_form" shortcode.
     * This shortcode renders the login button and signup form.
     *
     * @return void
     */
    public function register_shortcode(): void
    {
        add_shortcode('signin_signup_form', array($this, 'render_signin_signup_form'));
    }

    /**
     * Render the sign-in and sign-up forms for the shortcode.
     * Displays a login button for existing users and a sign-up form for new users.
     * If the user is logged in, a message is shown instead.
     *
     * @return string The HTML output of the forms.
     */
    public function render_signin_signup_form(): string
    {

        if (is_user_logged_in()) {
            return '<p>You are already logged in.</p>';
        }

        ob_start();

        echo '<div class="ckn_forms">
        <div class="ckn_loginform">
                <p>You already have an account:</p>
                <a href="' . wp_login_url() . '" class="button" id="ckn_button" >Sign In</a>
              </div>
              <div class="ckn_signupform">
                <form method="post">
                    <label for="signup_email">Sign up with your email address:</label><br>
                    <input type="email" id="signup_email" name="signup_email" required><br>
                    <input type="submit" name="signup_submit" value="Sign Up" id="ckn_sign_up_submit">
                </form>
              </div>
              </div>';

        // Display error or success after submit
        if (isset($_POST['signup_submit'])) {
            $this->handle_signup_form_submission();
        }

        return ob_get_clean();
    }

    /**
     * Handle the "Sign Up" form submission.
     * Checks if the email is already registered, creates a new user, and assigns a role.
     * If an error occurs, a message is displayed, else character's data are generated using randomuser.me.
     *
     * @return void
     */
    public function handle_signup_form_submission(): void
    {
        if (isset($_POST['signup_submit'])) {
            $email = sanitize_email($_POST['signup_email']);

            // Check if email already exist
            if (email_exists($email)) {
                echo '<p style="color: red;">This email is already registered. Please use another email or log in.</p>';
                unset($_POST['signup_submit']); // Empty form before returning results to avoid double alert
                return;
            } else {
                // Random pwd generator
                // $password = wp_generate_password();
                $password = 'test';

                // Create a new user with "Cool Kid" role
                $user_id = wp_create_user($email, $password, $email);

                if (!is_wp_error($user_id)) {
                    // Update user role to "Cool Kid"
                    wp_update_user(array('ID' => $user_id, 'role' => 'cool_kid'));

                    // Generate character data using the RandomUser API
                    $character_data = $this->generate_random_character();

                    if ($character_data) {
                        // Save character data in user meta
                        update_user_meta($user_id, 'first_name', $character_data['first_name']);
                        update_user_meta($user_id, 'last_name', $character_data['last_name']);
                        update_user_meta($user_id, 'country', $character_data['country']);
                    } else {
                        // Display an error if data parsing fails
                        echo '<p style="color: red;">There was an error creating your character. Please try again.</p>';
                        return;
                    }

                    // Send mail to user with pwd
                    wp_mail($email, 'Welcome to Cool Kids Network', 'Your account has been created. Your password is: ' . $password);

                    echo '<p style="color: green;">Your account has been created! Please check your email for your password.</p>';
                    unset($_POST['signup_submit']); // Empty form before returning results to avoid double alert
                    return;
                } else {
                    echo '<p style="color: red;">There was an error creating your account. Please try again.</p>';
                    return;
                }
            }
        }
    }

    /**
     * Generate random character data using the RandomUser.me API.
     *
     * @return array|false Returns an array of character data or false if API call fails.
     */
    public function generate_random_character(): bool|array
    {
        $response = wp_remote_get('https://randomuser.me/api/');

        if (is_wp_error($response)) {
            return false; // Return false if the API request fails
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!empty($data['results'][0])) {
            $character = $data['results'][0];
            return array(
                'first_name' => ucfirst($character['name']['first']),
                'last_name' => ucfirst($character['name']['last']),
                'country' => $character['location']['country'],
            );
        }

        return false; // Return false if data parsing fails
    }
}

if (class_exists('CoolKidsNetwork')) {
    $coolKidsNetwork = new CoolKidsNetwork();
}
