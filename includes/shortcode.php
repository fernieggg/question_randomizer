<?php
// Shortcode to display random question with dynamic form ID
function qr_display_random_question($atts) {
    $form_builder = get_option('qr_form_builder', '');

    if ($form_builder === '') {
        return '<p>Please select a form builder in the plugin settings.</p>';
    }

    if (($form_builder === 'gravity_forms' && !qr_is_gravity_forms_active()) || 
        ($form_builder === 'formidable_forms' && !qr_is_formidable_forms_active()) ||
        ($form_builder === 'cf7' && !qr_is_cf7_active())) {
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
        
        // Check if debugging is enabled
if (defined('WP_DEBUG') && WP_DEBUG) {
    // Output a JavaScript console message to indicate the transient was set
    echo "<script>console.log('Transient qr_current_question set with value: " . esc_js($question) . "');</script>";
}


        // Embed the appropriate form
        $form_id = intval($atts['form_id']);
        if ($form_builder === 'gravity_forms') {
            $gravity_form = gravity_form($form_id, false, false, false, '', true, 1, false);
            $output = '<div class="qr-question">' . esc_html($question) . '</div><div class="qr-form">' . $gravity_form . '</div>';
        } elseif ($form_builder === 'formidable_forms') {
            $formidable_form = FrmFormsController::get_form_shortcode(array('id' => $form_id));
            $output = '<div class="qr-question">' . esc_html($question) . '</div><div class="qr-form">' . $formidable_form . '</div>';
        } elseif ($form_builder === 'cf7') {
            $cf7_form = do_shortcode('[contact-form-7 id="' . $form_id . '"]');
            $output = '<div class="qr-question">' . esc_html($question) . '</div><div class="qr-form">' . $cf7_form . '</div>';
        }

        return $output;
    } else {
        return '<p>No questions found.</p>';
    }
}
add_shortcode('random_question', 'qr_display_random_question');

// Populate hidden field with selected question (for Gravity Forms)
add_filter('gform_field_value_question', 'qr_populate_question_field');
function qr_populate_question_field($value) {
    $question = get_transient('qr_current_question');
    if ($question) {
        return $question;
    }
    return $value;
}


add_filter('frm_get_default_value', 'qr_populate_formidable_field', 10, 2);

function qr_populate_formidable_field($new_value, $field) {
    // Log whenever the function is called
    error_log("frm_get_default_value called for field: " . $field->field_key);
    // Check if the field key is 'field_question'
    if ($field->field_key === 'question') { // Use the field key to identify the correct field
        $question = get_transient('qr_current_question'); // Retrieve the question stored in the transient
        error_log("Retrieved question from transient: " . $question); // Debugging
        if ($question) {
            return $question; // Return the question to populate the field
        }
    }

    return $new_value; // Return the original value if the field key does not match
}




// Populate hidden field with selected question (for CF7)
add_filter('wpcf7_form_tag', 'qr_populate_cf7_hidden_field', 10, 2);
function qr_populate_cf7_hidden_field($tag) {
    if ($tag['name'] === 'question') {
        $question = get_transient('qr_current_question');
        error_log("Retrieved question from transient: " . $question); // Debugging
        if ($question) {
            $tag['values'] = array($question);
        }
    }
    return $tag;
}
?>
