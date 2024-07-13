<?php
add_action('tgmpa_register', 'qr_register_required_plugins');

function qr_register_required_plugins() {
    $plugins = array(
        array(
            'name'      => 'Gravity Forms',
            'slug'      => 'gravityforms',
            'required'  => false,
            'source'    => 'https://gravityforms.com', // External URL
        ),
        array(
            'name'      => 'Formidable Forms',
            'slug'      => 'formidable',
            'required'  => false,
        ),
        array(
            'name'      => 'Contact Form 7',
            'slug'      => 'contact-form-7',
            'required'  => false,
        ),
        array(
            'name'      => 'Flamingo',
            'slug'      => 'flamingo',
            'required'  => false,
        ),
    );

    $config = array(
        'id'           => 'question-randomizer',          // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                             // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins',        // Menu slug.
        'has_notices'  => true,                           // Show admin notices or not.
        'dismissable'  => true,                           // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                             // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                          // Automatically activate plugins after installation or not.
        'message'      => '',                             // Message to output right before the plugins table.
    );

    tgmpa($plugins, $config);
}
?>
