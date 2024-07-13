<?php
/*
Plugin Name: Question Randomizer
Description: Display random questions using Gravity Forms or Formidable Forms.
Version: 1.0
Author: Hobo Programming
*/

// Include necessary files
require_once(plugin_dir_path(__FILE__) . 'includes/settings-page.php');
require_once(plugin_dir_path(__FILE__) . 'includes/save-settings.php');
require_once(plugin_dir_path(__FILE__) . 'includes/update-config.php');
require_once(plugin_dir_path(__FILE__) . 'includes/admin-notices.php');
require_once(plugin_dir_path(__FILE__) . 'includes/shortcode.php');
require_once(plugin_dir_path(__FILE__) . 'includes/prepopulate-questions.php');
require_once(plugin_dir_path(__FILE__) . 'includes/create-gravity-form.php');
require_once(plugin_dir_path(__FILE__) . 'includes/create-formidable-form.php');
require_once(plugin_dir_path(__FILE__) . 'includes/custom-post-type.php');

// Plugin activation hook
register_activation_hook(__FILE__, 'qr_activate_plugin');
function qr_activate_plugin() {
    // Add default options
    add_option('qr_form_builder', '');
    add_option('qr_debug_mode', false);
}

// Plugin deactivation hook
register_deactivation_hook(__FILE__, 'qr_deactivate_plugin');
function qr_deactivate_plugin() {
    // Clean up options
    delete_option('qr_form_builder');
    delete_option('qr_debug_mode');
}

// Check if Gravity Forms is active
function qr_is_gravity_forms_active() {
    return class_exists('GFAPI');
}

// Check if Formidable Forms is active
function qr_is_formidable_forms_active() {
    return class_exists('FrmForm');
}
?>
