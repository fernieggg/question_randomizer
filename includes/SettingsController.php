<?php

class QR_SettingsController {

    public function __construct() {
        add_action('admin_menu', [$this, 'register_settings_page']);
        add_action('admin_init', [$this, 'save_settings']);
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
        // Enqueue necessary scripts and styles
        wp_enqueue_style('qr_settings_css', plugins_url('/assets/css/settings.css', __FILE__));
        wp_enqueue_script('qr_settings_js', plugins_url('/assets/js/settings.js', __FILE__), ['jquery'], null, true);
    }

    public function render_settings_page() {
        $form_builder = get_option('qr_form_builder', '');
        $debug_mode = get_option('qr_debug_mode', false);
        ?>
        <div class="wrap">
            <h1>Question Randomizer Settings</h1>
            <form method="post" action="">
                <?php wp_nonce_field('qr_save_settings', 'qr_save_settings_nonce'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Form Builder</th>
                        <td>
                            <fieldset>
                                <label>
                                    <input type="radio" name="qr_form_builder" value="gravity_forms" <?php checked($form_builder, 'gravity_forms'); ?>>
                                    Gravity Forms
                                </label><br>
                                <label>
                                    <input type="radio" name="qr_form_builder" value="formidable_forms" <?php checked($form_builder, 'formidable_forms'); ?>>
                                    Formidable Forms
                                </label><br>
                                <label>
                                    <input type="radio" name="qr_form_builder" value="cf7" <?php checked($form_builder, 'cf7'); ?>>
                                    Contact Form 7
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Enable Debug Mode</th>
                        <td>
                            <fieldset>
                                <label>
                                    <input type="checkbox" name="qr_debug_mode" value="1" <?php checked($debug_mode, true); ?>>
                                    Enable Debugging
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                </table>
                <input type="submit" name="qr_save_settings" class="button button-primary" value="Save Settings">
            </form>
            <hr>
            <h2>Create Forms</h2>
            <form method="post" action="">
                <?php wp_nonce_field('qr_create_forms', 'qr_create_forms_nonce'); ?>
                <?php if ($form_builder === 'gravity_forms'): ?>
                    <input type="submit" name="qr_create_gravity_form" class="button button-primary" value="Create Gravity Form">
                <?php elseif ($form_builder === 'formidable_forms'): ?>
                    <input type="submit" name="qr_create_formidable_form" class="button button-primary" value="Create Formidable Form">
                <?php elseif ($form_builder === 'cf7'): ?>
                    <input type="submit" name="qr_create_cf7_form" class="button button-primary" value="Create Contact Form 7">
                <?php endif; ?>
            </form>
            <hr>
            <h2>Prepopulate Questions</h2>
            <form method="post" action="">
                <?php wp_nonce_field('qr_prepopulate_questions', 'qr_prepopulate_questions_nonce'); ?>
                <input type="submit" name="qr_prepopulate_questions" class="button button-secondary" value="Prepopulate Questions">
            </form>
        </div>
        <?php
    }

    public function save_settings() {
        if (isset($_POST['qr_save_settings']) && check_admin_referer('qr_save_settings', 'qr_save_settings_nonce')) {
            $previous_form_builder = get_option('qr_form_builder', '');

            if (isset($_POST['qr_form_builder'])) {
                update_option('qr_form_builder', sanitize_text_field($_POST['qr_form_builder']));
                $this->toggle_plugins($_POST['qr_form_builder'], $previous_form_builder);
            }

            $debug_mode = isset($_POST['qr_debug_mode']) ? true : false;
            update_option('qr_debug_mode', $debug_mode);

            // Update wp-config.php if necessary
            qr_update_debug_settings($debug_mode);

            set_transient('qr_settings_saved', true, 30);
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

    private function toggle_plugins($new_form_builder, $previous_form_builder) {
        if ($new_form_builder === 'gravity_forms' && $previous_form_builder !== 'gravity_forms') {
            // Deactivate Formidable Forms and CF7 + Flamingo
            if (is_plugin_active('formidable/formidable.php')) {
                deactivate_plugins('formidable/formidable.php');
            }
            if (is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
                deactivate_plugins('contact-form-7/wp-contact-form-7.php');
            }
            if (is_plugin_active('flamingo/flamingo.php')) {
                deactivate_plugins('flamingo/flamingo.php');
            }
            // Activate Gravity Forms
            if (!is_plugin_active('gravityforms/gravityforms.php')) {
                activate_plugin('gravityforms/gravityforms.php');
            }
        } elseif ($new_form_builder === 'formidable_forms' && $previous_form_builder !== 'formidable_forms') {
            // Deactivate Gravity Forms and CF7 + Flamingo
            if (is_plugin_active('gravityforms/gravityforms.php')) {
                deactivate_plugins('gravityforms/gravityforms.php');
            }
            if (is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
                deactivate_plugins('contact-form-7/wp-contact-form-7.php');
            }
            if (is_plugin_active('flamingo/flamingo.php')) {
                deactivate_plugins('flamingo/flamingo.php');
            }
            // Activate Formidable Forms
            if (!is_plugin_active('formidable/formidable.php')) {
                activate_plugin('formidable/formidable.php');
            }
        } elseif ($new_form_builder === 'cf7' && $previous_form_builder !== 'cf7') {
            // Deactivate Gravity Forms and Formidable Forms
            if (is_plugin_active('gravityforms/gravityforms.php')) {
                deactivate_plugins('gravityforms/gravityforms.php');
            }
            if (is_plugin_active('formidable/formidable.php')) {
                deactivate_plugins('formidable/formidable.php');
            }
            // Activate Contact Form 7 and Flamingo
            if (!is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
                activate_plugin('contact-form-7/wp-contact-form-7.php');
            }
            if (!is_plugin_active('flamingo/flamingo.php')) {
                activate_plugin('flamingo/flamingo.php');
            }
        }
    }
}

new QR_SettingsController();
?>
