<?php
/*
Plugin Name: Question Randomizer
Description: A plugin to display random questions using a shortcode and integrate with Gravity Forms or Contact Form 7 for capturing answers.
Version: 1.0
Author: Hobo Programming
*/

// Include necessary files
include_once plugin_dir_path(__FILE__) . 'includes/custom-post-type.php';
include_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';
include_once plugin_dir_path(__FILE__) . 'includes/settings-page.php';
include_once plugin_dir_path(__FILE__) . 'includes/prepopulate-questions.php';

// Check for Gravity Forms dependency
function qr_check_for_gravity_forms() {
    if (!is_plugin_active('gravityforms/gravityforms.php') && !is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
        deactivate_plugins(plugin_basename(__FILE__));
        add_action('admin_notices', function() {
            echo '<div class="error"><p>Question Randomizer requires Gravity Forms or Contact Form 7. Please install and activate one of these plugins first.</p></div>';
        });
    }
}
add_action('admin_init', 'qr_check_for_gravity_forms');
?>
