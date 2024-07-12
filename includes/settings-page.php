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
        <h2>Create Gravity Form</h2>
        <p>Click the button below to create a Gravity Form with the correct fields and settings for the Question Randomizer plugin.</p>
        <form method="post" action="">
            <?php wp_nonce_field('qr_create_form', 'qr_create_form_nonce'); ?>
            <input type="submit" name="qr_create_form" class="button button-primary" value="Create Gravity Form">
        </form>
    </div>
    <?php
    qr_handle_create_form();
}

function qr_handle_create_form() {
    if (isset($_POST['qr_create_form']) && check_admin_referer('qr_create_form', 'qr_create_form_nonce')) {
        if (class_exists('GFAPI')) {
            // Define the form
            $form = array(
                'title' => 'Question Form',
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
?>
