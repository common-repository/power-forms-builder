<?php
/**
 * The admin-specific functionality of the plugin for display Global settings.
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
<form method="post" id="power_form_global_form_submits" action="#">
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="power_form_global_form_class"><?php esc_attr_e('Form Class', 'power-forms'); ?></label></th>
                <td><input name="opt-form-class" type="text" id="power_form_global_form_class" value="<?php
                    if (get_option('opt-form-class')) {
                        echo esc_attr(get_option('opt-form-class'));
                    } else {
                        echo esc_attr('wppowerforms');
                    }
                    ?>" class="regular-text">
                    <p class="description"><?php esc_html_e('Please enter the form classes that you want to add in form.', 'power-forms'); ?></p></td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="power_form_global_form_permission"><?php esc_attr_e('Form Permissions', 'power-forms'); ?></label></th>
                <td>
                    <select id="power_form_global_form_permission" name="opt-form-permission" class="">
                        <option value="<?php echo esc_attr('all'); ?>" <?php
                        if (get_option('opt-form-permission') == 'all') {
                            echo 'selected';
                        } else {
                            echo '';
                        }
                        ?>><?php esc_attr_e('For All', 'power-forms'); ?></option>
                        <option value="<?php echo esc_attr('log'); ?>" <?php
                        if (get_option('opt-form-permission') == 'log') {
                            echo 'selected';
                        } else {
                            echo '';
                        }
                        ?>><?php esc_attr_e('Loggedin Users', 'power-forms'); ?></option>
                        <option value="<?php echo esc_attr('unlog'); ?>" <?php
                        if (get_option('opt-form-permission') == 'unlog') {
                            echo 'selected';
                        } else {
                            echo '';
                        }
                        ?>><?php esc_attr_e('Not Loggedin users', 'power-forms'); ?></option>
                    </select>
                    <p class="description"><?php esc_html_e('Please give permissions for the forms, whome you want to display the form.', 'power-forms'); ?></p></td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="power_form_global_form_success_message"><?php esc_attr_e('Success Message', 'power-forms'); ?></label></th>
                <td><input name="opt-form-sucess-message" type="text" id="power_form_global_form_success_message" value="<?php
                    if (get_option('opt-form-sucess-message')) {
                        echo esc_attr(get_option('opt-form-sucess-message'));
                    } else {
                        echo esc_attr('Thanks for contacting us! We will be in touch with you shortly.');
                    }
                    ?>" class="regular-text">
                    <p class="description"><?php esc_html_e('Please enter the form success message.', 'power-forms'); ?></p></td>
            </tr> 

            <tr valign="top">
                <th scope="row"><label for="power_form_global_form_error_message"><?php esc_attr_e('Error Message', 'power-forms'); ?></label></th>
                <td><input name="opt-form-error-message" type="text" id="power_form_global_form_error_message" value="<?php
                    if (get_option('opt-form-error-message')) {
                        echo esc_attr(get_option('opt-form-error-message'));
                    } else {
                        echo esc_attr('Something went wrong, please again later.');
                    }
                    ?>" class="regular-text">
                    <p class="description"><?php esc_html_e('Please enter the form error message.', 'power-forms'); ?></p></td>
            </tr> 

            <tr valign="top">
                <th scope="row"><label for="power_form_global_form_gdp_checkbox_text"><?php esc_attr_e('Request Data Page Consent', 'power-forms'); ?></label></th>
                <td>
                    <textarea style="width:100%" cols="10" rows="10" name="opt-form-gdpr-checkbox-text" id="power_form_global_form_gdp_checkbox_text"><?php
                        if (get_option('opt-form-gdpr-checkbox-text')) {
                            echo esc_attr(get_option('opt-form-gdpr-checkbox-text'));
                        } else {
                            echo esc_attr('Please check this checkbox for collect your data that you submitted with power forms.');
                        }
                        ?></textarea>
                    <p class="description"><?php esc_html_e('Please enter the GDPR request data page consent.', 'power-forms'); ?></p></td>
            </tr> 

            <tr valign="top">
                <th scope="row"><label for="power_form_global_form_gdpr_form"><?php esc_attr_e('Form Consent', 'power-forms'); ?></label></th>
                <td>
                    <?php
                    if (!empty(get_option('opt-form-gdpr-form-checkbox-text'))) {
                        $content = __(str_replace("rn", "", wp_specialchars_decode(get_option('opt-form-gdpr-form-checkbox-text'))), 'power-forms');
                    } else {
                        $content = '';
                    }
                    $editor_id = 'opt-form-gdpr-form-checkbox-text';
                    $settings = array(
                        'wpautop' => true,
                        'media_buttons' => false,
                        'quicktags' => array(
                            'buttons' => 'strong,em,del,ul,ol,li,h1,h2,h3,h4,h5,h6,p,block,close'
                        ),
                    );
                    wp_editor($content, $editor_id, $settings);
                    ?>

                    <p class="description"><?php esc_html_e('Please enter the GDPR Form Consent', 'power-forms'); ?></p></td>
            </tr> 

        </tbody>

    </table>
    <p class="submit"><input type="submit" name="power_form_global_form_submit" id="power_form_global_form_submit" class="button button-primary" value="<?php _e('Save Changes', 'power-forms'); ?>"></p>
</form>