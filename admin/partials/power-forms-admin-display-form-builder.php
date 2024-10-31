<?php
/**
 * The admin-specific functionality of the plugin for Form Builder meta View
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
$power_forms_form_key = get_post_meta($post->ID);
$activeClasses = '';
if (get_post_meta($post->ID, 'pf_active_column', true) == 'one') {

    $activeClasses .= "col-sm-12 col-lg-12 col-md-12 power_forms_columns_class";
} else if (get_post_meta($post->ID, 'pf_active_column', true) == 'two') {

    $activeClasses .= "col-sm-6 col-lg-6 col-md-6 power_forms_columns_class";
} else if (get_post_meta($post->ID, 'pf_active_column', true) == 'three') {

    $activeClasses .= "col-sm-4 col-lg-4 col-md-4 power_forms_columns_class";
}
?>
<div class="power_forms_container" id="power_forms_container_unique">
    <div class="col-xs-12 col-lg-4 col-md-4" style="margin-bottom: 20px;margin-top: 10px">
        <div class="power_forms_controls_class" id="power_forms_controls" style="">

            <div id="tabs" class="pf_tabs">
                <ul class="pf_tabs_ul">
                    <li class="active hvr-overline-from-left basic"><a href="#tabs-1"><?php echo esc_attr__('Fields', 'power-forms'); ?></a></li>
                    <li class="hvr-overline-from-left field_options"><a href="#tabs-2"><?php echo esc_attr__('Field Options', 'power-forms'); ?></a></li> 
                </ul>
                <?php
                global $post;
                $postID = $post->ID;
                $cols = get_post_meta($postID, 'pf_active_column', true);
                $row = 1;
                $col = $cols;

                $basicFields = array('text', 'email', 'textarea', 'number', 'select', 'radio', 'checkbox', 'url', 'password', 'file', 'date', 'time', 'tel');
                $layoutFields = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'hr', 'br', 'html');

                $html = '<div id="dataFields" class="power_forms_columns_class_fields" data-pop-row="' . $row . '" data-pop-col="' . $col . '" data-pop-postID="' . $postID . '">';
                $html .= '<div id="tabs-1"><ul class="field_button_listing">';
                foreach ($basicFields as $key => $value) {
                    $html .= '<li data-col-id="power_forms_columns_' . $col . '" data-pop-row="' . $row . '" data-pop-col="' . $col . '" data-pop-postID="' . $postID . '" data-field="pf_' . $value . '_field" id="pf_' . $value . '_field" class="draggable  hvr-overline-from-left pfaddfield">' . esc_attr__(ucfirst($value), 'power-forms') . '</li>';
                }
                $html .= '</ul><h4>Layout Fields <a href="#" title="Pricing fields allows you to add fields for adding layout elements such as H1,H2 etc.."><i class="fa fa-question-circle"></i></a></h4><ul class="field_button_listing">';
                foreach ($layoutFields as $key => $value) {
                    $html .= '<li data-col-id="power_forms_columns_' . $col . '" data-pop-row="' . $row . '" data-pop-col="' . $col . '" data-pop-postID="' . $postID . '" data-field="pf_' . $value . '_field" id="pf_' . $value . '_field" class="draggable  hvr-overline-from-left pfaddfield">' . esc_attr__(ucfirst($value), 'power-forms') . '</li>';
                }
                $html .= '</ul></div>';
                $html .= '<div id="tabs-2">';
                $html .= esc_attr__('Please select a field', 'power-forms');
                $html .= '</div>';

                $html .= '</div>';

                echo $html;
                ?>
            </div>

        </div>
    </div>
    <div class="col-xs-12 col-lg-8 col-md-8" style="margin-bottom: 20px;">
        <div class="message"></div>
        <div class="power_forms_controls_class" id="power_forms_controls">
            <ul class="col-navigation">
                <li id="col-1-image" data-postid="<?php echo $post->ID; ?>" data-column-active="one" data-source="power_forms_columns_one" data-target="col-sm-12 col-lg-12 col-md-12 power_forms_columns_class" class="switchColumn hvr-overline-from-left <?php if (get_post_meta($post->ID, 'pf_active_column', true) == 'one') { ?> liactive <?php } ?>"><span id="spanColum1" class="<?php if (get_post_meta($post->ID, 'pf_active_column', true) == 'one') { ?> colum-active <?php } ?>"></span></li>
                <li id="col-2-image" data-postid="<?php echo $post->ID; ?>" data-column-active="two" data-source="power_forms_columns_two" data-target="col-sm-6 col-lg-6 col-md-6 power_forms_columns_class" class="switchColumn hvr-overline-from-left <?php if (get_post_meta($post->ID, 'pf_active_column', true) == 'two') { ?> liactive <?php } ?>"><span id="spanColum2" class="<?php if (get_post_meta($post->ID, 'pf_active_column', true) == 'two') { ?> colum-active <?php } ?>"></span></li>
                <li id="col-3-image" data-postid="<?php echo $post->ID; ?>" data-column-active="three" data-source="power_forms_columns_three" data-target="col-sm-4 col-lg-4 col-md-4 power_forms_columns_class" class="switchColumn hvr-overline-from-left <?php if (get_post_meta($post->ID, 'pf_active_column', true) == 'three') { ?> liactive <?php } ?>"><span id="spanColum3" class="<?php if (get_post_meta($post->ID, 'pf_active_column', true) == 'three') { ?> colum-active <?php } ?>"></span></li>
                <li id="col-4-right" data-postid="<?php echo $post->ID; ?>" data-postID="<?php echo $post->ID; ?>" style="float: right;color: #fff;background: #0085ba;border-radius: 3px 3px 1px 1px;    border: none;" class="saveForm hvr-overline-from-left"><?php _e('Save', 'power-forms'); ?></li>
                <li id="col-4-right" data-postid="<?php echo $post->ID; ?>" data-postID="<?php echo $post->ID; ?>" style="float: right;color: #fff;background: #0085ba;border-radius: 3px 3px 1px 1px;margin-right: 5px;    border: none;" class="previewForm hvr-overline-from-left"><?php _e('Preview', 'power-forms'); ?></li>
            </ul>
        </div>
        <div class="mainDiv col-sm-12 col-lg-12 col-md-12" style="padding: 0px;">
            <div class="<?php
            if (!empty($activeClasses)) {
                echo __($activeClasses, 'power-forms');
            } else {
                echo __('power_forms_columns_class', 'power-forms');
            }
            ?>" id="power_forms_columns_one">
                <section class="item_container" id="sortable">
                    <?php
                    $power_forms_form_fields = maybe_unserialize(get_post_meta($post->ID, 'pf_form_fields', true));
                    if ($power_forms_form_fields) {
                        ?>
                        <?php
                        $backendfield = '';
                        foreach ($power_forms_form_fields as $key => $attr) {
                            $finalArray = json_encode($attr); //convert array to a JSON string
                            $dataMeta = htmlspecialchars($finalArray, ENT_QUOTES);
                            $form = new PhpFormBuilder();
                            $form->set_att('form_element', false);
                            if ($attr['pf_column'] == 'one') {
                                $key = $attr['pf_field_id'];
                                $backendfield .= '<section class ="' . $attr['pf_field_type'] . ' addedFields ui-state-default" id= "' . $key . '" data-meta-attributes="' . $dataMeta . '" data-meta-key = "' . $key . '" pf-data-post-id = "' . $attr['pf_post_id'] . '" data-pf-column-id = "' . $attr['pf_column_id'] . '" data-pf-column-number = "' . $attr['pf_column'] . '" data-pf-field-type="' . $attr['pf_field_type'] . '">';
                                $backendfield .= '<span  class="showonhover" style = "float: right;">';
                                $backendfield .= '<span data-meta-key = "' . $key . '" pf-data-post-id = "' . $attr['pf_post_id'] . '" data-pf-column-id = "' . $attr['pf_column_id'] . '" data-pf-column-number = "' . $attr['pf_column'] . '" class = "dashicons dashicons-trash pf_delete_field pfeditdeletefield"></span></span>';
                                if ($attr['pf_required'] == "yes") {
                                    $required = true;
                                } else {
                                    $required = false;
                                }
                                $form->add_input(
                                        isset($attr['pf_label']) ? $attr['pf_label'] : '', array(
                                    'type' => explode("_", $attr['pf_field_type'])[1],
                                    'disable' => true,
                                    'class' => isset($attr['pf_field_class']) ? $attr['pf_field_class'] : '',
                                    'placeholder' => isset($attr['pf_placeholder']) ? $attr['pf_placeholder'] : '',
                                    'value' => isset($attr['pf_dvalue']) ? $attr['pf_dvalue'] : '',
                                    'min' => isset($attr['pf_min']) ? $attr['pf_min'] : '',
                                    'max' => isset($attr['pf_max']) ? $attr['pf_max'] : '',
                                    'required' => isset($required) ? $required : '',
                                    'id' => isset($attr['pf_field_id']) ? $attr['pf_field_id'] : '',
                                    'column' => isset($attr['pf_column']) ? $attr['pf_column'] : '',
                                    'options' => isset($attr['pf_options']) ? $attr['pf_options'] : '',
                                    'filed_text' => isset($attr['pf_filed_text']) ? $attr['pf_filed_text'] : '',
                                    'theight' => isset($attr['pf_theight']) ? $attr['pf_theight'] : '',
                                        ), isset($attr['pf_title']) ? $attr['pf_title'] : '');
                                $formHtml = $form->build_form(false);
                                $backendfield .= $formHtml;
                                $backendfield .= '</section>';
                            }
                        }
                        echo $backendfield;
                        ?>
                    <?php } ?>
                </section>
                <section class="item_add col-xs-12">
                </section>

            </div>
            <div class="<?php
            if (!empty($activeClasses)) {
                echo __($activeClasses, 'power-forms');
            } else {
                echo __('power_forms_columns_class', 'power-forms');
            }
            ?>" <?php if (get_post_meta($post->ID, 'pf_active_column', true) == 'one') { ?> style="display: none" <?php } ?> id="power_forms_columns_two">
                <section class="item_container" id="sortable1">
                    <?php
                    $power_forms_form_fields = maybe_unserialize(get_post_meta($post->ID, 'pf_form_fields', true));
                    if ($power_forms_form_fields) {
                        ?>
                        <?php
                        $backendfield = '';
                        foreach ($power_forms_form_fields as $key => $attr) {
                            $finalArray = json_encode($attr); //convert array to a JSON string
                            $dataMeta = htmlspecialchars($finalArray, ENT_QUOTES);
                            $form1 = new PhpFormBuilder();
                            $form1->set_att('form_element', false);
                            $form1->set_att('active_column', get_post_meta($post->ID, 'pf_active_column', true));
                            if ($attr['pf_column'] == 'two') {
                                $key = $attr['pf_field_id'];
                                $backendfield .= '<section class ="' . $attr['pf_field_type'] . ' addedFields ui-state-default" id= "' . $key . '" data-meta-attributes="' . $dataMeta . '" data-meta-key = "' . $key . '" pf-data-post-id = "' . $attr['pf_post_id'] . '" data-pf-column-id = "' . $attr['pf_column_id'] . '" data-pf-column-number = "' . $attr['pf_column'] . '" data-pf-field-type="' . $attr['pf_field_type'] . '">';
                                $backendfield .= '<span  class="showonhover" style = "float: right;">';
                                $backendfield .= '<span data-meta-key = "' . $key . '" pf-data-post-id = "' . $attr['pf_post_id'] . '" data-pf-column-id = "' . $attr['pf_column_id'] . '" data-pf-column-number = "' . $attr['pf_column'] . '" class = "dashicons dashicons-trash pf_delete_field pfeditdeletefield"></span></span>';
                                if ($attr['pf_required'] == "yes") {
                                    $required = true;
                                } else {
                                    $required = false;
                                }
                                $formHtml = $form1->add_input(
                                        isset($attr['pf_label']) ? $attr['pf_label'] : '', array(
                                    'type' => explode("_", $attr['pf_field_type'])[1],
                                    'disable' => true,
                                    'class' => isset($attr['pf_field_class']) ? $attr['pf_field_class'] : '',
                                    'placeholder' => isset($attr['pf_placeholder']) ? $attr['pf_placeholder'] : '',
                                    'value' => isset($attr['pf_dvalue']) ? $attr['pf_dvalue'] : '',
                                    'min' => isset($attr['pf_min']) ? $attr['pf_min'] : '',
                                    'max' => isset($attr['pf_max']) ? $attr['pf_max'] : '',
                                    'required' => isset($required) ? $required : '',
                                    'id' => isset($attr['pf_field_id']) ? $attr['pf_field_id'] : '',
                                    'column' => isset($attr['pf_column']) ? $attr['pf_column'] : '',
                                    'options' => isset($attr['pf_options']) ? $attr['pf_options'] : '',
                                    'filed_text' => isset($attr['pf_filed_text']) ? $attr['pf_filed_text'] : '',
                                    'theight' => isset($attr['pf_theight']) ? $attr['pf_theight'] : '',
                                        ), isset($attr['pf_title']) ? $attr['pf_title'] : '');
                                $formHtml = $form1->build_form(false);
                                $backendfield .= $formHtml;
                                $backendfield .= '</section>';
                            }
                        }
                        echo $backendfield;
                        ?>
                    <?php } ?>
                </section>
                <section class="item_add col-xs-12">
                </section>
            </div>
            <div class="<?php
            if (!empty($activeClasses)) {
                echo __($activeClasses, 'power-forms');
            } else {
                echo __('power_forms_columns_class', 'power-forms');
            }
            ?>" <?php if (get_post_meta($post->ID, 'pf_active_column', true) == 'one' || get_post_meta($post->ID, 'pf_active_column', true) == 'two') { ?> style="display: none" <?php } ?> id="power_forms_columns_three">
                <section class="item_container" id="sortable2">
                    <?php
                    $power_forms_form_fields = maybe_unserialize(get_post_meta($post->ID, 'pf_form_fields', true));
                    if ($power_forms_form_fields) {
                        ?>
                        <?php
                        $backendfield = '';
                        foreach ($power_forms_form_fields as $key => $attr) {
                            $finalArray = json_encode($attr); //convert array to a JSON string
                            $dataMeta = htmlspecialchars($finalArray, ENT_QUOTES);
                            $form2 = new PhpFormBuilder();
                            $form2->set_att('form_element', false);
                            $form2->set_att('active_column', get_post_meta($post->ID, 'pf_active_column', true));
                            if ($attr['pf_column'] == 'three') {
                                $key = $attr['pf_field_id'];
                                $backendfield .= '<section class ="' . $attr['pf_field_type'] . ' addedFields ui-state-default" id= "' . $key . '" data-meta-attributes="' . $dataMeta . '" data-meta-key = "' . $key . '" pf-data-post-id = "' . $attr['pf_post_id'] . '" data-pf-column-id = "' . $attr['pf_column_id'] . '" data-pf-column-number = "' . $attr['pf_column'] . '" data-pf-field-type="' . $attr['pf_field_type'] . '">';
                                $backendfield .= '<span  class="showonhover" style = "float: right;">';
                                $backendfield .= '<span data-meta-key = "' . $key . '" pf-data-post-id = "' . $attr['pf_post_id'] . '" data-pf-column-id = "' . $attr['pf_column_id'] . '" data-pf-column-number = "' . $attr['pf_column'] . '" class = "dashicons dashicons-trash pf_delete_field pfeditdeletefield"></span></span>';
                                if ($attr['pf_required'] == "yes") {
                                    $required = true;
                                } else {
                                    $required = false;
                                }
                                $form2->add_input(
                                        isset($attr['pf_label']) ? $attr['pf_label'] : '', array(
                                    'type' => explode("_", $attr['pf_field_type'])[1],
                                    'disable' => true,
                                    'class' => isset($attr['pf_field_class']) ? $attr['pf_field_class'] : '',
                                    'placeholder' => isset($attr['pf_placeholder']) ? $attr['pf_placeholder'] : '',
                                    'value' => isset($attr['pf_dvalue']) ? $attr['pf_dvalue'] : '',
                                    'min' => isset($attr['pf_min']) ? $attr['pf_min'] : '',
                                    'max' => isset($attr['pf_max']) ? $attr['pf_max'] : '',
                                    'required' => isset($required) ? $required : '',
                                    'id' => isset($attr['pf_field_id']) ? $attr['pf_field_id'] : '',
                                    'column' => isset($attr['pf_column']) ? $attr['pf_column'] : '',
                                    'options' => isset($attr['pf_options']) ? $attr['pf_options'] : '',
                                    'filed_text' => isset($attr['pf_filed_text']) ? $attr['pf_filed_text'] : '',
                                    'theight' => isset($attr['pf_theight']) ? $attr['pf_theight'] : '',
                                        ), isset($attr['pf_title']) ? $attr['pf_title'] : '');
                                $formHtml = $form2->build_form(false);
                                $backendfield .= $formHtml;
                                $backendfield .= '</section>';
                            }
                        }
                        echo $backendfield;
                        ?>
                    <?php } ?>
                </section>
                <section class="item_add col-xs-12">
                </section>

            </div>
        </div>
    </div>
</div>