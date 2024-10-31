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
if (empty(get_post_meta($post->ID, 'pf_form_captcha', true))) {
    update_post_meta($post->ID, 'pf_form_captcha', 'yes');
    $pf_form_captcha = get_post_meta($post->ID, 'pf_form_captcha', true);
} else {
    $pf_form_captcha = get_post_meta($post->ID, 'pf_form_captcha', true);
}
?>
<p>
    <select name='pf_form_captcha' id='pf_form_captcha' style="width:100%">
        <?php
        $confirms = array('yes' => __('Yes','power-forms'), __('no','power-forms') => 'No');
        foreach ($confirms as $key => $value):
            ?>
            <option <?php
            if ($pf_form_captcha == $key) {
                echo __('selected', 'power-forms');
            }
            ?>  value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value); ?></option>
            <?php endforeach; ?>
    </select>
    <span><?php echo esc_html__('By default reCAPTCHA will be included in form. please enter your credentials in Settings.', 'power-forms'); ?></span>

</p>