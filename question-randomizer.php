<?php
/*
Plugin Name: Question Randomizer
Description: A plugin to display random questions using a shortcode and integrate with Gravity Forms for capturing answers.
Version: 1.0
Author: Hobo Programming
*/

// Include necessary files
include_once plugin_dir_path(__FILE__) . 'includes/custom-post-type.php';
include_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';
include_once plugin_dir_path(__FILE__) . 'includes/settings-page.php';

// Check for Gravity Forms dependency
function qr_check_for_gravity_forms() {
    if (!is_plugin_active('gravityforms/gravityforms.php')) {
        deactivate_plugins(plugin_basename(__FILE__));
        add_action('admin_notices', function() {
            echo '<div class="error"><p>Question Randomizer requires Gravity Forms. Please install and activate Gravity Forms first.</p></div>';
        });
    }
}
add_action('admin_init', 'qr_check_for_gravity_forms');
