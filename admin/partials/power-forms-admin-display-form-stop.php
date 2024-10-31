<?php
/**
 * The admin-specific functionality of the plugin for Stop Entries View
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
if (empty(get_post_meta($post->ID, 'pf_form_stop', true))) {
    update_post_meta($post->ID, 'pf_form_stop', 'no');
    $pf_form_stop = get_post_meta($post->ID, 'pf_form_stop', true);
} else {
    $pf_form_stop = get_post_meta($post->ID, 'pf_form_stop', true);
}
?>
<p>
    <select name='pf_form_stop' id='pf_form_stop' style="width:100%">
        <?php
        $confirms = array('no' => __('No','power-forms'), __('yes','power-forms') => 'Yes');
        foreach ($confirms as $key => $value):
            ?>
            <option <?php
            if ($pf_form_stop == $key) {
                echo __('selected', 'power-forms');
            }
            ?>  value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value); ?></option>
            <?php endforeach; ?>
    </select>
    <span><?php echo esc_html__('By default form will store the entries', 'power-forms'); ?></span>

</p>