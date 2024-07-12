<?php
// Shortcode to display random question with dynamic form ID
function qr_display_random_question($atts) {
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

        // Determine the form builder to use
        $form_builder = get_option('qr_form_builder', 'gravity_forms');

        // Embed the appropriate form
        $form_id = intval($atts['form_id']);
        if ($form_builder === 'gravity_forms') {
            $gravity_form = gravity_form($form_id, false, false, false, '', true, 1, false);
            $output = '<div class="qr-question">' . esc_html($question) . '</div><div class="qr-form">' . $gravity_form . '</div>';
        } else if ($form_builder === 'contact_form_7') {
            $contact_form = do_shortcode('[contact-form-7 id="' . $form_id . '"]');
            $output = '<div class="qr-question">' . esc_html($question) . '</div><div class="qr-form">' . $contact_form . '</div>';
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

// Populate hidden field with selected question (for Contact Form 7)
add_filter('wpcf7_form_hidden_fields', 'qr_populate_contact_form_7_field');
function qr_populate_contact_form_7_field($hidden_fields) {
    $question = get_transient('qr_current_question');
    if ($question) {
        $hidden_fields['question'] = $question;
    }
    return $hidden_fields;
}
?>
