<?php

add_action('wp_ajax_qr_toggle_debug_mode', 'qr_toggle_debug_mode');
function qr_toggle_debug_mode() {
    check_ajax_referer('qr_toggle_debug_mode');

    if (isset($_POST['qr_debug_mode'])) {
        $debug_mode = $_POST['qr_debug_mode'] === '1';
        update_option('qr_debug_mode', $debug_mode);
        wp_send_json_success('Debug mode updated successfully');
    } else {
        wp_send_json_error('Invalid request');
    }
}
?>
