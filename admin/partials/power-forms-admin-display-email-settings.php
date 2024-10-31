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
$pf_email_setting = json_decode(get_post_meta($post->ID, 'pf_email_setting', true));
?>
<div id="power_forms_container_unique pf_email_settings" class="power_forms_container container-fluid" style="padding: 20px;background: #f1f1f1;">
    <legend style="font-weight: bold;"><?php esc_html__('You can use these mail-tags for composing email template after submission of this form:','power-forms'); ?></legend>
    <div id="tagsDiv" style="padding: 10px 0px 10px 0px">
        <?php
        $power_forms_form_fieldss = maybe_unserialize(get_post_meta($post->ID, 'pf_form_fields', true));
        if ($power_forms_form_fieldss) {
            $i = 0;
            foreach ($power_forms_form_fieldss as $key => $attr) {
                if (!empty($attr['pf_label'])) {
                    if (substr($attr['pf_title'], 0, 7) == 'pf_file') {
                        echo '<span style="margin-right:10px">[pf_File_' . $i . ']</span>';
                        $i++;
                    } else {
                        if($attr['pf_label'] != 'H1' && $attr['pf_label'] != 'H2' && $attr['pf_label'] != 'H3' && $attr['pf_label'] != 'H4' && $attr['pf_label'] != 'H5' && $attr['pf_label'] != 'H6' && $attr['pf_label'] != 'p' && $attr['pf_label'] != 'hr' && $attr['pf_label'] != 'br' && $attr['pf_label'] != 'html')
                        echo '<span style="margin-right:10px">[' . esc_attr ($attr['pf_title']) . ']</span>';
                    }
                }
            }
        }
        ?>
    </div>
    <label class="pf_label"><?php _e('To:', 'power-forms'); ?></label><input type="text" class="emailsetting_inputs" id="pf_email_to"  name="pf_email_to" placeholder="Email / [pf_field_number]" value="<?php
    if (!empty($pf_email_setting->pf_email_to)) {
        echo esc_attr__($pf_email_setting->pf_email_to, 'power-forms');
    }
    ?>" style="width: 100%" />
    <label class="pf_label"><?php _e('From:', 'power-forms'); ?></label><input type="text" class="emailsetting_inputs" id="pf_email_from" name="pf_email_from" placeholder="Email / [pf_field_number]" value="<?php
    if (!empty($pf_email_setting->pf_email_from)) {
        echo esc_attr__($pf_email_setting->pf_email_from, 'power-forms');
    }
    ?>" style="width: 100%" />
    <label class="pf_label"><?php _e('Subject:', 'power-forms'); ?></label><input type="text" class="emailsetting_inputs" id="pf_email_subject" name="pf_email_subject" placeholder="Email Subject / [pf_field_number]" value="<?php
    if (!empty($pf_email_setting->pf_email_subject)) {
        echo esc_attr__($pf_email_setting->pf_email_subject, 'power-forms');
    }
    ?>" style="width: 100%" />
    <label class="pf_label"><?php _e('Email Body :', 'power-forms'); ?></label>
    <?php
    if (!empty($pf_email_setting->pf_email_body)) {
        $content = __(str_replace("rn", "", wp_specialchars_decode($pf_email_setting->pf_email_body)), 'power-forms');
    } else {
        $content = '';
    }
    $editor_id = 'pf_email_body';
    $settings = array(
        'wpautop' => true,
        'media_buttons' => false,
        'quicktags' => array(
            'buttons' => 'strong,em,del,ul,ol,li,h1,h2,h3,h4,h5,h6,p,block,close'
        ),
    );
    wp_editor($content, $editor_id, $settings);
    ?>
    <label class="pf_label"><?php _e('File Attachments :', 'power-forms'); ?></label><textarea class="emailsetting_inputs" cols="10" rows="3" id="pf_email_attachment" placeholder="[file1],[file2]" name="pf_email_attachment"  style="width: 100%"><?php
        if (!empty($pf_email_setting->pf_email_attachment)) {
            echo esc_attr__($pf_email_setting->pf_email_attachment, 'power-forms');
        }
        ?></textarea>
</div>