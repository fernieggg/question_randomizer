<?php
// Handle pre-populating questions
function qr_handle_prepopulate_questions() {
    if (isset($_POST['qr_prepopulate_questions']) && check_admin_referer('qr_prepopulate_questions', 'qr_prepopulate_questions_nonce')) {
        $questions = array(
            "What's the darkest thought you've ever had about yourself?",
"What's a hard truth about life that you're trying to ignore?",
"How much of who you are is really just what others expect you to be?",
"Could you give up your strongest belief if it meant helping a lot of people? What’s your strongest belief? What's stopping you?",
"What's something you hate about yourself but can't seem to let go of?",
"If you lost everything tomorrow, who would you be?",
"What have you been trying to prove wrong about yourself your whole life?",
"Has chasing happiness actually made you miss out on other important things?",
"What scares you most about being human?",
"If you could feel someone else's pain completely, how would it change you?",
"What's the biggest lie you tell yourself?",
"How much of what you do is because you want to, and how much is because you're afraid of not being good enough?",
"If you could know everything about yourself, would you want to? Or would you rather not know?",
"What part of your personality comes from stuff that hurt you?",
"How are you part of the problems you want to fix in the world?",
"If you knew how much you affected others and the world, could you handle it?",
"What truth about yourself are you hiding from because you're scared to face it?",
"How much of who you are is built on the bad things that happened to you?",
"Is the person you’re in love with the person you ended up with?",
"What family issues are you carrying without realizing it?",
"How would you live differently if you were given a day to relive a moment in time?",
"What part of yourself do you need to let go of to become who you're meant to be?",
"If you could see every result of your actions, would you still make the same choices?",
"What's something you've always believed about life that might be wrong?",
"What is something you wish you could tell someone, and who is it?",
"If your whole life was summed up in one moment, what would it be?",
"What's the hardest truth about yourself you'd have to tell?",
"How has your fear of being unimportant driven you to seek meaning in the wrong places?",
"Do you believe in life after death?",
"What part of who you are have you let others define, and what would it take to reclaim it?",
"Your phone rings, and you pick up. It is your mother. What is the first thing you say?",
"If you could fully understand how your words impact others, would you still talk the same way?",
"What value that you hold dear might actually be a way of fooling yourself?",
"How has your need for things to make sense kept you from embracing life's contradictions?",
"If you could fully grasp that you're going to die, how would it change your daily choices?",
"What part of your personality is just a learned response to pain rather than the real you?",
"If you were diagnosed with something terminal, would you try and fight it?",
"If you could truly understand the concept of forever, how would it change how you see your own life?",
"What part of your moral code, if challenged, might turn out to be based on fear rather than what's right?",
"How has your desire for comfort robbed you of amazing life experiences?",
"Would you relive this life if it meant you could not change anything?",
"What hard truth about relationships are you avoiding because it hurts too much?",
"If you could change something about your life, what would it be?",
"Have you ever self-admitted to being an alcoholic? Would you start drinking when you did?",
"What part of who you are is just rebelling against your past, and is it still helpful?",
"How has your fear of being vulnerable shaped your ability to truly connect with others?",
"What is something you just need to get off your chest?",
"What belief about yourself is holding you back from really growing and changing?",
"What is something from the past that you wish had never happened, but you're thankful it did?",
"What is something from the past you wish you could change? How would that change affect your life today?",
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
