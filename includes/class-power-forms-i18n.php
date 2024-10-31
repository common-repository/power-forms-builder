<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.powerformbuilder.com/
 * @since      1.0.0
 *
 * @package    Power_Forms
 * @subpackage Power_Forms/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Power_Forms
 * @subpackage Power_Forms/includes
 * @author     PressTigers <support@presstigers.com>
 */
class Power_Forms_i18n {

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     * @param void
     * @return void
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
                'power-forms', false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

}
