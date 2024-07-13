<?php
// Shortcode to display random question with dynamic form ID
function qr_display_random_question($atts) {
    $form_builder = get_option('qr_form_builder', '');

    if ($form_builder === '') {
        error_log('[QR Plugin] Form builder not selected.');
        return '<p>Please select a form builder in the plugin settings.</p>';
    }

    if (($form_builder === 'gravity_forms' && !qr_is_gravity_forms_active()) || ($form_builder === 'formidable_forms' && !qr_is_formidable_forms_active())) {
        error_log('[QR Plugin] Selected form builder plugin is not installed or activated.');
        return '<p>The selected form builder plugin is not installed or activated. Please install and activate the plugin.</p>';
    }

    $atts = shortcode_atts(['form_id' => 1], $atts, 'random_question');
    $query_args = [
        'post_type' => 'questions',
        'posts_per_page' => 1,
        'orderby' => 'rand'
    ];

    $query = new WP_Query($query_args);

    if ($query->have_posts()) {
        $query->the_post();
        $question = get_the_title();
        wp_reset_postdata();

        // Store the question in a transient to retrieve it later
        set_transient('qr_current_question', $question, 60 * 60); // 1 hour expiration
        error_log('[QR Plugin] Selected question: ' . $question);

        // Embed the appropriate form
        $form_id = intval($atts['form_id']);
        if ($form_builder === 'gravity_forms') {
            $gravity_form = gravity_form($form_id, false, false, false, '', true, 1, false);
            $output = '<div class="qr-question">' . esc_html($question) . '</div><div class="qr-form">' . $gravity_form . '</div>';
        } else if ($form_builder === 'formidable_forms') {
            $formidable_form = FrmFormsController::get_form_shortcode(array('id' => $form_id));
            $output = '<div class="qr-question">' . esc_html($question) . '</div><div class="qr-form">' . $formidable_form . '</div>';
        }

        return $output;
    } else {
        error_log('[QR Plugin] No questions found.');
        return '<p>No questions found.</p>';
    }
}
add_shortcode('random_question', 'qr_display_random_question');

// Populate hidden field with selected question (for Gravity Forms)
add_filter('gform_field_value_question', 'qr_populate_question_field');
function qr_populate_question_field($value) {
    $question = get_transient('qr_current_question');
    error_log('[QR Plugin] Gravity Forms: Retrieved question from transient: ' . $question); // Debugging
    if ($question) {
        return $question;
    }
    return $value;
}

// Populate hidden field with selected question (for Formidable Forms)
add_filter('frm_get_default_value', 'qr_populate_formidable_field', 10, 2);
function qr_populate_formidable_field($new_value, $field) {
    error_log('[QR Plugin] Formidable Forms: frm_get_default_value called for field: ' . $field->field_key);
    if ($field->id == 8) {  // Adjust this to match your Formidable Forms field ID
        $question = get_transient('qr_current_question');
        if ($question) {
            error_log('[QR Plugin] Formidable Forms: Populating hidden field with question: ' . $question);
            return $question;
        }
        error_log('[QR Plugin] Formidable Forms: No question found in transient.');
    }
    return $new_value;
}



?>
