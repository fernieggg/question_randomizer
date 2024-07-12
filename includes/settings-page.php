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
    // Get the current form builder setting
    $form_builder = get_option('qr_form_builder', 'gravity_forms');
    ?>
    <div class="wrap">
        <h1>Question Randomizer Settings</h1>
        <h2>About the Plugin</h2>
        <p>Question Randomizer is a plugin that allows you to display random questions on your website using a shortcode. It integrates with Gravity Forms or Contact Form 7 to capture answers from your visitors. The plugin was developed by Hobo Programming to provide an easy way to engage visitors with random questions and capture their responses.</p>
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
                                <input type="radio" name="qr_form_builder" value="contact_form_7" <?php checked($form_builder, 'contact_form_7'); ?>>
                                Contact Form 7
                            </label>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <input type="submit" name="qr_save_settings" class="button button-primary" value="Save Settings">
        </form>
        <h2>Create Gravity Form</h2>
        <p>Click the button below to create a Gravity Form with the correct fields and settings for the Question Randomizer plugin.</p>
        <form method="post" action="">
            <?php wp_nonce_field('qr_create_gravity_form', 'qr_create_gravity_form_nonce'); ?>
            <input type="submit" name="qr_create_gravity_form" class="button button-primary" value="Create Gravity Form">
        </form>
        <h2>Create Contact Form 7 Form</h2>
        <p>Click the button below to create a Contact Form 7 form with the correct fields and settings for the Question Randomizer plugin.</p>
        <form method="post" action="">
            <?php wp_nonce_field('qr_create_cf7_form', 'qr_create_cf7_form_nonce'); ?>
            <input type="submit" name="qr_create_cf7_form" class="button button-primary" value="Create Contact Form 7 Form">
        </form>
        <h2>Pre-populate Questions</h2>
        <p>Click the button below to pre-populate the custom post type with 20 deep, meaningful questions.</p>
        <form method="post" action="">
            <?php wp_nonce_field('qr_prepopulate_questions', 'qr_prepopulate_questions_nonce'); ?>
            <input type="submit" name="qr_prepopulate_questions" class="button button-secondary" value="Pre-populate Questions">
        </form>
    </div>
    <?php
    qr_handle_create_gravity_form();
    qr_handle_create_cf7_form();
    qr_handle_prepopulate_questions();
}

function qr_handle_create_gravity_form() {
    if (isset($_POST['qr_create_gravity_form']) && check_admin_referer('qr_create_gravity_form', 'qr_create_gravity_form_nonce')) {
        if (class_exists('GFAPI')) {
            // Define the form
            $form = array(
                'title' => 'Question Form',
                'labelPlacement' => 'top_label',
                'button' => array(
                    'type' => 'text',
                    'text' => 'Submit'
                ),
                'fields' => array(
                    array(
                        'type' => 'hidden',
                        'label' => 'Question',
                        'adminLabel' => 'question',
                        'isRequired' => false,
                        'inputName' => 'question',
                        'allowsPrepopulate' => true,
                        'size' => 'medium',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => 'Answer',
                        'isRequired' => true,
                        'size' => 'medium',
                        'inputName' => 'answer',
                    ),
                ),
            );

            // Create the form
            $form_id = GFAPI::add_form($form);

            if (!is_wp_error($form_id)) {
                echo '<div class="updated"><p>Gravity Form created successfully. Form ID: ' . $form_id . '</p></div>';
            } else {
                echo '<div class="error"><p>There was an error creating the form: ' . $form_id->get_error_message() . '</p></div>';
            }
        } else {
            echo '<div class="error"><p>Gravity Forms is not installed or activated.</p></div>';
        }
    }
}

function qr_handle_create_cf7_form() {
    if (isset($_POST['qr_create_cf7_form']) && check_admin_referer('qr_create_cf7_form', 'qr_create_cf7_form_nonce')) {
        if (class_exists('WPCF7_ContactForm')) {
            // Define the form content with a hidden field and a textarea for the answer
            $form_content = "[hidden question default:get]\n[textarea* answer]\n\n[submit 'Submit']";

            // Create the form post array
            $cf7_form = array(
                'post_title' => 'Question Form',
                'post_type' => 'wpcf7_contact_form',
                'post_status' => 'publish',
                'post_author' => get_current_user_id(),
                'post_content' => '', // Empty initially, will be updated after insert
            );

            // Insert the form post
            $form_id = wp_insert_post($cf7_form);

            if (!is_wp_error($form_id)) {
                // Now update the post content with the form fields
                wp_update_post(array(
                    'ID' => $form_id,
                    'post_content' => $form_content
                ));

                // Provide feedback to the user
                echo '<div class="updated"><p>Contact Form 7 form created successfully. Form ID: ' . $form_id . '</p></div>';
            } else {
                echo '<div class="error"><p>There was an error creating the form: ' . $form_id->get_error_message() . '</p></div>';
            }
        } else {
            echo '<div class="error"><p>Contact Form 7 is not installed or activated.</p></div>';
        }
    }
}


// Save the settings
function qr_save_settings() {
    if (isset($_POST['qr_save_settings']) && check_admin_referer('qr_save_settings', 'qr_save_settings_nonce')) {
        if (isset($_POST['qr_form_builder'])) {
            update_option('qr_form_builder', sanitize_text_field($_POST['qr_form_builder']));
            echo '<div class="updated"><p>Settings saved successfully.</p></div>';
        }
    }
}
add_action('admin_init', 'qr_save_settings');
?>
