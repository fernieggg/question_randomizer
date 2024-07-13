<?php

function qr_handle_create_cf7_form() {
    if (isset($_POST['qr_create_cf7_form']) && check_admin_referer('qr_create_forms', 'qr_create_forms_nonce')) {
        if (class_exists('WPCF7_ContactForm')) {
            // Define the form content with a hidden field and a textarea for the answer
            $form_content = "[hidden question default:get]\n[textarea* answer]\n\n[submit 'Submit']";

            // Create the form post array
            $cf7_form = array(
                'post_title' => 'Question Form',
                'post_type' => 'wpcf7_contact_form',
                'post_status' => 'publish',
                'post_author' => get_current_user_id(),
                'post_content' => $form_content
            );

            // Insert the form post
            $form_id = wp_insert_post($cf7_form);

            if (!is_wp_error($form_id)) {
                echo '<div class="updated"><p>Contact Form 7 form created successfully. Form ID: ' . $form_id . '</p></div>';
            } else {
                echo '<div class="error"><p>There was an error creating the form: ' . $form_id->get_error_message() . '</p></div>';
            }
        } else {
            echo '<div class="error"><p>Contact Form 7 is not installed or activated.</p></div>';
        }
    }
}
?>
