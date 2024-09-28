<?php

/**
 * Plugin Name: Cool Kids Network
 * Description: A Wordpress plugin to manage Cool Kids users
 * Version: 1.0
 * Author: Hoplitfr
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('COOL_KIDS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('COOL_KIDS_PLUGIN_URL', plugin_dir_url(__FILE__));

require COOL_KIDS_PLUGIN_PATH . 'classes/CoolKidsNetwork.php';
