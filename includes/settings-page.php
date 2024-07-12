<?php
// Add settings page
function qr_add_settings_page() {
    add_options_page(
        'Question Randomizer Settings',
        'Question Randomizer',
        'manage_options',
        'question-randomizer',
        'qr_render_settings_page'
    );
}
add_action('admin_menu', 'qr_add_settings_page');

// Render settings page
function qr_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Question Randomizer Settings</h1>
        <h2>About the Plugin</h2>
        <p>Question Randomizer is a plugin that allows you to display random questions on your website using a shortcode. It integrates with Gravity Forms to capture answers from your visitors. The plugin was developed by Hobo Programming to provide an easy way to engage visitors with random questions and capture their responses.</p>
        <h2>Usage</h2>
        <p>To display a random question and embed the Gravity Form, use the following shortcode:</p>
        <code>[random_question form_id=your_gravity_form_id]</code>
        <p>Replace <code>your_gravity_form_id</code> with the actual ID of the Gravity Form you created.</p>
    </div>
    <?php
}
?>
