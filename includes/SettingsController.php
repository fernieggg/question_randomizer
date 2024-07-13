<?php

class QR_SettingsController {

    public function __construct() {
        add_action('admin_menu', [$this, 'register_settings_page']);
        add_action('admin_enqueue_scripts', [$this, 'load_assets']);
        add_action('admin_init', [$this, 'handle_form_submissions']);
    }

    public function register_settings_page() {
        // Add top-level menu for settings
        add_menu_page(
            'Question Randomizer', // Page title
            'Question Randomizer', // Menu title
            'manage_options',      // Capability
            'qr-settings',         // Menu slug
            [$this, 'render_settings_page'],  // Callback function
            'dashicons-admin-settings', // Icon URL
            6                      // Position
        );
    }

    public function load_assets() {
        wp_enqueue_style('tailwindcss', 'https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css');
    }

    public function render_settings_page() {
        include_once(plugin_dir_path(__FILE__) . 'settings-form.php');
    }

    public function handle_form_submissions() {
        if (isset($_POST['qr_save_settings']) && check_admin_referer('qr_save_settings', 'qr_save_settings_nonce')) {
            $previous_form_builder = get_option('qr_form_builder', '');

            if (isset($_POST['qr_form_builder'])) {
                update_option('qr_form_builder', sanitize_text_field($_POST['qr_form_builder']));
                qr_toggle_plugins($_POST['qr_form_builder'], $previous_form_builder);
            }

            $debug_mode = isset($_POST['qr_debug_mode']) ? true : false;
            update_option('qr_debug_mode', $debug_mode);

            // Update wp-config.php if necessary
            qr_update_debug_settings($debug_mode);

            set_transient('qr_settings_saved', true, 30);

            // Add an admin notice for successful settings save
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible"><p>Settings saved successfully.</p></div>';
            });
        }

        if (isset($_POST['qr_create_gravity_form']) && check_admin_referer('qr_create_forms', 'qr_create_forms_nonce')) {
            qr_handle_create_gravity_form();
        }

        if (isset($_POST['qr_create_formidable_form']) && check_admin_referer('qr_create_forms', 'qr_create_forms_nonce')) {
            qr_handle_create_formidable_form();
        }

        if (isset($_POST['qr_create_cf7_form']) && check_admin_referer('qr_create_forms', 'qr_create_forms_nonce')) {
            qr_handle_create_cf7_form();
        }

        if (isset($_POST['qr_prepopulate_questions']) && check_admin_referer('qr_prepopulate_questions', 'qr_prepopulate_questions_nonce')) {
            qr_handle_prepopulate_questions();
        }
    }
}

new QR_SettingsController();
?>
