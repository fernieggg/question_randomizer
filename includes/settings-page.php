<?php
// Include necessary function files
require_once(plugin_dir_path(__FILE__) . 'create-gravity-form.php');
require_once(plugin_dir_path(__FILE__) . 'create-formidable-form.php');
require_once(plugin_dir_path(__FILE__) . 'prepopulate-questions.php');

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
    // Get the current form builder setting
    $form_builder = get_option('qr_form_builder', '');
    $debug_mode = get_option('qr_debug_mode', false);
    ?>
    <div class="wrap">
        <h1>Question Randomizer Settings</h1>
        <h2>About the Plugin</h2>
        <p>Question Randomizer is a plugin that allows you to display random questions on your website using a shortcode. It integrates with Gravity Forms or Formidable Forms to capture answers from your visitors. The plugin was developed by Hobo Programming to provide an easy way to engage visitors with random questions and capture their responses.</p>
        <h2>Usage</h2>
        <p>To display a random question and embed the form, use the following shortcode:</p>
        <code>[random_question form_id=your_form_id]</code>
        <p>Replace <code>your_form_id</code> with the actual ID of the form you created.</p>
        <h2>Select Form Builder</h2>
        <form method="post" action="">
            <?php wp_nonce_field('qr_save_settings', 'qr_save_settings_nonce'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Form Builder</th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="radio" name="qr_form_builder" value="gravity_forms" <?php checked($form_builder, 'gravity_forms'); ?>>
                                Gravity Forms
                            </label><br>
                            <label>
                                <input type="radio" name="qr_form_builder" value="formidable_forms" <?php checked($form_builder, 'formidable_forms'); ?>>
                                Formidable Forms
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Enable Debug Mode</th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="checkbox" name="qr_debug_mode" value="1" <?php checked($debug_mode, true); ?>>
                                Enable Debugging
                            </label>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <input type="submit" name="qr_save_settings" class="button button-primary" value="Save Settings">
        </form>
        <?php if ($form_builder): ?>
            <h2>Create <?php echo ucfirst(str_replace('_', ' ', $form_builder)); ?> Form</h2>
            <p>Click the button below to create a <?php echo ucfirst(str_replace('_', ' ', $form_builder)); ?> Form with the correct fields and settings for the Question Randomizer plugin.</p>
            <form method="post" action="">
                <?php wp_nonce_field('qr_create_' . $form_builder . '_form', 'qr_create_' . $form_builder . '_form_nonce'); ?>
                <input type="submit" name="qr_create_<?php echo $form_builder; ?>_form" class="button button-primary" value="Create <?php echo ucfirst(str_replace('_', ' ', $form_builder)); ?> Form">
            </form>
        <?php endif; ?>
        <h2>Pre-populate Questions</h2>
        <p>Click the button below to pre-populate the custom post type with 20 deep, meaningful questions.</p>
        <form method="post" action="">
            <?php wp_nonce_field('qr_prepopulate_questions', 'qr_prepopulate_questions_nonce'); ?>
            <input type="submit" name="qr_prepopulate_questions" class="button button-secondary" value="Pre-populate Questions">
        </form>
    </div>
    <?php
    if (function_exists('qr_handle_create_gravity_form')) {
        qr_handle_create_gravity_form();
    }
    if (function_exists('qr_handle_create_formidable_form')) {
        qr_handle_create_formidable_form();
    }
    if (function_exists('qr_handle_prepopulate_questions')) {
        qr_handle_prepopulate_questions();
    }
}
?>
