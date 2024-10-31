<?php
/**
 * The admin-specific functionality of the plugin for Captcha Meta View
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

global $post;
if (empty(get_post_meta($post->ID, 'pf_form_gdpr', true))) {
    update_post_meta($post->ID, 'pf_form_gdpr', 'yes');
    $pf_form_gdpr = get_post_meta($post->ID, 'pf_form_gdpr', true);
} else {
    $pf_form_gdpr = get_post_meta($post->ID, 'pf_form_gdpr', true);
}
?>
<p>
    <select name='pf_form_gdpr' id='pf_form_gdpr' style="width:100%">
        <?php
        $confirms = array('yes' => __('Yes','power-forms'), __('no','power-forms') => 'No');
        foreach ($confirms as $key => $value):
            ?>
            <option <?php
            if ($pf_form_gdpr == $key) {
                echo __('selected', 'power-forms');
            }
            ?>  value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value); ?></option>
            <?php endforeach; ?>
    </select>
    <span><?php echo esc_html__('By default GDPR Complience Checkbox will be included in form. please enter Text for checkbox in Settings.', 'power-forms'); ?></span>

</p>