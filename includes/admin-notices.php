<?php
// Display admin notices, including debug status message
function qr_admin_notices() {
    if (get_transient('qr_settings_saved')) {
        echo '<div class="notice notice-success"><p>Settings saved successfully.</p></div>';
        delete_transient('qr_settings_saved');
    }

    $status_message = get_transient('qr_debug_status_message');
    if ($status_message) {
        echo '<div class="notice notice-success"><p>' . $status_message . '</p></div>';
        delete_transient('qr_debug_status_message');
    }

    $form_builder = get_option('qr_form_builder', '');
    if ($form_builder === 'gravity_forms' && !qr_is_gravity_forms_active()) {
        echo '<div class="notice notice-error"><p>Gravity Forms is not installed or activated. Please install and activate Gravity Forms to use this plugin.</p></div>';
    } elseif ($form_builder === 'formidable_forms' && !qr_is_formidable_forms_active()) {
        echo '<div class="notice notice-error"><p>Formidable Forms is not installed or activated. Please install and activate Formidable Forms to use this plugin.</p></div>';
    }
}
add_action('admin_notices', 'qr_admin_notices');
?>
