<?php
/*
Plugin Name: Question Randomizer
Description: A plugin to display random questions using a shortcode and integrate with Gravity Forms for capturing answers.
Version: 1.0
Author: Hobo Programming
*/

// Register custom post type
function qr_register_custom_post_type() {
    register_post_type('questions', [
        'labels' => [
            'name' => 'Questions',
            'singular_name' => 'Question'
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor'],
    ]);
}
add_action('init', 'qr_register_custom_post_type');

// Check for Gravity Forms dependency
function qr_check_for_gravity_forms() {
    if (!is_plugin_active('gravityforms/gravityforms.php')) {
        deactivate_plugins(plugin_basename(__FILE__));
        add_action('admin_notices', function() {
            echo '<div class="error"><p>Question Randomizer requires Gravity Forms. Please install and activate Gravity Forms first.</p></div>';
        });
    }
}
add_action('admin_init', 'qr_check_for_gravity_forms');

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
        $content = get_the_content();
        wp_reset_postdata();

        // Output the question and form with JavaScript to set the hidden field
        $form_id = intval($atts['form_id']);
        $output = '<div class="qr-question">' . esc_html($question) . '</div>';
        $output .= '<div class="qr-form">' . gravity_form($form_id, false, false, false, '', true, 1, false) . '</div>';
        $output .= "<script type='text/javascript'>
                        document.addEventListener('DOMContentLoaded', function() {
                            var questionField = document.querySelector('input[name=\"input_question\"]');
                            if (questionField) {
                                questionField.value = '" . esc_js($question) . "';
                            }
                        });
                    </script>";

        return $output;
    } else {
        return '<p>No questions found.</p>';
    }
}
add_shortcode('random_question', 'qr_display_random_question');

// Populate hidden field with selected question
add_filter('gform_field_value_question', 'qr_populate_question_field');
function qr_populate_question_field($value) {
    return isset($_POST['input_question']) ? sanitize_text_field($_POST['input_question']) : $value;
}
