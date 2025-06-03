<?php
// templates/admin/manage-links.php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Ensure this file is loaded within WordPress admin
if ( ! current_user_can( 'manage_options' ) ) { // 'manage_options' is a common capability for admins
    wp_die( __( 'You do not have sufficient permissions to access this page.', 'linkwiz-saas' ) );
}

// Load the URL Shortener class if not already loaded
// This path might need adjustment based on final autoloader/include strategy
if ( ! class_exists( 'LinkWiz_URLShortener' ) ) {
    $url_shortener_path = plugin_dir_path( __FILE__ ) . '../../lib/url-shortening/url-shortener.php';
    if ( file_exists( $url_shortener_path ) ) {
        require_once $url_shortener_path;
    } else {
        wp_die( __( 'LinkWiz_URLShortener class not found.', 'linkwiz-saas' ) ); // Should not happen if files are in place
    }
}

$shortener = new LinkWiz_URLShortener();
$feedback_message = '';
$error_message = '';

// Handle form submission
if ( isset( $_POST['linkwiz_submit_new_url'] ) && check_admin_referer( 'linkwiz_add_new_url_action', 'linkwiz_add_new_url_nonce' ) ) {
    $long_url = isset( $_POST['long_url'] ) ? sanitize_text_field( stripslashes( $_POST['long_url'] ) ) : '';

    if ( ! empty( $long_url ) ) {
        $current_user_id = get_current_user_id();
        $result = $shortener->shortenURL( $long_url, $current_user_id );

        if ( is_wp_error( $result ) ) {
            $error_message = $result->get_error_message();
        } else {
            // $result is now an array: array('short_url' => '...', 'short_key' => '...')
            $created_short_url = $result['short_url'];
            $created_short_key = $result['short_key'];

            $qr_handler_url = '';
            if (defined('LINKWIZ_PLUGIN_FILE')) {
                $qr_handler_url = plugins_url( 'linkwiz-qr-handler.php?key=' . rawurlencode($created_short_key), LINKWIZ_PLUGIN_FILE );
            } else {
                // Fallback if LINKWIZ_PLUGIN_FILE is not defined (should not happen ideally)
                // This constructs a URL assuming 'linkwiz-saas' is the plugin directory name.
                $plugin_dir_name = basename(dirname(__FILE__, 2)); // Should be 'linkwiz-saas'
                $qr_handler_url = site_url('/wp-content/plugins/' . $plugin_dir_name . '/linkwiz-qr-handler.php?key=' . rawurlencode($created_short_key));
            }

            $feedback_message = sprintf(
                esc_html__( 'Short URL created: %1$s', 'linkwiz-saas' ),
                '<a href="' . esc_url( $created_short_url ) . '" target="_blank">' . esc_html( $created_short_url ) . '</a>'
            );
            $feedback_message .= '<br>' . sprintf(
                esc_html__( 'QR Code: %1$s', 'linkwiz-saas' ),
                '<a href="' . esc_url( $qr_handler_url ) . '" target="_blank">' . esc_html__( 'View QR Code', 'linkwiz-saas' ) . '</a>'
            );
        }
    } else {
        $error_message = __( 'Please enter a URL to shorten.', 'linkwiz-saas' );
    }
}
?>

<div class="wrap">
    <h1><?php echo esc_html__( 'Manage Links', 'linkwiz-saas' ); ?></h1>

    <?php if ( ! empty( $feedback_message ) ) : ?>
        <div id="message" class="updated notice notice-success is-dismissible">
            <p><?php echo wp_kses_post( $feedback_message ); ?></p>
        </div>
    <?php endif; ?>

    <?php if ( ! empty( $error_message ) ) : ?>
        <div id="message" class="error notice notice-error is-dismissible">
            <p><?php echo esc_html( $error_message ); ?></p>
        </div>
    <?php endif; ?>

    <h2><?php echo esc_html__( 'Add New Short Link', 'linkwiz-saas' ); ?></h2>
    <form method="post" action="">
        <?php wp_nonce_field( 'linkwiz_add_new_url_action', 'linkwiz_add_new_url_nonce' ); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <label for="long_url"><?php echo esc_html__( 'Original URL', 'linkwiz-saas' ); ?></label>
                </th>
                <td>
                    <input type="url" name="long_url" id="long_url" class="regular-text" value="" required />
                    <p class="description"><?php echo esc_html__( 'Enter the full URL you want to shorten (e.g., https://www.example.com/very/long/url).', 'linkwiz-saas' ); ?></p>
                </td>
            </tr>
        </table>

        <?php submit_button( esc_html__( 'Create Short Link', 'linkwiz-saas' ), 'primary', 'linkwiz_submit_new_url' ); ?>
    </form>

    <?php
    // Placeholder for displaying existing links - to be implemented later
    // <h2><?php echo esc_html__( 'Existing Links', 'linkwiz-saas' ); ?></h2>
    // <p><?php echo esc_html__( 'Functionality to list, edit, and delete links will be here.', 'linkwiz-saas' ); ?></p>
    ?>
</div>
