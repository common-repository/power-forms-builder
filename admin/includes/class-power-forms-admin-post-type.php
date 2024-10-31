<?php

/**
 * The admin-specific functionality of the plugin for adding Power Form Meta and Saving the meta with form save.
 *
 * @link       https://www.powerformbuilder.com/
 * @since      1.0.0
 *
 * @package    Power_Forms
 * @subpackage Power_Forms/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Power_Forms
 * @subpackage Power_Forms/admin
 * @author     PressTigers <support@presstigers.com>
 */
class Power_Forms_Admin_Post_Type {

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct() {

        add_action('init', array($this, 'power_forms_create_post_type_power_form'));

        add_action('add_meta_boxes', array($this, 'power_forms_register_meta_boxes'));

        add_action('save_post', array($this, 'power_forms_save_metabox_callback'));

        add_action('admin_enqueue_scripts', array($this, 'power_forms_load_admin_custom_js_css'));

        add_filter('manage_powerform_posts_columns', array($this, 'power_forms_set_custom_powerform_columns'));

        add_action('manage_powerform_posts_custom_column', array($this, 'power_forms_custom_powerform_column'), 10, 2);

        add_filter('wp_privacy_personal_data_exporters', array($this, 'pfb_register_exporter'));

        add_filter('wp_privacy_personal_data_erasers', array($this, 'pfb_register_erasers'));

        add_action('admin_footer', array($this, 'pfb_admin_footer_function'));

        add_action('admin_menu', array($this, 'go_add_menu_items'));
    }

    /**
     * Funtion for registering premium Submenu
     * 
     * @since   1.0
     * 
     * @param   int     $exporters    WordPress exporter
     * @return  $exporters
     * 
     */
    function go_add_menu_items() {
        global $submenu;
        $permalink = 'https://www.powerformbuilder.com/pricing/';
        $submenu['edit.php?post_type=powerform'][] = array('Go Premium', 'manage_options', $permalink);
    }

    function pfb_admin_footer_function() {
        global $typenow;
        if ($typenow == 'powerform') {
            require plugin_dir_path(dirname(__FILE__)) . 'partials/power-forms-details-modal.php';
        }
    }

    /**
     * Funtion for registering gdpr exporter
     * 
     * @since   1.0
     * 
     * @param   int     $exporters    WordPress exporter
     * @return  $exporters
     * 
     */
    function pfb_register_exporter($exporters) {
        $exporters[] = array(
            'exporter_friendly_name' => __('Power Form Builder Data'),
            'callback' => array($this, 'pfb_data_exporter'),
        );
        return $exporters;
    }

    /**
     * Funtion for registering gdpr erasers
     * 
     * @since   1.0
     * 
     * @param   int     $erasers    WordPress erasers
     * @return  $erasers
     * 
     */
    function pfb_register_erasers($erasers = array()) {
        $erasers[] = array(
            'eraser_friendly_name' => __('Power Form Builder Data'),
            'callback' => array($this, 'pfb_data_eraser'),
        );
        return $erasers;
    }

    /**
     * Funtion for exporting the pf data
     * 
     * @since   1.0
     * 
     * @param   int     $email_address
     * @param   int     $page
     * @return  $exported_items
     * 
     */
    function pfb_data_exporter($email_address, $page = 1) {
        $export_items = array();
        $idss = self::get_entry_id($email_address);
        $array = array();
        foreach ($idss as $key => $value) {
            $array[] = $value['entry_id'];
        }
        $commaids = implode(",", $array);
        $results = self::get_entry_meta($commaids);
        $newItems = array();
        if (!empty($idss))
            array_push($newItems, array('name' => __("Entry", 'power-forms'), 'value' => 1));
        $myval = 0;
        $entryNo = 1;
        foreach ($results as $key => $value) {
            $myval = $myval ? $myval : $value['entry_id'];
            if ($myval == $value['entry_id']) {
                array_push($newItems, array('name' => __($value['entry_name'], 'power-forms'), 'value' => $value['entry_value']));
            } else {
                $entryNo++;
                $myval = $value['entry_id'];
                array_push($newItems, array('name' => __('Entry', 'power-forms'), 'value' => $entryNo));
                array_push($newItems, array('name' => __($value['entry_name'], 'power-forms'), 'value' => $value['entry_value']));
            }
        }
        $data_to_export[] = array(
            'group_id' => 'pfb_data',
            'group_label' => __('Power Form Builder Data', 'power-forms'),
            'item_id' => 'user_data',
            'data' => $newItems
        );

        return array(
            'data' => $data_to_export,
            'done' => true,
        );
    }

    /**
     * Funtion for erasing the pf data
     * 
     * @since   1.0
     * 
     * @param   int     $email_address
     * @param   int     $page
     * @return  $message
     * 
     */
    function pfb_data_eraser($email_address, $page = 1) {
        if (empty($email_address)) {
            return array(
                'items_removed' => false,
                'items_retained' => false,
                'messages' => array(),
                'done' => true,
            );
        }
        $messages = array();
        $items_removed = false;
        $items_retained = false;

        $delete_ids = self::get_entry_id($email_address);
        $array = array();
        foreach ($delete_ids as $key => $value) {
            $array[] = $value['entry_id'];
        }
        foreach ($array as $id) {
            self::delete_entry($id);
        }
        $messages[] = 'Power Form Builder Data Sucessfully Removed';
        $items_removed = true;

        return array(
            'items_removed' => $items_removed,
            'items_retained' => $items_retained,
            'messages' => $messages,
            'done' => true,
        );
    }

    public static function get_entry_id($email) {
        global $wpdb;
        $results = $wpdb->get_results("SELECT entry_id FROM {$wpdb->prefix}wpf_entry_meta WHERE entry_value = '$email'", ARRAY_A);
        if ($results) {
            return $results;
        } else {
            return '';
        }
    }

    public static function get_entry_meta($id) {
        global $wpdb;
        if (empty($id)) {
            return '';
        } else {
            $results = $wpdb->get_results("SELECT entry_id,entry_name,entry_value FROM {$wpdb->prefix}wpf_entry_meta WHERE entry_id IN($id)", ARRAY_A);
            return $results;
        }
    }

    public static function delete_entry($id) {
        global $wpdb;
        $wpdb->delete("{$wpdb->prefix}wpf_entries", ['id' => $id], ['%d']);
        $wpdb->delete("{$wpdb->prefix}wpf_entry_meta", ['entry_id' => $id], ['%d']);
    }

    /**
     * Funtion for setting up the custom column in custom post type powerforms.
     * 
     * @since   1.0
     * 
     * @param   int     $columns    Column Header
     * @return  $columns
     * 
     */
    function power_forms_set_custom_powerform_columns($columns) {
        unset($columns['author']);
        unset($columns['date']);
        $columns['powerform_shortcode'] = __(esc_attr('Shortcode'), 'power-forms');
        $columns['powerform_confirmation'] = __(esc_attr('Smart Confirmation'), 'power-forms');
        $columns['powerform_re'] = __(esc_attr('reCAPTCHA?'), 'power-forms');
        $columns['powerform_export'] = __(esc_attr('Export Entries'), 'power-forms');

        return $columns;
    }

    /**
     * 
     * Funtion for setting up values for the custom column in custom post type powerforms.
     * 
     * @since   1.0
     * 
     * @param   int     $columns    Column Header
     * @param   int     $post_id    POST ID
     * @return  void
     * 
     */
    function power_forms_custom_powerform_column($column, $post_id) {
        switch ($column) {

            case 'powerform_shortcode' :
                echo __(esc_attr(get_post_meta($post_id, 'power_forms_shortcode_key', true)), 'power-forms');
                break;
            case 'powerform_confirmation' :
                if (get_post_meta($post_id, 'pf_smart_confirmation', true) == 'noredirect') {
                    echo __(esc_attr('No Redirect'), 'power-forms');
                } else {
                    echo __(esc_attr('Redirect To Thankyou Page.'), 'power-forms');
                }
                break;
            case 'powerform_re' :
                if (get_post_meta($post_id, 'pf_form_captcha', true) == 'yes') {
                    echo __(esc_attr('Yes'), 'power-forms');
                } else {
                    echo __(esc_attr('No'), 'power-forms');
                }
                break;
            case 'powerform_export' :
                if ($this->power_form_entries_count($post_id)) {
                    echo '<a class="page-title-action submissions" style="top:10px" id="submissions" href="#" data-formid="' . $post_id . '">' . __(esc_attr('Donwload'), 'power-forms') . '</a>';
                }else{
                    echo 'No Entries';
                }
                break;
        }
    }

    function power_form_entries_count($formid) {
        global $wpdb;
        $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpf_entries WHERE form_id=$formid", ARRAY_A);
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * Funtion for adding admin javascripts and styles into powerforms plugin.
     * 
     * @since   1.0
     * 
     * @param   int     $hook    Current Page Hook
     * @return  void
     * 
     */
    function power_forms_load_admin_custom_js_css($hook) {
        global $typenow;
        if ($typenow == 'powerform') {
            wp_enqueue_style('power-form-builder-css', plugin_dir_url(__FILE__) . '../css/form-builder.min.css', array(), POWER_FORMS_VERSION, 'all');
            wp_enqueue_style('power-form-jquery-ui-css', plugin_dir_url(__FILE__) . '../css/power-forms-jquery-ui.min.css', array(), POWER_FORMS_VERSION, 'all');
            wp_enqueue_style('power-form-alertify.core', plugin_dir_url(__FILE__) . '../css/power-forms-alertify.core.min.css', array(), POWER_FORMS_VERSION, 'all');
            wp_enqueue_style('power-form-alertify.default', plugin_dir_url(__FILE__) . '../css/power-forms-alertify.default.min.css', array(), POWER_FORMS_VERSION, 'all');
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('power-form-alertify.min', plugin_dir_url(__FILE__) . '../js/power-forms-alertify.min.js', array('jquery'), POWER_FORMS_VERSION, true);
            wp_enqueue_script('power-form-demo', plugin_dir_url(__FILE__) . '../js/form-builder.min.js', array('jquery', 'wp-color-picker', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-droppable', 'jquery-ui-draggable', 'jquery-ui-tabs'), POWER_FORMS_VERSION, true);
            if ($hook == 'powerform_page_power-form-gdpr-requestsss') {
                wp_enqueue_style('power-form-new-tabs', plugin_dir_url(__FILE__) . '../css/pf_tabs.css', array(), POWER_FORMS_VERSION, 'all');
            }
            if ($hook == 'powerform_page_power-form-settings' || $hook == 'powerform_page_power-form-entries' || $hook == 'powerform_page_power-form-gdpr-requests' || $hook == 'powerform_page_power-form-gdpr-delete' || $hook == 'powerform_page_pfb-license') {

                wp_enqueue_style('power-form-new-settings-tabs-normalize', plugin_dir_url(__FILE__) . '../css/normalize.css', array(), POWER_FORMS_VERSION, 'all');
                wp_enqueue_style('power-form-new-settings-tabs-demo', plugin_dir_url(__FILE__) . '../css/demo.css', array(), POWER_FORMS_VERSION, 'all');
                wp_enqueue_style('power-form-new-settings-tabs-tabs', plugin_dir_url(__FILE__) . '../css/tabs.css', array(), POWER_FORMS_VERSION, 'all');
                wp_enqueue_style('power-form-new-settings-tabs-tabstyles', plugin_dir_url(__FILE__) . '../css/tabstyles.css', array(), POWER_FORMS_VERSION, 'all');

                wp_enqueue_script('power-form-normalize', plugin_dir_url(__FILE__) . '../js/modernizr.custom.js', array('jquery'), POWER_FORMS_VERSION, true);
                wp_enqueue_script('power-form-cbpFWTabs', plugin_dir_url(__FILE__) . '../js/cbpFWTabs.js', array('jquery'), POWER_FORMS_VERSION, true);
                wp_enqueue_script('power-form-cbpFWTabs-custom', plugin_dir_url(__FILE__) . '../js/cbpFWTabs-custom.js', array('jquery'), POWER_FORMS_VERSION, true);
            }
            $info = __("Please save the from before leave otherwise you can not undo", 'power-forms');
            $field_success_message = __("Field Sucessfully Added", 'power-forms');
            $field_del_message = __("Field Sucessfully Deleted", 'power-forms');
            $form_save_message = __("Form Saved", 'power-forms');
            $form_sort_message = __("Form Sorted", 'power-forms');
            $form_column = __("Column switched to", 'power-forms');
            $error = __("Error ! Please try again.", 'power-forms');

            wp_localize_script('power-form-demo', 'frontend_ajax_object', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'information_message' => $info,
                'field_success_message' => $field_success_message,
                'field_del_message' => $field_del_message,
                'form_save_message' => $form_save_message,
                'form_column' => $form_column,
                'form_sort_message' => $form_sort_message,
                'form_error' => $error
                    )
            );
        }
    }

    /**
     * Funtion for the creation of power forms custom post type..
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     */
    function power_forms_create_post_type_power_form() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'partials/power-forms-admin-display-post-type.php';
    }

    /**
     * Funtion for the adding metaboxes of power forms custom post type.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     */
    function power_forms_register_meta_boxes() {
        add_meta_box('pf-form-styling', esc_attr__('Form Styling', 'power-forms'), array($this, 'power_forms_form_styling_callback'), 'powerform', 'side', 'default', null);
        add_meta_box('pf-shortcode', esc_attr__('Shortcode', 'power-forms'), array($this, 'power_forms_shortcode_display_callback'), 'powerform', 'side', 'high', null);
        add_meta_box('pf-smart-confirmation', esc_attr__('Smart Confirmation', 'power-forms'), array($this, 'power_forms_smart_display_callback'), 'powerform', 'side', 'default', null);
        add_meta_box('pf-form-builder', esc_attr__('Form Builder', 'power-forms'), array($this, 'power_forms_form_builder_display_callback'), 'powerform', 'normal', 'high', null);
        add_meta_box('pf-email-settings', esc_attr__('Email Settings', 'power-forms'), array($this, 'power_forms_email_settings_display_callback'), 'powerform', 'normal', 'default', null);
        add_meta_box('pf-capchta', esc_attr__('Need Form reCAPTCHA?', 'power-forms'), array($this, 'power_forms_need_captcha_callback'), 'powerform', 'side', 'default', null);
        add_meta_box('pf-complience', esc_attr__('GDPR Complience?', 'power-forms'), array($this, 'power_forms_gdpr_callback'), 'powerform', 'side', 'default', null);
        add_meta_box('pf-stopentries', esc_attr__('Stop Entries?', 'power-forms'), array($this, 'power_forms_stop_callback'), 'powerform', 'side', 'default', null);
    }

    /**
     * Funtion for handling the form stop entries power forms custom post type.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     */
    function power_forms_stop_callback() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'partials/power-forms-admin-display-form-stop.php';
    }

    /**
     * Funtion for handling the form gdpr power forms custom post type.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     */
    function power_forms_gdpr_callback() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'partials/power-forms-admin-display-form-gdpr.php';
    }

    /**
     * Funtion for handling the form styling power forms custom post type.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     */
    function power_forms_form_styling_callback() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'partials/power-forms-admin-display-form-styling.php';
    }

    /**
     * Funtion for handling the Need Form reCAPTCHA? metabox power forms custom post type.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     */
    function power_forms_need_captcha_callback() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'partials/power-forms-admin-display-captch.php';
    }

    /**
     * Funtion for handling the Email Settings metabox power forms custom post type.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     */
    function power_forms_email_settings_display_callback() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'partials/power-forms-admin-display-email-settings.php';
    }

    /**
     * Funtion for handling the Smart Confirmation metabox power forms custom post type.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     */
    function power_forms_smart_display_callback() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'partials/power-forms-admin-display-smart-confirmation.php';
    }

    /**
     * Funtion for handling the Shortcode metabox power forms custom post type.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     */
    function power_forms_shortcode_display_callback() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'partials/power-forms-admin-display-shortcode.php';
    }

    /**
     * Funtion for handling the Form Builder metabox power forms custom post type.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     */
    function power_forms_form_builder_display_callback() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'partials/power-forms-admin-display-form-builder.php';
    }

    /**
     * Recursive sanitation for an array
     * 
     * @param $array
     *
     * @return mixed
     */
    function recursive_sanitize_text_field($array) {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = recursive_sanitize_text_field($value);
            } else {
                $value = sanitize_text_field($value);
            }
        }

        return $array;
    }

    /**
     * Recursive sanitation for an array
     * 
     * @param void
     *
     * @return array
     */
    function pf_allowed_html() {

        $allowed_tags = array(
            'a' => array(
                'class' => array(),
                'href' => array(),
                'rel' => array(),
                'title' => array(),
            ),
            'abbr' => array(
                'title' => array(),
            ),
            'b' => array(),
            'blockquote' => array(
                'cite' => array(),
            ),
            'cite' => array(
                'title' => array(),
            ),
            'code' => array(),
            'del' => array(
                'datetime' => array(),
                'title' => array(),
            ),
            'dd' => array(),
            'div' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'dl' => array(),
            'dt' => array(),
            'em' => array(),
            'h1' => array(),
            'h2' => array(),
            'h3' => array(),
            'h4' => array(),
            'h5' => array(),
            'h6' => array(),
            'i' => array(),
            'img' => array(
                'alt' => array(),
                'class' => array(),
                'height' => array(),
                'src' => array(),
                'width' => array(),
            ),
            'li' => array(
                'class' => array(),
            ),
            'ol' => array(
                'class' => array(),
            ),
            'p' => array(
                'class' => array(),
            ),
            'q' => array(
                'cite' => array(),
                'title' => array(),
            ),
            'span' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'strike' => array(),
            'strong' => array(),
            'ul' => array(
                'class' => array(),
            ),
        );

        return $allowed_tags;
    }

    /**
     * Funtion for handling the meta and for saving the power forms custom post type meta in to database.
     * 
     * @since   1.0
     * 
     * @param   $post_id  POST ID
     * @return  void
     */
    function power_forms_save_metabox_callback($post_id) {

        if (isset($_POST['power_forms_meta_boxes_nonce'])) {
            if (!wp_verify_nonce($_POST['power_forms_meta_boxes_nonce'], 'power_forms_meta_boxes_nonce')) {
                return;
            }
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        if (isset($_POST['post_type']) && 'powerform' === $_POST['post_type']) {

            $shortcode = isset($_POST['power_forms_shortcode']) ? sanitize_text_field($_POST['power_forms_shortcode']) : '';

            $pf_smart_confirmation = isset($_POST['pf_smart_confirmation']) ? sanitize_text_field($_POST['pf_smart_confirmation']) : '';

// We are taking token like => [email_786] in this field that's why we implement the text sanitization but in any case we also applied a condition for dealing with the email sanitization as well
            if (is_email($_POST['pf_email_to'])) {
                $pf_email_to = isset($_POST['pf_email_to']) ? sanitize_email($_POST['pf_email_to']) : '';
            } else {
                $pf_email_to = isset($_POST['pf_email_to']) ? sanitize_text_field($_POST['pf_email_to']) : '';
            }

// We are taking token like => [email_786] in this field that's why we implement the text sanitization but in any case we also applied a condition for dealing with the email sanitization as well
            if (is_email($_POST['pf_email_from'])) {
                $pf_email_from = isset($_POST['pf_email_from']) ? sanitize_email($_POST['pf_email_from']) : '';
            } else {
                $pf_email_from = isset($_POST['pf_email_from']) ? sanitize_text_field($_POST['pf_email_from']) : '';
            }
            $pf_email_subject = isset($_POST['pf_email_subject']) ? sanitize_text_field($_POST['pf_email_subject']) : '';

            $allowtags = $this->pf_allowed_html();
            $pf_email_body = isset($_POST['pf_email_body']) ? wp_kses($_POST['pf_email_body'], $allowtags) : '';

            $pf_email_attachment = isset($_POST['pf_email_attachment']) ? sanitize_text_field($_POST['pf_email_attachment']) : '';

            $pf_form_captcha = isset($_POST['pf_form_captcha']) ? sanitize_text_field($_POST['pf_form_captcha']) : '';

            $pf_form_gdpr = isset($_POST['pf_form_gdpr']) ? sanitize_text_field($_POST['pf_form_gdpr']) : '';

            $pf_form_stop = isset($_POST['pf_form_stop']) ? sanitize_text_field($_POST['pf_form_stop']) : '';

            $power_forms_styling = isset($_POST['power_forms_styling']) ? $this->recursive_sanitize_text_field($_POST['power_forms_styling']) : '';


            $pf_email_setting = array(
                'pf_email_to' => $pf_email_to,
                'pf_email_from' => $pf_email_from,
                'pf_email_subject' => $pf_email_subject,
                'pf_email_body' => $pf_email_body,
                'pf_email_attachment' => $pf_email_attachment,
            );
// Hook Before the Meta Save
            $arg = array(
                'post_id' => $post_id,
                'shortcode' => $shortcode,
                'pf_smart_confirmation' => $pf_smart_confirmation,
                'pf_email_to' => $pf_email_to,
                'pf_email_from' => $pf_email_from,
                'pf_email_subject' => $pf_email_subject,
                'pf_email_body' => $pf_email_body,
                'pf_email_attachment' => $pf_email_attachment,
                'power_forms_styling' => $power_forms_styling,
                'pf_form_captcha' => $pf_form_captcha,
                'pf_form_gdpr' => $pf_form_gdpr,
                'pf_form_stop' => $pf_form_stop,
            );
            apply_filters('before_update_powerform', $arg);

            $shortcode = '[POWER_FORMS FORM_ID="' . $post_id . '"]';

            update_post_meta($post_id, 'power_forms_shortcode_key', $shortcode);
            update_post_meta($post_id, 'pf_smart_confirmation', $pf_smart_confirmation);
            update_post_meta($post_id, 'pf_email_setting', json_encode($pf_email_setting));
            update_post_meta($post_id, 'pf_form_captcha', $pf_form_captcha);
            update_post_meta($post_id, 'pf_form_gdpr', $pf_form_gdpr);
            update_post_meta($post_id, 'pf_form_stop', $pf_form_stop);
            update_post_meta($post_id, 'pf_forms_styling', $power_forms_styling);

            // Hook After the Meta Save
            apply_filters('after_update_powerform', $arg);
        }
    }

}

new Power_Forms_Admin_Post_Type();
