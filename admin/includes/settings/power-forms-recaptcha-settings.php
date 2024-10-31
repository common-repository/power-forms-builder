<?php
/**
 * The admin-specific functionality of the plugin for display Captcha settings.
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
?>
<form method="POST" id="power_form_global_recaptcha_form" action="<?php echo esc_url($_SERVER["REQUEST_URI"] . '&tab=recaptcha_settings'); ?>">
    <table class="form-table">

        <tbody>

            <tr valign="top">
                <th scope="row"><label for="power_form_global_recaptcha_site_key"><?php echo esc_attr__('Site Key', 'power-forms'); ?></label></th>
                <td><input name="opt-form-site-key" type="text" id="power_form_global_recaptcha_site_key" value="<?php
                    if (get_option('opt-form-site-key')) {
                        echo get_option('opt-form-site-key');
                    } 
                    ?>" class="regular-text">
                    <p class="description"><?php esc_html_e('Please enter the google reCaptcha Site Key', 'power-forms'); ?></p></td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="power_form_global_form_secret_key"><?php echo esc_attr__('Secret Key', 'power-forms'); ?></label></th>
                <td><input name="opt-form-secret-key" type="text" id="power_form_global_form_secret_key" value="<?php
                    if (get_option('opt-form-secret-key')) {
                        echo get_option('opt-form-secret-key');
                    } 
                    ?>" class="regular-text">
                    <p class="description"><?php esc_html_e('Please enter the google reCaptcha Secret Key', 'power-forms'); ?></p></td>
            </tr> 

        </tbody>

    </table>

    <p class="submit"><input type="submit" name="power_form_global_form_recaptcha" id="power_form_recaptcha_form_submit" class="button button-primary" value="<?php echo esc_attr__('Save Changes', 'power-forms'); ?>"></p>
</form>