<?php
/**
 * The admin-specific functionality of the plugin for Form Builder smart confirmation meta View
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
if (empty(get_post_meta($post->ID, 'pf_smart_confirmation', true))) {
    update_post_meta($post->ID, 'pf_smart_confirmation', 'noredirect');
    $pf_smart_confirmation = get_post_meta($post->ID, 'pf_smart_confirmation', true);
} else {
    $pf_smart_confirmation = get_post_meta($post->ID, 'pf_smart_confirmation', true);
}
?>
<p>
    <select name='pf_smart_confirmation' id='pf_smart_confirmation' style="width:100%">
        <?php
        $confirms = array('noredirect' => esc_attr__('No Redirect','power-forms'), 'thankyou' => esc_attr__('Redirect to thankyou page','power-forms'));
        foreach ($confirms as $key => $value):
            ?>
            <option <?php
            if ($pf_smart_confirmation == $key) {
                echo 'selected';
            }
            ?>  value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value); ?></option>
            <?php endforeach; ?>
    </select>
    <span><?php echo esc_html__('Redirection after form submission','power-forms'); ?></span>
</p>