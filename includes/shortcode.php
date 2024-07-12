<?php
// Shortcode to display random question with dynamic Gravity Form ID
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
        set_transient('qr_current_question', $question, 60*60); // 1 hour expiration

        // Embed Gravity Form with dynamic form ID
        $form_id = intval($atts['form_id']);
        $gravity_form = gravity_form($form_id, false, false, false, '', true, 1, false);

        return '<div class="qr-question">' . esc_html($question) . '</div><div class="qr-form">' . $gravity_form . '</div>';
    } else {
        return '<p>No questions found.</p>';
    }
}
add_shortcode('random_question', 'qr_display_random_question');

// Populate hidden field with selected question
add_filter('gform_field_value_question', 'qr_populate_question_field');
function qr_populate_question_field($value) {
    $question = get_transient('qr_current_question');
    if ($question) {
        return $question;
    }
    return $value;
}
?>
