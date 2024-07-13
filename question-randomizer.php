<?php
/*
Plugin Name: Question Randomizer
Description: Display random questions using Gravity Forms, Formidable Forms, or Contact Form 7 for capturing answers.
Version: 1.0
Author: Hobo Programming
*/

// Include necessary files
require_once(plugin_dir_path(__FILE__) . 'includes/tgmpa/class-tgm-plugin-activation.php');
require_once(plugin_dir_path(__FILE__) . 'includes/tgmpa-config.php');
require_once(plugin_dir_path(__FILE__) . 'includes/SettingsController.php');
require_once(plugin_dir_path(__FILE__) . 'includes/utility-functions.php');
require_once(plugin_dir_path(__FILE__) . 'includes/admin-notices.php');
require_once(plugin_dir_path(__FILE__) . 'includes/shortcode.php');
require_once(plugin_dir_path(__FILE__) . 'includes/prepopulate-questions.php');
require_once(plugin_dir_path(__FILE__) . 'includes/create-gravity-form.php');
require_once(plugin_dir_path(__FILE__) . 'includes/create-formidable-form.php');
require_once(plugin_dir_path(__FILE__) . 'includes/create-cf7-form.php');
require_once(plugin_dir_path(__FILE__) . 'includes/custom-post-type.php');

// Enqueue Tailwind CSS and Alpine.js
function qr_enqueue_assets() {
    wp_enqueue_style('tailwindcss', 'https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css');
    wp_enqueue_script('alpinejs', 'https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.4.2/cdn.min.js', [], null, true);
}
add_action('admin_enqueue_scripts', 'qr_enqueue_assets');

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

// Check if Contact Form 7 is active
function qr_is_cf7_active() {
    return class_exists('WPCF7_ContactForm');
}

// Check if Flamingo is active
function qr_is_flamingo_active() {
    return class_exists('Flamingo_Inbound');
}
?>
