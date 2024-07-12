<?php
// Handle pre-populating questions
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
add_action('admin_init', 'qr_handle_prepopulate_questions');
?>
