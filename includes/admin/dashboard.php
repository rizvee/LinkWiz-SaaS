<?php
// includes/admin/dashboard.php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Ensure this file is loaded within WordPress admin context
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( __( 'You do not have sufficient permissions to access this page.', 'linkwiz-saas' ) );
}

global $wpdb;
$table_name = $wpdb->prefix . 'linkwiz_urls';

// Get total links
$total_links = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
if (is_null($total_links)) {
    $total_links = 0; // In case table doesn't exist yet or other error
}

// Get total visits (placeholder for now, as visit tracking is not fully implemented)
// $total_visits = $wpdb->get_var( "SELECT SUM(visit_count) FROM $table_name" );
// if (is_null($total_visits)) {
//     $total_visits = 0;
// }
$total_visits_display = __('N/A (Tracking not yet active)', 'linkwiz-saas');

?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
            <div id="postbox-container-1" class="postbox-container">
                <div class="meta-box-sortables">
                    <div class="postbox">
                        <h2 class="hndle"><span><?php esc_html_e('Overview', 'linkwiz-saas'); ?></span></h2>
                        <div class="inside">
                            <p><?php esc_html_e('Welcome to the LinkWiz SaaS dashboard. Here, you can manage links, users, and settings for your system.', 'linkwiz-saas'); ?></p>
                        </div>
                    </div>

                    <div class="postbox">
                        <h2 class="hndle"><span><?php esc_html_e('Statistics', 'linkwiz-saas'); ?></span></h2>
                        <div class="inside">
                            <p><strong><?php esc_html_e('Total Short Links:', 'linkwiz-saas'); ?></strong> <?php echo absint($total_links); ?></p>
                            <p><strong><?php esc_html_e('Total Link Visits:', 'linkwiz-saas'); ?></strong> <?php echo esc_html($total_visits_display); ?></p>
                            <?php
                            // Future idea:
                            // <p><strong><?php esc_html_e('Most Popular Link:', 'linkwiz-saas'); ?></strong> <?php echo esc_html__('N/A', 'linkwiz-saas'); ?></p>
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="postbox-container-2" class="postbox-container">
                <div class="meta-box-sortables">
                    <div class="postbox">
                        <h2 class="hndle"><span><?php esc_html_e('Quick Actions', 'linkwiz-saas'); ?></span></h2>
                        <div class="inside">
                            <p>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=linkwiz_saas_manage_links' ) ); ?>" class="button button-primary">
                                    <?php esc_html_e('Create New Link', 'linkwiz-saas'); ?>
                                </a>
                            </p>
                            <p>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=linkwiz_saas_manage_links' ) ); ?>" class="button">
                                    <?php esc_html_e('Manage All Links', 'linkwiz-saas'); ?>
                                </a>
                            </p>
                            <p>
                                <a href="<?php echo esc_url( admin_url( 'users.php' ) ); ?>" class="button">
                                    <?php esc_html_e('Manage Users', 'linkwiz-saas'); ?>
                                </a>
                            </p>
                            <p>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=linkwiz_saas_settings' ) ); ?>" class="button">
                                    <?php esc_html_e('Configure Settings', 'linkwiz-saas'); ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    // Clean up the old structure if it exists
    // The old structure had <header>, <nav>, <container>, <footer> tags which are not standard for WP admin pages.
    // The structure above uses WordPress-like dashboard widgets.
    ?>
</div>
