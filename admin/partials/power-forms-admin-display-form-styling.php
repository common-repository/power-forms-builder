<?php
/**
 * The admin-specific functionality of the plugin for Form styling meta View
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
$power_forms_styling = get_post_meta($post->ID, 'pf_forms_styling', true);
?>
<div style="padding: 10px;background: #f1f1f1;color: #111111;margin-bottom: 5px;"><label style="display: block"><?php echo __(esc_attr('Background Color'), 'power-forms'); ?></label><input class="pf-color-picker" type="text" id="power_forms_styling_bgcolor" name="power_forms_styling[bgcolor]" value="<?php echo isset($power_forms_styling['bgcolor']) ? esc_attr($power_forms_styling['bgcolor']) : esc_attr('#f1f1f1'); ?>" /></div>
<div style="padding: 10px;background: #f1f1f1;color: #111111;margin-bottom: 5px;"><label style="display: block"><?php echo __(esc_attr('Text Color'), 'power-forms'); ?></label><input class="pf-color-picker" type="text" id="power_forms_styling_textcolor" name="power_forms_styling[textcolor]" value="<?php echo isset($power_forms_styling['textcolor']) ? esc_attr($power_forms_styling['textcolor']) : esc_attr('#000000'); ?>" /></div>

<div style="padding: 10px;background: #f1f1f1;color: #111111;margin-bottom: 5px;"><label style="display: block"><?php echo __(esc_attr('Submit Button Background Color'), 'power-forms'); ?></label><input class="pf-color-picker" type="text" id="power_forms_styling_bgcolor" name="power_forms_styling[submitbgcolor]" value="<?php echo isset($power_forms_styling['submitbgcolor']) ? esc_attr($power_forms_styling['submitbgcolor']) : esc_attr('#0085ba'); ?>" /></div>
<div style="padding: 10px;background: #f1f1f1;color: #111111;margin-bottom: 5px;"><label style="display: block"><?php echo __(esc_attr('Submit Button Text Color'), 'power-forms'); ?></label><input class="pf-color-picker" type="text" id="power_forms_styling_textcolor" name="power_forms_styling[submittextcolor]" value="<?php echo isset($power_forms_styling['submittextcolor']) ? esc_attr($power_forms_styling['submittextcolor']) : esc_attr('#FFFFFF'); ?>" /></div>
<div style="padding: 10px;background: #f1f1f1;color: #111111;margin-bottom: 5px;"><label style="display: block"><?php echo __(esc_attr('Submit Button Width (%)'), 'power-forms'); ?></label><input style="width: 100%" type="number" id="power_forms_styling_submit_width" name="power_forms_styling[submitwidth]" value="<?php echo isset($power_forms_styling['submitwidth']) ? esc_attr(intval($power_forms_styling['submitwidth'])) : esc_attr(intval('0')); ?>" /></div>

<div style="padding: 10px;background: #f1f1f1;color: #111111;margin-bottom: 5px;">
    <label style="display: block"><?php echo __(esc_attr('Submit Button Position'), 'power-forms'); ?></label>
    <select style="width: 100%" id="power_forms_styling_submit_position" name="power_forms_styling[submitposition]">
        <option <?php if (isset($power_forms_styling['submitposition']) && $power_forms_styling['submitposition'] == 'left') { echo __('selected', 'power-forms'); } ?> value="<?php echo esc_attr('left'); ?>"><?php echo esc_attr('Left'); ?></option>
        <option <?php if (isset($power_forms_styling['submitposition']) && $power_forms_styling['submitposition'] == 'center') { echo __('selected', 'power-forms'); } ?> value="<?php echo esc_attr('center'); ?>"><?php echo esc_attr('Center'); ?></option>
        <option <?php if (isset($power_forms_styling['submitposition']) && $power_forms_styling['submitposition'] == 'right') { echo __('selected', 'power-forms'); } ?> value="<?php echo esc_attr('right'); ?>"><?php echo esc_attr('Right'); ?></option>
    </select> 
</div>

<div style="padding: 10px;background: #f1f1f1;color: #111111;margin-bottom: 5px;"><label style="display: block"><?php echo __(esc_attr('Padding'), 'power-forms'); ?></label><input style="width: 100%" type="number" id="power_forms_styling_padding" name="power_forms_styling[padding]" value="<?php echo isset($power_forms_styling['padding']) ? esc_attr(intval($power_forms_styling['padding'])) : esc_attr(intval('20')); ?>" /></div>
<div style="padding: 10px;background: #f1f1f1;color: #111111;margin-bottom: 5px;"><label style="display: block"><?php echo __(esc_attr('Border Radius'), 'power-forms'); ?></label><input style="width: 100%" type="number" id="power_forms_styling_bdr" name="power_forms_styling[bdr]" value="<?php echo isset($power_forms_styling['bdr']) ? esc_attr(intval($power_forms_styling['bdr'])) : esc_attr(intval('5')); ?>" /></div>

