<?php

$form_builder = get_option('qr_form_builder', '');
$debug_mode = get_option('qr_debug_mode', false);

// URLs of the SVG files
$gravity_svg_url = plugin_dir_url(__FILE__) . 'assets/gravity-forms-logo-badge-only.svg';
$formidable_svg_url = plugin_dir_url(__FILE__) . 'assets/formidable-cropped.svg';
$cf7_svg_url = plugin_dir_url(__FILE__) . 'assets/cf7_logo.svg';

// Enqueue the debug-toggle.js script
wp_enqueue_script('qr_debug_toggle', plugin_dir_url(__FILE__) . 'assets/debug-toggle.js', [], null, true);
wp_localize_script('qr_debug_toggle', 'qr_toggle_debug_mode', ['nonce' => wp_create_nonce('qr_toggle_debug_mode')]);

?>
<div class="wrap">
    <h1 class="text-center text-purple-700 text-3xl font-bold mb-10">Question Randomizer Settings</h1>
    <form method="post" action="">
        <?php wp_nonce_field('qr_save_settings', 'qr_save_settings_nonce'); ?>
        <div class="grid grid-cols-3 gap-8">

            <!-- Column 1: Form Builder Card -->
            <div>
                <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                    <h2 class="text-purple-700 text-xl font-semibold mb-4 text-left">Form Builder</h2>
                    <div class="flex flex-col items-center">
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
                        }" class="max-w-md px-4">
                            <ul class="mt-6 space-y-3 text-left">
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
                        <!-- Save Settings Button -->
                        <div class="mt-6">
                            <input type="submit" name="qr_save_settings" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded" value="Save Settings">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column 2: Other Cards -->
            <div>
                <!-- Create Form Card -->
                <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                    <h2 class="text-purple-700 text-xl font-semibold mb-4">Create Forms</h2>
                    <?php wp_nonce_field('qr_create_forms', 'qr_create_forms_nonce'); ?>
                    <?php if ($form_builder === 'gravity_forms'): ?>
                        <input type="submit" name="qr_create_gravity_form" class="bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded-md" value="Create Gravity Form">
                    <?php elseif ($form_builder === 'formidable_forms'): ?>
                        <input type="submit" name="qr_create_formidable_form" class="bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded-md" value="Create Formidable Form">
                    <?php elseif ($form_builder === 'cf7'): ?>
                        <input type="submit" name="qr_create_cf7_form" class="bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded-md" value="Create Contact Form 7">
                    <?php endif; ?>
                </div>

                <!-- Prepopulate Questions Card -->
                <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                    <h2 class="text-purple-700 text-xl font-semibold mb-4">Prepopulate Questions</h2>
                    <?php wp_nonce_field('qr_prepopulate_questions', 'qr_prepopulate_questions_nonce'); ?>
                    <input type="submit" name="qr_prepopulate_questions" class="bg-purple-500 hover:bg-purple-600 text-white py-2 px-4 rounded-md" value="Prepopulate Questions">
                </div>

                <!-- Debugger Card -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-purple-700 text-xl font-semibold mb-4">Debug Mode</h2>
                    <div class="flex items-center" x-data="toggleDebugMode" @click="toggleDebugMode">
                        <label for="qr_debug_mode" class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" id="qr_debug_mode" name="qr_debug_mode" class="sr-only" x-model="on">
                                <div class="block bg-gray-600 w-14 h-8 rounded-full" :class="{ 'bg-indigo-600': on }"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition" :class="{ 'transform translate-x-full': on }"></div>
                            </div>
                            <div class="ml-3 text-gray-700">Enable Debugging</div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Column 3: Empty Column -->
            <div></div>

        </div>
    </form>
</div>
<?php

include_once(plugin_dir_path(__FILE__) . 'save-settings.php');
?>
