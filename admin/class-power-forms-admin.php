<?php

/**
 * The admin-specific functionality of the plugin for including the assets and functionality files.
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
class Power_Forms_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        /**
         * The code that is used by plugin for generating the form fields dynamically
         * This action is documented in admin/includes/class-power-forms-form.php
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/includes/class-power-forms-form.php';
        /**
         * The code that is used by plugin for adding Power Form post type meta and saving the meta
         * This action is documented in admin/includes/class-power-forms-admin-post-type.php
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/includes/class-power-forms-admin-post-type.php';
        /**
         * The code that is used by plugin for managing the ajax requests and saving the form fields in database
         * This action is documented in admin/includes/class-power-forms-fields.php
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/includes/class-power-forms-fields.php';
        /**
         * The code that is used by plugin for dealing the Power Form SMTP mailer
         * This action is documented in admin/partials/class-power-forms-mailer.php
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/class-power-forms-mailer.php';
        /**
         * The code that is used by plugin for managing the Power Forms Settings
         * This action is documented in admin/includes/class-power-forms-settings.php
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/includes/class-power-forms-settings.php';
        /**
         * The code that is used by plugin for managing Power Form Entries
         * This action is documented in admin/includes/class-power-forms-entries.php
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/includes/class-power-forms-entries.php';
        /**
         * The code that is used by plugin for managing Power Form GDPR Requests
         * This action is documented in admin/includes/class-power-forms-gdpr-requests.php
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/includes/class-power-forms-gdpr-requests.php';
        /**
         * The code that is used by plugin for managing Power Form GDPR Delete Requests
         * This action is documented in admin/includes/class-power-forms-gdpr-requests_delete.php
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/includes/class-power-forms-gdpr-requests_delete.php';
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Power_Forms_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Power_Forms_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/power-forms-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name . '-font-fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Power_Forms_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Power_Forms_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/power-forms-admin.js', array('jquery'), $this->version, false);
    }

}
