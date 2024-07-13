<?php
// Save the settings
function qr_save_settings() {
    if (isset($_POST['qr_save_settings']) && check_admin_referer('qr_save_settings', 'qr_save_settings_nonce')) {
        if (isset($_POST['qr_form_builder'])) {
            update_option('qr_form_builder', sanitize_text_field($_POST['qr_form_builder']));
        }
        $debug_mode = isset($_POST['qr_debug_mode']) ? true : false;
        update_option('qr_debug_mode', $debug_mode);

        qr_update_debug_settings($debug_mode);

        set_transient('qr_settings_saved', true, 30);
    }
}
add_action('admin_init', 'qr_save_settings');
?>
