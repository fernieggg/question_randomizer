<?php
function qr_handle_create_formidable_form() {
    if (isset($_POST['qr_create_formidable_form']) && check_admin_referer('qr_create_formidable_form', 'qr_create_formidable_form_nonce')) {
        if (class_exists('FrmForm')) {
            // Define the form fields
            $form_fields = array(
                array(
                    'type' => 'hidden',
                    'name' => 'Question',
                    'required' => false,
                    'field_key' => 'field_question',
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'Answer',
                    'required' => true,
                    'field_key' => 'field_answer',
                ),
            );

            // Create the form
            $form_data = array(
                'name' => 'Question Form',
                'status' => 'published',
                'fields' => $form_fields,
            );

            $form_id = FrmForm::create($form_data);

            if (!is_wp_error($form_id)) {
                echo '<div class="updated"><p>Formidable Form created successfully. Form ID: ' . $form_id . '</p></div>';
            } else {
                echo '<div class="error"><p>There was an error creating the form: ' . $form_id->get_error_message() . '</p></div>';
            }
        } else {
            echo '<div class="error"><p>Formidable Forms is not installed or activated.</p></div>';
        }
    }
}
?>
