<?php

/**
 * The public-facing functionality of the plugin for including assets and functionality files.
 *
 * @link       https://www.powerformbuilder.com/
 * @since      1.0.0
 *
 * @package    Power_Forms
 * @subpackage Power_Forms/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Power_Forms
 * @subpackage Power_Forms/public
 * @author     PressTigers <support@presstigers.com>
 */
class Power_Forms_Public {

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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/includes/class-power-forms-shortcode.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/includes/class-power-forms-gdpr-shortcode.php';
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/power-forms-public.min.css', array(), $this->version, 'all');
        if (isset($_GET['pflink'])) {
            wp_enqueue_style($this->plugin_name . '_data_table_bootstrap_min_css', plugin_dir_url(__FILE__) . 'css/power-forms-dataTables.bootstrap4.min.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name . '_data_table_bootstrap_css', plugin_dir_url(__FILE__) . 'css/power-forms-bootstrap.min.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name . '_font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        }
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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
        wp_enqueue_script($this->plugin_name . '_validate_js', plugin_dir_url(__FILE__) . 'js/power-forms-jquery.validate.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . '_data_table_js', plugin_dir_url(__FILE__) . 'js/power-forms-jquery.dataTables.min.js', array('jquery'), $this->version, false);
        if (isset($_GET['pflink'])) {

            wp_enqueue_script($this->plugin_name . '_data_ui_table_js', plugin_dir_url(__FILE__) . 'js/power-forms-dataTables.jqueryui.min.js', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name . '_data_table_button_js', plugin_dir_url(__FILE__) . 'js/power-forms-dataTables.buttons.min.js', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name . '_data_table_js_zip', plugin_dir_url(__FILE__) . 'js/power-forms-jszip.min.js', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name . '_data_table_js_pdf', plugin_dir_url(__FILE__) . 'js/power-forms-pdfmake.min.js', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name . '_data_table_js_fonts', plugin_dir_url(__FILE__) . 'js/power-forms-vfs_fonts.js', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name . '_data_table_js_print', plugin_dir_url(__FILE__) . 'js/power-forms-buttons.print.min.js', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name . '_data_table_js_buttons_ui', plugin_dir_url(__FILE__) . 'js/power-forms-buttons.jqueryui.min.js', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name . '_data_table_js_button_html', plugin_dir_url(__FILE__) . 'js/power-forms-buttons.html5.min.js', array('jquery'), $this->version, false);
            // For 1.0.2
            wp_enqueue_script($this->plugin_name . '_data_table_js_bootstrap.min', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), $this->version, false);
            // For 1.0.2
            wp_enqueue_script($this->plugin_name . '_data_table_js_dataTables_bootstrap4_min_html', plugin_dir_url(__FILE__) . 'js/power-forms-dataTables.bootstrap4.min.js', array('jquery'), $this->version, false);
        } else {
            wp_enqueue_script($this->plugin_name . '_data_table_button_js', plugin_dir_url(__FILE__) . 'js/power-forms-dataTables.buttons.min.js', array('jquery'), $this->version, false);
        }
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/power-forms-public.min.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'frontend_ajax_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
                )
        );
    }

}
