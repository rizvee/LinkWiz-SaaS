<?php
// includes/admin/settings.php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Ensure this page is loaded through admin menu (capability check is in admin-menu.php)
?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form method="post" action="options.php">
        <?php
        // Output nonce, action, and option_page fields for a settings page.
        settings_fields('linkwiz_saas_settings_group'); // Must match the group name used in register_setting()

        // Output the settings sections and their fields.
        // Must match the page slug used in add_settings_section() / add_settings_field()
        do_settings_sections('linkwiz_saas_settings_page');

        // Output save settings button
        submit_button(__('Save Settings', 'linkwiz-saas'));
        ?>
    </form>
</div>
