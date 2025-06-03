<?php
// includes/admin-settings-handler.php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_action( 'admin_init', 'linkwiz_saas_register_settings' );

function linkwiz_saas_register_settings() {
    // Option Group
    $option_group = 'linkwiz_saas_settings_group';

    // Settings Page Slug (used in do_settings_sections)
    $page_slug = 'linkwiz_saas_settings_page';

    // Register Settings
    register_setting($option_group, 'linkwiz_saas_short_url_domain', array(
        'type' => 'string',
        'sanitize_callback' => 'esc_url_raw',
        'default' => site_url(),
    ));
    register_setting($option_group, 'linkwiz_saas_site_title', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => get_bloginfo('name'),
    ));
    register_setting($option_group, 'linkwiz_saas_site_description', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_textarea_field',
        'default' => get_bloginfo('description'),
    ));
    register_setting($option_group, 'linkwiz_saas_site_logo_url', array(
        'type' => 'string',
        'sanitize_callback' => 'esc_url_raw',
        'default' => '',
    ));

    // Add Section
    add_settings_section(
        'linkwiz_saas_general_section',
        __('General Settings', 'linkwiz-saas'),
        '__return_false', // No callback needed for section description for now
        $page_slug
    );

    // Add Fields
    add_settings_field(
        'linkwiz_saas_short_url_domain_field',
        __('Short URL Domain', 'linkwiz-saas'),
        'linkwiz_saas_render_short_url_domain_field',
        $page_slug,
        'linkwiz_saas_general_section'
    );
    add_settings_field(
        'linkwiz_saas_site_title_field',
        __('Site Title (Plugin Specific)', 'linkwiz-saas'),
        'linkwiz_saas_render_site_title_field',
        $page_slug,
        'linkwiz_saas_general_section'
    );
    add_settings_field(
        'linkwiz_saas_site_description_field',
        __('Site Description (Plugin Specific)', 'linkwiz-saas'),
        'linkwiz_saas_render_site_description_field',
        $page_slug,
        'linkwiz_saas_general_section'
    );
    add_settings_field(
        'linkwiz_saas_site_logo_url_field',
        __('Site Logo URL (Plugin Specific)', 'linkwiz-saas'),
        'linkwiz_saas_render_site_logo_url_field',
        $page_slug,
        'linkwiz_saas_general_section'
    );
}

// Render Callbacks for Fields
function linkwiz_saas_render_short_url_domain_field() {
    $option = get_option('linkwiz_saas_short_url_domain', site_url());
    echo '<input type="url" name="linkwiz_saas_short_url_domain" value="' . esc_attr($option) . '" class="regular-text" placeholder="' . esc_attr(site_url()) . '">';
    echo '<p class="description">' . __('Enter the domain to be used for short URLs (e.g., https://sho.rt). Defaults to your site URL. Ensure it includes http/https.', 'linkwiz-saas') . '</p>';
}

function linkwiz_saas_render_site_title_field() {
    $option = get_option('linkwiz_saas_site_title', get_bloginfo('name'));
    echo '<input type="text" name="linkwiz_saas_site_title" value="' . esc_attr($option) . '" class="regular-text">';
    echo '<p class="description">' . __('This title might be used in plugin-specific displays, like Bio-Link pages.', 'linkwiz-saas') . '</p>';
}

function linkwiz_saas_render_site_description_field() {
    $option = get_option('linkwiz_saas_site_description', get_bloginfo('description'));
    echo '<textarea name="linkwiz_saas_site_description" rows="3" class="regular-text">' . esc_textarea($option) . '</textarea>';
    echo '<p class="description">' . __('This description might be used in plugin-specific displays.', 'linkwiz-saas') . '</p>';
}

function linkwiz_saas_render_site_logo_url_field() {
    $option = get_option('linkwiz_saas_site_logo_url', '');
    echo '<input type="url" name="linkwiz_saas_site_logo_url" value="' . esc_attr($option) . '" class="regular-text" placeholder="https://example.com/logo.png">';
    echo '<p class="description">' . __('Enter the URL for a logo. This might be used in plugin-specific displays. For a better experience, use the Media Library and paste the URL here.', 'linkwiz-saas') . '</p>';
}
?>
