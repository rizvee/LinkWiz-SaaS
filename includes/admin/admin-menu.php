<?php
// includes/admin/admin-menu.php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_action( 'admin_menu', 'linkwiz_saas_add_admin_menu' );

function linkwiz_saas_add_admin_menu() {
    // Main Menu Page
    add_menu_page(
        __('LinkWiz SaaS', 'linkwiz-saas'),
        __('LinkWiz SaaS', 'linkwiz-saas'),
        'manage_options', // Capability
        'linkwiz_saas_dashboard', // Menu slug
        'linkwiz_saas_render_dashboard_page', // Function to display page
        'dashicons-admin-links', // Icon
        80 // Position
    );

    // Submenu Page: Dashboard (duplicates main menu link, common practice)
    add_submenu_page(
        'linkwiz_saas_dashboard', // Parent slug
        __('Dashboard', 'linkwiz-saas'),
        __('Dashboard', 'linkwiz-saas'),
        'manage_options',
        'linkwiz_saas_dashboard', // Same slug as parent to make parent clickable
        'linkwiz_saas_render_dashboard_page'
    );

    // Submenu Page: Manage Links
    add_submenu_page(
        'linkwiz_saas_dashboard', // Parent slug
        __('Manage Links', 'linkwiz-saas'),
        __('Manage Links', 'linkwiz-saas'),
        'manage_options',
        'linkwiz_saas_manage_links',
        'linkwiz_saas_render_manage_links_page'
    );

    // Submenu Page: Settings
    add_submenu_page(
        'linkwiz_saas_dashboard', // Parent slug
        __('Settings', 'linkwiz-saas'),
        __('Settings', 'linkwiz-saas'),
        'manage_options',
        'linkwiz_saas_settings', // Page slug for settings
        'linkwiz_saas_render_settings_page'
    );
}

function linkwiz_saas_render_dashboard_page() {
    if (file_exists(plugin_dir_path(__FILE__) . 'dashboard.php')) {
        require_once plugin_dir_path(__FILE__) . 'dashboard.php';
    } else {
        echo '<div class="wrap"><p>Error: Dashboard file not found.</p></div>';
    }
}

function linkwiz_saas_render_manage_links_page() {
    // Path is templates/admin/manage-links.php
    // plugin_dir_path(__FILE__) is includes/admin/
    if (file_exists(plugin_dir_path(__FILE__) . '../../templates/admin/manage-links.php')) {
        require_once plugin_dir_path(__FILE__) . '../../templates/admin/manage-links.php';
    } else {
        echo '<div class="wrap"><p>Error: Manage Links file not found.</p></div>';
    }
}

function linkwiz_saas_render_settings_page() {
    // This is the settings page we are about to update
    if (file_exists(plugin_dir_path(__FILE__) . 'settings.php')) {
        require_once plugin_dir_path(__FILE__) . 'settings.php';
    } else {
        echo '<div class="wrap"><p>Error: Settings file not found.</p></div>';
    }
}
?>
