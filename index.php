<?php
/*
Script: LinkWiz SaaS
Description: LinkWiz SaaS is a versatile and powerful PHP script that empowers you to manage and customize your links effortlessly. It offers features like QR code generation, URL shortening, and personalized Bio-Links, making it a go-to solution for businesses, influencers, and marketing professionals. Built on a rock-solid Laravel foundation, LinkWiz SaaS is ready to handle your growing demands, making it a perfect choice for a thriving SaaS business. Unlock limitless monetization opportunities and establish a strong brand presence with white-label branding.
Version: 1.0
Author: Hasan Rizvee
Author URI: https://github.com/rizvee
*/

// Enqueue  CSS and JavaScript files
function linkwiz_saas_enqueue_scripts() {
    // Enqueue CSS
    wp_enqueue_style('linkwiz-saas-styles', plugins_url('/assets/css/styles.css', __FILE__));
    
    // Enqueue JavaScript
    wp_enqueue_script('linkwiz-saas-scripts', plugins_url('/assets/js/script.js', __FILE__), array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'linkwiz_saas_enqueue_scripts');

// Include other necessary files and functions for your script's functionality
require_once(plugin_dir_path(__FILE__) . 'includes/admin/dashboard.php');
require_once(plugin_dir_path(__FILE__) . 'includes/admin/settings.php');

// Define and implement the rest of your script's features and functionality here.

// create custom post types, functions, and hooks to extend your script.

// Don't forget to handle user authentication, access control, and database operations.

// Create documentation and user guides in the 'docs' directory.

// Handle any licensing and updates system, if needed.

// Implement your features based on the changelog you provided.


?>
