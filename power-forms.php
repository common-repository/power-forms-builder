<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.powerformbuilder.com/
 * @since             1.0.6
 * @package           Power_Forms
 *
 * @wordpress-plugin
 * Plugin Name:       Power Forms Builder
 * Plugin URI:        
 * Description:       WordPress Contact Form Plugin PowerFormBuilder is the ultimate FREE and intuitive FORM creation tool for WordPress.Create professional CONTACT FORMS, polls, surveys, and other types of forms for your site within minutes with absolutely no code using this EASY yet POWERFUL drag & drop form builder.
 * Version:           1.0.6
 * Author:            Derek Hamilton
 * Author URI:        https://www.powerformbuilder.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       power-forms
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 */
if(!get_option('POWER_FORMS_VERSION')){
   add_option('POWER_FORMS_VERSION', '1.0.6');  
}
define('POWER_FORMS_VERSION', '1.0.6');
define('POWER_FORMS_PLUGIN_NAME', 'Power Form Builder');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-power-forms-activator.php
 */
function activate_power_forms() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-power-forms-activator.php';
    Power_Forms_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-power-forms-deactivator.php
 */
function deactivate_power_forms() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-power-forms-deactivator.php';
    Power_Forms_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_power_forms');
register_deactivation_hook(__FILE__, 'deactivate_power_forms');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-power-forms.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function plugin_add_settings_link($links) {
    $settings_link = '<a href="edit.php?post_type=powerform&page=power-form-settings">' . __('Settings') . '</a>';
    array_push($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'plugin_add_settings_link');

function plugin_add_preimuim_link($links) {
    $settings_link = '<a href="https://www.powerformbuilder.com/pricing/" target="_blank">' . __('Go Premium') . '</a>';
    array_push($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'plugin_add_preimuim_link');

function run_power_forms() {

    $plugin = new Power_Forms();
    $plugin->run();
}

run_power_forms();
