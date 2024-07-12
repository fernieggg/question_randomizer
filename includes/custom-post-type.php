<?php
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
?>
