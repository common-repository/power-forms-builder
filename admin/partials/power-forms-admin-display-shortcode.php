<?php
/**
 * The admin-specific functionality of the plugin for Form Builder shortcode View
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
wp_nonce_field('power_forms_meta_boxes_nonce', 'power_forms_meta_boxes_nonce');
global $post;
if (empty(get_post_meta($post->ID, 'pf_active_column', true))) {
    update_post_meta($post->ID, 'pf_active_column', 'one');
}
// Use get_post_meta to retrieve an existing value from the database.
$power_forms_shortcode_key = get_post_meta($post->ID, 'power_forms_shortcode_key', true);
if ($power_forms_shortcode_key) {
    $value = $power_forms_shortcode_key;
} else {
    $value = '[POWER_FORMS FORM_ID="' . $post->ID . '"]';
}

// Display the form, using the current value.
?>
<input readonly="" type="text" id="power_forms_shortcode" name="power_forms_shortcode" value="<?php echo esc_attr($value); ?>" style="width: 100%" />
