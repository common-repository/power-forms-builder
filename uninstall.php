<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 *
 * @link       https://www.powerformbuilder.com/
 * @since      1.0.0
 *
 * @package    Power_Forms
 */
// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}
delete_option('opt-form-class');
delete_option('opt-form-permission');
delete_option('opt-form-submit');
delete_option('opt-form-sucess-message');
delete_option('opt-form-error-message');
delete_option('opt-form-site-key');
delete_option('opt-form-secret-key');
delete_option('power_form_builtin_template');
delete_option('opt-form-gdpr-checkbox-text');
delete_option('opt-form-gdpr-form-checkbox-text');
delete_option('POWER_FORMS_VERSION');

$mycustomposts = get_posts(array('post_type' => 'powerform'));
foreach ($mycustomposts as $mypost) {
    delete_post_meta($mypost->ID, 'power_forms_shortcode_key');
    delete_post_meta($mypost->ID, 'pf_smart_confirmation');
    delete_post_meta($mypost->ID, 'pf_email_setting');
    delete_post_meta($mypost->ID, 'pf_form_captcha');
    delete_post_meta($mypost->ID, 'pf_forms_styling');
    delete_post_meta($mypost->ID, 'pf_form_fields');
    delete_post_meta($mypost->ID, 'pf_active_column');
    delete_post_meta($mypost->ID, 'pf_form_gdpr');
    delete_post_meta($mypost->ID, 'pf_form_stop');
    wp_delete_post($mypost->ID, true);
}
global $wpdb;
$table_name = $wpdb->prefix . 'wpf_entries';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

$table_names = $wpdb->prefix . 'wpf_entry_meta';
$sql = "DROP TABLE IF EXISTS $table_names";
$wpdb->query($sql);

$table_names = $wpdb->prefix . 'wpf_gdpr_requested_data';
$sql = "DROP TABLE IF EXISTS $table_names";
$wpdb->query($sql);

$table_names = $wpdb->prefix . 'wpf_gdpr_delete_data';
$sql = "DROP TABLE IF EXISTS $table_names";
$wpdb->query($sql);

$id = get_option('opt-form-page-id');
wp_delete_post($id,true);


delete_option('opt-form-page-id');
delete_option('salt');