<?php

$form_builder = get_option('qr_form_builder', '');
$debug_mode = get_option('qr_debug_mode', false);

// URLs of the SVG files
$gravity_svg_url = plugin_dir_url(__FILE__) . 'assets/gravity-forms-logo-badge-only.svg';
$formidable_svg_url = plugin_dir_url(__FILE__) . 'assets/formidable-cropped.svg';
$cf7_svg_url = plugin_dir_url(__FILE__) . 'assets/cf7_logo.svg';

?>
<div class="wrap">
    <h1 class="text-2xl font-bold mb-4">Question Randomizer Settings</h1>
    <form method="post" action="">
        <?php wp_nonce_field('qr_save_settings', 'qr_save_settings_nonce'); ?>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Form Builder</label>
            <div x-data="{
                radios: [
                    {
                        name: 'gravity_forms',
                        displayName: 'Gravity Forms',
                        description: 'A powerful form builder for WordPress.',
                        iconUrl: '<?php echo $gravity_svg_url; ?>'
                    },
                    {
                        name: 'formidable_forms',
                        displayName: 'Formidable Forms',
                        description: 'Advanced forms with powerful features.',
                        iconUrl: '<?php echo $formidable_svg_url; ?>'
                    },
                    {
                        name: 'cf7',
                        displayName: 'Contact Form 7',
                        description: 'Simple and flexible contact forms.',
                        iconUrl: '<?php echo $cf7_svg_url; ?>'
                    }
                ],
                selectedFormBuilder: '<?php echo $form_builder; ?>'
            }" class="max-w-md mx-auto px-4">
                <ul class="mt-6 space-y-3">
                    <template x-for="(item, index) in radios" :key="index">
                        <li>
                            <label :for="item.name" class="block relative">
                                <input
                                    :id="item.name"
                                    type="radio"
                                    :value="item.name"
                                    :checked="selectedFormBuilder === item.name"
                                    @click="selectedFormBuilder = item.name"
                                    name="qr_form_builder"
                                    class="sr-only peer"
                                />
                                <div :class="{'ring-2 ring-indigo-600': selectedFormBuilder === item.name}" class="w-full flex gap-x-3 items-start p-4 cursor-pointer rounded-lg border bg-white shadow-sm duration-200">
                                    <div class="flex-none">
                                        <img x-bind:src="item.iconUrl" alt="" class="w-12 h-12 border-none" x-show="item.iconUrl">
                                        <div x-html="item.icon" x-show="!item.iconUrl"></div>
                                    </div>
                                    <div>
                                        <h3 class="leading-none text-gray-800 font-medium pr-3" x-text="item.displayName"></h3>
                                        <p class="mt-1 text-sm text-gray-600" x-text="item.description"></p>
                                    </div>
                                </div>
                                <div :class="{'bg-indigo-600 text-white': selectedFormBuilder === item.name}" class="absolute top-4 right-4 flex-none flex items-center justify-center w-4 h-4 rounded-full border text-transparent duration-200">
                                    <svg class="w-2.5 h-2.5" viewBox="0 0 12 10">
                                        <polyline fill="none" stroke-width="2px" stroke="currentColor" stroke-dasharray="16px" points="1.5 6 4.5 9 10.5 1"></polyline>
                                    </svg>
                                </div>
                            </label>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Enable Debug Mode</label>
            <div class="flex items-center">
                <input type="checkbox" name="qr_debug_mode" value="1" <?php checked($debug_mode, true); ?> class="form-checkbox h-4 w-4 text-indigo-600">
                <span class="ml-2">Enable Debugging</span>
            </div>
        </div>
        <div>
            <input type="submit" name="qr_save_settings" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" value="Save Settings">
        </div>
    </form>
    <hr class="my-6">
    <h2 class="text-xl font-bold mb-4">Create Forms</h2>
    <form method="post" action="">
        <?php wp_nonce_field('qr_create_forms', 'qr_create_forms_nonce'); ?>
        <?php if ($form_builder === 'gravity_forms'): ?>
            <input type="submit" name="qr_create_gravity_form" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" value="Create Gravity Form">
        <?php elseif ($form_builder === 'formidable_forms'): ?>
            <input type="submit" name="qr_create_formidable_form" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" value="Create Formidable Form">
        <?php elseif ($form_builder === 'cf7'): ?>
            <input type="submit" name="qr_create_cf7_form" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" value="Create Contact Form 7">
        <?php endif; ?>
    </form>
    <hr class="my-6">
    <h2 class="text-xl font-bold mb-4">Prepopulate Questions</h2>
    <form method="post" action="">
        <?php wp_nonce_field('qr_prepopulate_questions', 'qr_prepopulate_questions_nonce'); ?>
        <input type="submit" name="qr_prepopulate_questions" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded" value="Prepopulate Questions">
    </form>
</div>
<?php

include_once(plugin_dir_path(__FILE__) . 'save-settings.php');
?>
