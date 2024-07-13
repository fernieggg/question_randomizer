<?php
// Update debugging settings
function qr_update_debug_settings($enable) {
    $config_path = ABSPATH . 'wp-config.php';
    $status_message = '';

    error_log('[QR Plugin] qr_update_debug_settings called with enable = ' . ($enable ? 'true' : 'false'));

    if (!file_exists($config_path)) {
        error_log('[QR Plugin] wp-config.php does not exist at: ' . $config_path);
        $status_message = 'wp-config.php does not exist at: ' . $config_path;
    } elseif (!is_writable($config_path)) {
        error_log('[QR Plugin] wp-config.php is not writable at: ' . $config_path);
        $status_message = 'wp-config.php is not writable at: ' . $config_path;
    } else {
        $config_content = file_get_contents($config_path);
        if ($config_content === false) {
            error_log('[QR Plugin] Failed to read wp-config.php at: ' . $config_path);
            $status_message = 'Failed to read wp-config.php at: ' . $config_path;
        } else {
            $debug_code = "
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);
";

            if ($enable) {
                if (strpos($config_content, "define('WP_DEBUG',") === false) {
                    // Append debug settings if not found
                    $config_content = str_replace("/* That's all, stop editing! Happy blogging. */", $debug_code . "\n/* That's all, stop editing! Happy blogging. */", $config_content);
                    error_log('[QR Plugin] Appending debug settings to wp-config.php');
                } else {
                    // Update existing debug settings
                    $config_content = preg_replace("/define\('WP_DEBUG',.*\);/", "define('WP_DEBUG', true);", $config_content);
                    $config_content = preg_replace("/define\('WP_DEBUG_LOG',.*\);/", "define('WP_DEBUG_LOG', true);", $config_content);
                    $config_content = preg_replace("/define\('WP_DEBUG_DISPLAY',.*\);/", "define('WP_DEBUG_DISPLAY', false);", $config_content);
                    $config_content = preg_replace("/@ini_set\('display_errors',.*\);/", "@ini_set('display_errors', 0);", $config_content);
                    error_log('[QR Plugin] Updating existing debug settings in wp-config.php');
                }
            } else {
                // Disable debug settings
                $config_content = preg_replace("/define\('WP_DEBUG',.*\);/", "define('WP_DEBUG', false);", $config_content);
                $config_content = preg_replace("/define\('WP_DEBUG_LOG',.*\);/", "define('WP_DEBUG_LOG', false);", $config_content);
                $config_content = preg_replace("/define\('WP_DEBUG_DISPLAY',.*\);/", "define('WP_DEBUG_DISPLAY', true);", $config_content);
                $config_content = preg_replace("/@ini_set\('display_errors',.*\);/", "@ini_set('display_errors', 1);", $config_content);
                error_log('[QR Plugin] Disabling debug settings in wp-config.php');
            }

            $result = file_put_contents($config_path, $config_content);
            if ($result === false) {
                error_log('[QR Plugin] Failed to write to wp-config.php at: ' . $config_path);
                $status_message = 'Failed to write to wp-config.php at: ' . $config_path;
            } else {
                error_log('[QR Plugin] Successfully updated wp-config.php at: ' . $config_path);
                $status_message = 'Successfully updated wp-config.php at: ' . $config_path;
            }
        }
    }

    // Set the status message as a transient to be retrieved and displayed in the admin notice
    set_transient('qr_debug_status_message', $status_message, 30);
}
?>
