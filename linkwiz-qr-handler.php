<?php
// linkwiz-qr-handler.php

// Attempt to load WordPress environment
// This path might need adjustment if the plugin is in a deeper subdirectory of wp-content/plugins
$wp_load_path = dirname(__FILE__, 4) . '/wp-load.php'; // Assuming plugin is like /wp-content/plugins/linkwiz-saas/
if ( ! defined('ABSPATH') && file_exists( $wp_load_path ) ) {
    require_once( $wp_load_path );
}

// Basic security check: ensure WordPress environment is loaded.
// More robust checks like nonces or capability checks might be needed for sensitive QR codes,
// but for publicly accessible short links, this might be okay.
// if ( ! defined('ABSPATH') ) {
//     // http_response_code(403); // Forbidden
//     // die('WordPress environment not loaded.');
//     // For now, allow access even if WP isn't loaded, as it's just generating a QR from a key.
//     // The actual security is that the key must exist and point to a real URL.
// }


$short_key = isset($_GET['key']) ? sanitize_text_field($_GET['key']) : '';

if (empty($short_key)) {
    http_response_code(400); // Bad Request
    die('Error: Short key not provided.');
}

// Load necessary class files manually. In a full setup, an autoloader would handle this.
$url_shortener_class_path = dirname(__FILE__) . '/lib/url-shortening/url-shortener.php';
$qr_generator_class_path = dirname(__FILE__) . '/lib/qr-code/qr-code-generator.php';

if (file_exists($url_shortener_class_path)) {
    require_once $url_shortener_class_path;
} else {
    http_response_code(500);
    die('Error: URL Shortener class not found.');
}

if (file_exists($qr_generator_class_path)) {
    require_once $qr_generator_class_path;
} else {
    http_response_code(500);
    die('Error: QR Code Generator class not found.');
}

// It's better if LinkWiz_URLShortener doesn't rely on global $wpdb directly when called from outside WP admin context
// For now, this will only work if $wpdb is available (i.e. wp-load.php was successful)
// A better approach for LinkWiz_URLShortener would be to allow $wpdb to be passed in, or initialize it if not passed.
// However, given our current structure of LinkWiz_URLShortener, it initializes $wpdb itself.

$url_to_encode = '';
if (class_exists('LinkWiz_URLShortener')) {
    $shortener = new LinkWiz_URLShortener(); // Assumes $wpdb is available if wp-load.php worked
    // The getShortURLFromKey method constructs the full URL, which is what we need for the QR code
    $url_to_encode = $shortener->getShortURLFromKey($short_key);

    if (empty($url_to_encode)) {
         // Check if the key itself is valid by trying to get the long URL.
         // This ensures we don't generate QR codes for non-existent keys.
        if (defined('ABSPATH') && class_exists('LinkWiz_URLShortener')) { // only if WP loaded
            $long_url_check = $shortener->getLongURL($short_key);
            if (empty($long_url_check)) {
                http_response_code(404); // Not Found
                die('Error: Invalid or unknown short key.');
            }
            // If long URL exists, but getShortURLFromKey returned empty (e.g. domain not set),
            // we might fall back to a simpler representation if absolutely necessary, or error out.
            // For now, if getShortURLFromKey fails to construct a URL, we error.
             http_response_code(500);
             die('Error: Could not construct URL for QR code from key.');
        } else {
            // If WP isn't loaded, we can't validate the key with the DB easily via the class method.
            // This is a fallback, less secure, assumes key is valid and constructs a possible URL.
            // This part is problematic if the domain option isn't set and site_url() isn't available.
            // For now, we'll rely on getShortURLFromKey which itself has fallbacks.
        }
    }
} else {
    http_response_code(500);
    die('Error: LinkWiz_URLShortener class did not load correctly.');
}


if (class_exists('QRCodeGenerator')) {
    $qr_generator = new QRCodeGenerator();
    $qr_code_data = $qr_generator->generateQRCode($url_to_encode, 150); // size 150px

    // For our placeholder library, this is a string.
    // For a real library, this would be binary PNG data.
    // If it were real PNG data:
    // header('Content-Type: image/png');
    // echo $qr_code_data;

    // Since it's a placeholder string:
    header('Content-Type: text/plain'); // Or text/html to see it easily in browser
    echo "QR Code Data (Placeholder for image):
";
    echo $qr_code_data;

} else {
    http_response_code(500);
    die('Error: QRCodeGenerator class not found.');
}

exit;
?>
