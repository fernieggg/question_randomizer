<?php

function qr_toggle_plugins($new_form_builder, $previous_form_builder) {
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

function qr_update_debug_settings($debug_mode) {
    $wp_config_file = ABSPATH . 'wp-config.php';
    
    if (!is_writable($wp_config_file)) {
        error_log('wp-config.php is not writable');
        return;
    }

    $config_contents = file_get_contents($wp_config_file);
    
    if ($debug_mode) {
        $config_contents = preg_replace(
            "/define\(\s*'WP_DEBUG',\s*false\s*\);/",
            "define('WP_DEBUG', true);",
            $config_contents
        );
        $config_contents = preg_replace(
            "/define\(\s*'WP_DEBUG_LOG',\s*false\s*\);/",
            "define('WP_DEBUG_LOG', true);",
            $config_contents
        );
        $config_contents = preg_replace(
            "/define\(\s*'WP_DEBUG_DISPLAY',\s*true\s*\);/",
            "define('WP_DEBUG_DISPLAY', false);",
            $config_contents
        );
    } else {
        $config_contents = preg_replace(
            "/define\(\s*'WP_DEBUG',\s*true\s*\);/",
            "define('WP_DEBUG', false);",
            $config_contents
        );
        $config_contents = preg_replace(
            "/define\(\s*'WP_DEBUG_LOG',\s*true\s*\);/",
            "define('WP_DEBUG_LOG', false);",
            $config_contents
        );
        $config_contents = preg_replace(
            "/define\(\s*'WP_DEBUG_DISPLAY',\s*false\s*\);/",
            "define('WP_DEBUG_DISPLAY', true);",
            $config_contents
        );
    }

    file_put_contents($wp_config_file, $config_contents);
}
?>
