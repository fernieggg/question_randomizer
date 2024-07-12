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
        <h2>Pre-populate Questions</h2>
        <p>Click the button below to pre-populate the custom post type with 20 deep, meaningful questions.</p>
        <form method="post" action="">
            <?php wp_nonce_field('qr_prepopulate_questions', 'qr_prepopulate_questions_nonce'); ?>
            <input type="submit" name="qr_prepopulate_questions" class="button button-secondary" value="Pre-populate Questions">
        </form>
    </div>
    <?php
    qr_handle_create_form();
    qr_handle_prepopulate_questions();
}

function qr_handle_create_form() {
    if (isset($_POST['qr_create_form']) && check_admin_referer('qr_create_form', 'qr_create_form_nonce')) {
        if (class_exists('GFAPI')) {
            // Define the form
            $form = array(
                'title' => 'Question Form',
                'labelPlacement' => 'top_label', // Added default value
                'button' => array( // Added default button
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

function qr_handle_prepopulate_questions() {
    if (isset($_POST['qr_prepopulate_questions']) && check_admin_referer('qr_prepopulate_questions', 'qr_prepopulate_questions_nonce')) {
        $questions = array(
            "What is your biggest fear, and how does it affect your life?",
            "What is the most valuable lesson you've learned in life so far?",
            "How do you define true happiness, and have you experienced it?",
            "What is the one thing you regret the most in your life, and why?",
            "If you could change one event in your past, what would it be and why?",
            "What are the top three things on your bucket list, and why are they important to you?",
            "What does love mean to you, and how do you express it?",
            "How do you deal with failure, and what have you learned from your biggest failure?",
            "What is the most meaningful book you have read, and how did it impact you?",
            "What is your greatest achievement, and why does it mean so much to you?",
            "How do you find purpose in your life, and what drives you every day?",
            "What is the biggest challenge you have faced, and how did you overcome it?",
            "What role does forgiveness play in your life, and who do you need to forgive?",
            "How do you handle stress and pressure in difficult situations?",
            "What are your core values, and how do they influence your decisions?",
            "What is the most important relationship in your life, and why?",
            "How do you contribute to the world, and what legacy do you want to leave behind?",
            "What does success mean to you, and how do you measure it?",
            "How do you cope with loss, and what have you learned from it?",
            "What is your vision for the future, and what steps are you taking to achieve it?"
        );

        foreach ($questions as $question) {
            // Check if the question already exists
            $existing_posts = get_posts(array(
                'post_type' => 'questions',
                'meta_key' => 'is_prepopulated',
                'meta_value' => '1',
                'title' => $question,
            ));

            if (empty($existing_posts)) {
                // Insert the post
                $post_id = wp_insert_post(array(
                    'post_title' => wp_strip_all_tags($question),
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_type' => 'questions'
                ));

                // Add the meta key to indicate this is a prepopulated question
                add_post_meta($post_id, 'is_prepopulated', '1');
            }
        }

        echo '<div class="updated"><p>20 deep, meaningful questions have been pre-populated successfully.</p></div>';
    }
}
?>
