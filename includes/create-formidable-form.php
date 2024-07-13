<?php

function qr_handle_create_formidable_form() {
    if (isset($_POST['qr_create_formidable_form']) && check_admin_referer('qr_create_forms', 'qr_create_forms_nonce')) {
        if (class_exists('FrmForm')) {
            $form_data = [
                'name' => 'Question Form',
                'description' => '',
                'is_template' => 0,
                'status' => 'published',
                'options' => [
                    'custom_style' => 1,
                ],
                'form_key' => 'question_form_' . wp_generate_password(5, false)
            ];

            $form_id = FrmForm::create($form_data);

            if (!is_wp_error($form_id)) {
                $fields = [
                    [
                        'type' => 'hidden',
                        'name' => 'question',
                        'form_id' => $form_id,
                        'field_key' => 'field_question',
                        'default_value' => '',
                        'description' => '',
                        'options' => '',
                        'field_options' => [
                            'required' => false,
                            'size' => '',
                            'max' => '',
                            'label' => 'Question',
                            'placeholder' => '',
                            'unique' => false,
                            'blank' => '',
                            'conf_field' => '',
                            'invalid' => '',
                            'clear_on_focus' => false,
                            'read_only' => false,
                            'field_value' => '',
                            'separate_value' => false,
                        ],
                    ],
                    [
                        'type' => 'textarea',
                        'name' => 'answer',
                        'form_id' => $form_id,
                        'field_key' => 'field_answer',
                        'default_value' => '',
                        'description' => '',
                        'options' => '',
                        'field_options' => [
                            'required' => true,
                            'size' => '',
                            'max' => '',
                            'label' => 'Answer',
                            'placeholder' => '',
                            'unique' => false,
                            'blank' => '',
                            'conf_field' => '',
                            'invalid' => '',
                            'clear_on_focus' => false,
                            'read_only' => false,
                            'field_value' => '',
                            'separate_value' => false,
                        ],
                    ],
                ];

                foreach ($fields as $field) {
                    FrmField::create($field);
                }

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
