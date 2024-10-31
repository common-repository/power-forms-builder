<?php
/**
 * The admin-specific functionality of the plugin for managing the settings.
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
class Power_Forms_Settings {

    /**
     * Constructor
     * 
     * @since   1.0
     * 
     * @param   void
     * @return void
     * 
     */
    public function __construct() {

        add_action('admin_menu', array($this, 'add_plugin_page'));
    }

    /**
     * For adding setting menu.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return void
     * 
     */
    public function add_plugin_page() {
        add_submenu_page('edit.php?post_type=powerform', esc_attr__('Settings', 'power-forms'), esc_attr__('Settings', 'power-forms'), 'manage_options', 'power-form-settings', array($this, 'settings_options_page'));
    }

    /**
     * For Display settings page.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return void
     * 
     */
    public function settings_options_page() {
        ?>
        <div class="pfbcontainer">
            <header class="codrops-header">
                <h1>PowerFormBuilder Settings <span>WordPress Contact Form Plugin PowerFormBuilder is the ultimate FREE and intuitive FORM creation tool for WordPress</span></h1>
                <p class="support">Your browser does not support <strong>flexbox</strong>! <br />Please view this demo with a <strong>modern browser</strong>.</p>
            </header>
            <section>
                <div class="tabs tabs-style-linebox">
                    <nav id="stickynavbar">
                        <ul>
                            <?php
                            do_action('power_forms_before_settings_tabs');
                            ?>
                            <li class="tab-current"><a href="#section-underline-1" class=""><span>General</span></a></li>
                            <li class=""><a href="#section-underline-2" class=""><span>GDPR</span></a></li>
                            <li class=""><a href="#section-underline-3" class=""><span>SMTP</span></a></li>
                            <li class=""><a href="#section-underline-4" class=""><span>Recaptcha</span></a></li>
                            <?php
                            do_action('power_forms_after_settings_tabs');
                            ?>
                        </ul>
                    </nav>
                    <div class="content-wrap" style="width: 100%">
                        <?php
                        do_action('power_forms_before_settings_tab_section');
                        ?>
                        <section id="section-underline-1" class="content-current">
                            <?php
                            do_action('power_forms_before_global_settings_tab_content');
                            require_once plugin_dir_path(dirname(__FILE__)) . 'includes/settings/power-forms-global-settings.php';
                            do_action('power_forms_after_global_settings_tab_content');
                            ?>
                        </section>
                        <section id="section-underline-2" class="">
                            <?php
                            do_action('power_forms_before_gdpr_settings_tab_content');
                            require_once plugin_dir_path(dirname(__FILE__)) . 'includes/settings/power-forms-gdpr-settings.php';
                            do_action('power_forms_after_gdpr_settings_tab_content');
                            ?>
                        </section>
                        <section id="section-underline-3" class="">
                            <?php
                            do_action('power_forms_before_smtp_settings_tab_content');
                            require_once plugin_dir_path(dirname(__FILE__)) . 'includes/settings/power-forms-smtp-settings.php';
                            do_action('power_forms_after_smtp_settings_tab_content');
                            ?>
                        </section>
                        <section id="section-underline-4" class="">
                            <?php
                            do_action('power_forms_before_recaptcha_settings_tab_content');
                            require_once plugin_dir_path(dirname(__FILE__)) . 'includes/settings/power-forms-recaptcha-settings.php';
                            do_action('power_forms_after_recaptcha_settings_tab_content');
                            ?>
                        </section>
                        <?php
                        do_action('power_forms_after_settings_tab_section');
                        ?>
                    </div><!-- /content -->
                </div><!-- /tabs -->
            </section>
        </div>


        <?php
    }

}

$Power_Forms_Settings = new Power_Forms_Settings();
