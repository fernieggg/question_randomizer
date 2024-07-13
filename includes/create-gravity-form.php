<?php
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
?>