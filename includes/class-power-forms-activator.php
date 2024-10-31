<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.powerformbuilder.com/
 * @since      1.0.0
 *
 * @package    Power_Forms
 * @subpackage Power_Forms/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Power_Forms
 * @subpackage Power_Forms/includes
 * @author     PressTigers <support@presstigers.com>
 */
class Power_Forms_Activator {

    /**
     * Funtion called during the plugin activation
     *
     * @since    1.0.0
     * @param void
     * @return void
     */
    public static function activate() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wpf_entries';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
		id mediumint(11) NOT NULL AUTO_INCREMENT,
		form_name tinytext NOT NULL,
		ip tinytext NOT NULL,
		form_id mediumint(11) NOT NULL,
                user_id mediumint(11) NULL DEFAULT NULL,
                created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);

        $table_name = $wpdb->prefix . 'wpf_entry_meta';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
		id mediumint(11) NOT NULL AUTO_INCREMENT,
                entry_name tinytext NOT NULL,		
                entry_value tinytext NOT NULL,
		entry_id mediumint(11) NOT NULL,
                created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);


        $table_name = $wpdb->prefix . 'wpf_gdpr_requested_data';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
		id mediumint(11) NOT NULL AUTO_INCREMENT,
                email tinytext NOT NULL,		
		status tinytext NOT NULL,
                slug text NOT NULL,
                requested_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);

        $table_name = $wpdb->prefix . 'wpf_gdpr_delete_data';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
		id mediumint(11) NOT NULL AUTO_INCREMENT,
                email tinytext NOT NULL,		
                data_id mediumint(11) NOT NULL,
		status tinytext NOT NULL,
                requested_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);

        if (get_option('power_form_builtin_template') == 'no') {
            update_option("power_form_builtin_template", 'no', '', true);
        } else {
            add_option("power_form_builtin_template", 'yes');
            $id = wp_insert_post(array(
                'post_type' => 'page',
                'post_status' => 'publish',
                'post_title' => __('GDPR - Request power forms data', 'power-forms'),
                'post_content' => '[POWER_FORMS_REQ_DATA]'
            ));
            add_option('opt-form-page-id', $id);
            add_option('opt-form-class', 'wppowerforms');
            add_option('opt-form-permission', 'all');
            add_option('opt-form-submit', 'Submit');
            add_option('opt-form-sucess-message', 'Thanks for contacting us! We will be in touch with you shortly.');
            add_option('opt-form-error-message', 'Something went wrong, please again later.');
            add_option('opt-form-site-key', '');
            add_option('opt-form-secret-key', '');
            add_option('opt-form-gdpr-checkbox-text', 'Please check this checkbox for collect your data that you submitted with power forms.');
            add_option('opt-form-gdpr-form-checkbox-text', 'I am agree with Terms and conditions of Power Form Builder');
        }
    }

}
