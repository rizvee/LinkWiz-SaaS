<?php
/*
Script: LinkWiz SaaS
Description: LinkWiz SaaS is a versatile and powerful WordPress plugin that empowers you to manage and customize your links effortlessly. It offers features like QR code generation, URL shortening, and personalized Bio-Links, making it a go-to solution for businesses, influencers, and marketing professionals. LinkWiz SaaS is a WordPress plugin designed to handle your growing demands, making it a perfect choice for a thriving SaaS business. Unlock limitless monetization opportunities and establish a strong brand presence with white-label branding.
Version: 1.0
Author: Hasan Rizvee
Author URI: https://github.com/rizvee
Plugin URI: https://example.com/linkwiz-saas // Added for good practice
License: GPLv2 or later // Added for good practice
Text Domain: linkwiz-saas // Added for good practice
Domain Path: /languages // Added for good practice
*/

// Ensure WordPress environment is loaded
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! defined( 'LINKWIZ_PLUGIN_FILE' ) ) {
    define( 'LINKWIZ_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'LINKWIZ_PLUGIN_DIR' ) ) {
    define( 'LINKWIZ_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// Load Text Domain
function linkwiz_saas_load_textdomain() {
    load_plugin_textdomain( 'linkwiz-saas', false, dirname( plugin_basename( LINKWIZ_PLUGIN_FILE ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'linkwiz_saas_load_textdomain' );

// Enqueue CSS and JavaScript files
function linkwiz_saas_enqueue_scripts() {
    // Enqueue CSS
    wp_enqueue_style('linkwiz-saas-styles', plugins_url('/assets/css/styles.css', __FILE__));

    // Enqueue JavaScript
    wp_enqueue_script('linkwiz-saas-scripts', plugins_url('/assets/js/script.js', __FILE__), array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'linkwiz_saas_enqueue_scripts');

// Database setup on plugin activation
function linkwiz_saas_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'linkwiz_urls';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        original_url text NOT NULL,
        short_key varchar(55) NOT NULL,
        user_id bigint(20) UNSIGNED DEFAULT NULL, // To link to wp_users table
        visit_count bigint(20) UNSIGNED DEFAULT 0 NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY short_key (short_key), // Ensure short keys are unique
        KEY user_id (user_id) // Index for user_id if frequently queried
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'linkwiz_saas_activate');

// Admin settings and menu
require_once LINKWIZ_PLUGIN_DIR . 'includes/admin-settings-handler.php';
require_once LINKWIZ_PLUGIN_DIR . 'includes/admin/admin-menu.php';

// Fron-end redirection
require_once LINKWIZ_PLUGIN_DIR . 'includes/front/redirection-handler.php';

// Placeholder for URL Shortener class - will be properly included/managed later
if (file_exists(plugin_dir_path(__FILE__) . 'lib/url-shortening/url-shortener.php')) {
    // require_once(plugin_dir_path(__FILE__) . 'lib/url-shortening/url-shortener.php');
}

// Placeholder for QR Code Generator class - will be properly included/managed later
if (file_exists(plugin_dir_path(__FILE__) . 'lib/qr-code/qr-code-generator.php')) {
    // require_once(plugin_dir_path(__FILE__) . 'lib/qr-code/qr-code-generator.php');
}

// Define and implement the rest of your script's features and functionality here.
// create custom post types, functions, and hooks to extend your script.
// Don't forget to handle user authentication, access control, and database operations.
// Create documentation and user guides in the 'docs' directory.
// Handle any licensing and updates system, if needed.
// Implement your features based on the changelog you provided.

?>
