<?php
// includes/front/redirection-handler.php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

add_action( 'template_redirect', 'linkwiz_saas_handle_short_url_redirection', 1 ); // Priority 1 to run early

function linkwiz_saas_handle_short_url_redirection() {
    // Get the configured base for short URLs.
    // It should be like "https://yourdomain.com/basepath" (no trailing slash from option)
    $short_url_domain_setting = get_option('linkwiz_saas_short_url_domain', site_url());

    // Get the path part of this configured domain/base.
    // Example: if option is "https://sho.rt/s", base_path_component will be "/s"
    // If option is "https://mywordpress.com", base_path_component will be ""
    $base_path_component = rtrim( wp_parse_url( $short_url_domain_setting, PHP_URL_PATH ) ?: '', '/' );

    // Get the requested path from the current URL, without query string.
    $requested_path = strtok( $_SERVER['REQUEST_URI'], '?' );
    $requested_path = rtrim( $requested_path, '/' ); // Normalize by removing trailing slash for comparison

    // Construct the comparable part of the configured base path.
    // If base_path_component is "/s", then comparable_base is "/s".
    // If base_path_component is "", then we are looking for keys directly at root.
    $comparable_base = $base_path_component;

    $short_key = '';

    // Check if the requested path starts with the base_path_component
    // And if there's something *after* the base_path_component (the potential key)
    // Or if base_path_component is empty, the requested_path itself is the key.
    if ( $comparable_base === '' ) {
        // Short links are at the root of the domain specified in settings (e.g., https://sho.rt/key)
        // or at the root of the WP site (e.g., https://mywordpress.com/key)
        // We must ensure the request is for the correct host if short_url_domain_setting has a different host.
        $configured_host = wp_parse_url( $short_url_domain_setting, PHP_URL_HOST );
        $current_host = $_SERVER['HTTP_HOST'];

        if ( $configured_host && strtolower($current_host) !== strtolower($configured_host) ) {
            // Not the domain we should be handling short links for.
            return;
        }
        // If hosts match (or configured_host is empty, meaning same as WP site),
        // the requested_path (without trailing slash) is the potential key.
        // Avoid matching empty paths (root of the site).
        if ( $requested_path !== '' && $requested_path !== '/' ) {
             // Remove leading slash if present, as short keys don't store it.
            $short_key = ltrim( $requested_path, '/' );
        }

    } elseif ( strpos( $requested_path, $comparable_base . '/' ) === 0 && strlen( $requested_path ) > strlen( $comparable_base . '/' ) ) {
        // Short links are under a base path (e.g., https://mywordpress.com/s/key)
        // And the current request matches this base path.
        $short_key = substr( $requested_path, strlen( $comparable_base . '/' ) );
    }


    if ( !empty($short_key) ) {
        // Validate short_key: typically alphanumeric, no slashes.
        // This simple check prevents trying to look up things like 'wp-admin' if base is empty.
        if (preg_match('/^[a-zA-Z0-9]+$/', $short_key)) {

            // Load the URL Shortener class if not already loaded
            if ( ! class_exists( 'LinkWiz_URLShortener' ) ) {
                // Ensure LINKWIZ_PLUGIN_DIR is defined (should be from index.php)
                if ( ! defined('LINKWIZ_PLUGIN_DIR') ) {
                    // Fallback or error if not defined, though it should be.
                    // This might occur if this file is somehow hit before index.php fully sets up constants.
                    error_log('LinkWiz SaaS: LINKWIZ_PLUGIN_DIR not defined during redirection.');
                    return;
                }
                $url_shortener_path = LINKWIZ_PLUGIN_DIR . 'lib/url-shortening/url-shortener.php';
                if ( file_exists( $url_shortener_path ) ) {
                    require_once $url_shortener_path;
                } else {
                    // Log error, but don't break the site for visitors
                    error_log('LinkWiz SaaS: LinkWiz_URLShortener class not found during redirection.');
                    return;
                }
            }

            $shortener = new LinkWiz_URLShortener();
            $long_url = $shortener->getLongURL($short_key);

            if ( $long_url ) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'linkwiz_urls';

                // Increment visit count
                $wpdb->query( $wpdb->prepare(
                    "UPDATE $table_name SET visit_count = visit_count + 1 WHERE short_key = %s",
                    $short_key
                ) );

                // Perform redirect
                // Use wp_safe_redirect to prevent redirecting to disallowed hosts if not careful,
                // but original_url should be validated on input. esc_url_raw was used.
                if ( wp_redirect( esc_url_raw( $long_url ), 301 ) ) { // 301 for permanent
                    exit;
                }
            }
            // If $long_url is not found for a valid-looking key, WordPress will proceed to its 404.
        }
    }
    // If not a short URL pattern we handle, do nothing and let WordPress continue.
}
?>
