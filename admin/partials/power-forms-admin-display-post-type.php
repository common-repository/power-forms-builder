<?php

/**
 * The admin-specific functionality of the plugin for Form post type and for creating template and csv.
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
$labels = array(
    'name' => _x('Power Forms', 'Power Form General Name', 'power-forms'),
    'singular_name' => _x('Power Form', 'Power Form Singular Name', 'power-forms'),
    'menu_name' => __('Power Forms', 'power-forms'),
    'name_admin_bar' => __('Power Form', 'power-forms'),
    'archives' => __('Form Archives', 'power-forms'),
    'attributes' => __('Form Attributes', 'power-forms'),
    'parent_item_colon' => __('Parent Form:', 'power-forms'),
    'all_items' => __('All Forms', 'power-forms'),
    'add_new_item' => __('Add New Form', 'power-forms'),
    'add_new' => __('Add New', 'power-forms'),
    'new_item' => __('New Form', 'power-forms'),
    'edit_item' => __('Edit Form', 'power-forms'),
    'update_item' => __('Update Form', 'power-forms'),
    'view_item' => __('View Form', 'power-forms'),
    'view_items' => __('View Forms', 'power-forms'),
    'search_items' => __('Search Form', 'power-forms'),
    'not_found' => __('Not found', 'power-forms'),
    'not_found_in_trash' => __('Not found in Trash', 'power-forms'),
    'featured_image' => __('Featured Image', 'power-forms'),
    'set_featured_image' => __('Set featured image', 'power-forms'),
    'remove_featured_image' => __('Remove featured image', 'power-forms'),
    'use_featured_image' => __('Use as featured image', 'power-forms'),
    'insert_into_item' => __('Insert into item', 'power-forms'),
    'uploaded_to_this_item' => __('Uploaded to this item', 'power-forms'),
    'items_list' => __('Forms list', 'power-forms'),
    'items_list_navigation' => __('Forms list navigation', 'power-forms'),
    'filter_items_list' => __('Filter items list', 'power-forms'),
);
$args_powerform = array(
    'labels' => $labels,
    'hierarchical' => FALSE,
    'description' => sprintf(esc_html__('This is where you can create and manage %s.', 'power-forms'), 'Power Forms'),
    'public' => TRUE,
    'exclude_from_search' => FALSE,
    'publicly_queryable' => FALSE,
    'show_ui' => TRUE,
    'show_in_nav_menus' => TRUE,
    'menu_icon' => 'dashicons-format-aside',
    'capability_type' => 'post',
    'has_archive' => FALSE,
    'rewrite' => array('slug' => 'powerform', 'hierarchical' => TRUE, 'with_front' => FALSE),
    'query_var' => TRUE,
    'can_export' => TRUE,
    'supports' => array(
        'title',
    ),
);
// Hook for the customizing the Labels    
register_post_type("powerform", apply_filters("power_forms_post_type_arguments", $args_powerform));


if (get_option('power_form_builtin_template') == 'yes') {

    $formtitle = esc_attr('Contact Us');
    $my_post = array(
        'post_type' => 'powerform',
        'post_title' => $formtitle,
        'post_name' => 'contact-us',
        'post_content' => '',
        'post_status' => 'publish',
        'post_author' => 1,
    );
    $post_id = wp_insert_post($my_post);
    $pftitle = sanitize_key("pf_text" . '_' . rand(0, 1000));
    $pfemail = sanitize_key("pf_email" . '_' . rand(0, 1000));
    $pfsubject = sanitize_key("pf_text" . '_' . rand(0, 1000));
    $pftextarea = sanitize_key("pf_textarea" . '_' . rand(0, 1000));

    if (intval($post_id)) {

        $formfields = array(
            array(
                'pf_field_type' => "pf_text_field",
                'pf_column_id' => "power_forms_columns_one",
                'pf_column' => "one",
                'pf_row' => intval(1),
                'pf_post_id' => intval($post_id),
                'pf_title' => $pftitle,
                'pf_label' => "Name",
                'pf_dvalue' => "",
                'pf_help_text' => "",
                'pf_error_msg' => "",
                'pf_meta_key' => "",
                'pf_placeholder' => "Please enter a name",
                'pf_min' => "",
                'pf_max' => "",
                'pf_theight' => "",
                'pf_accept_html' => "no",
                'pf_options' => "",
                'pf_file_type' => "",
                'pf_file_length' => "",
                'pf_filed_text' => "",
                'pf_required' => "yes",
                'pf_field_class' => "name",
                'pf_field_id' => $pftitle
            ),
            array(
                'pf_field_type' => "pf_email_field",
                'pf_column_id' => "power_forms_columns_one",
                'pf_column' => "one",
                'pf_row' => 1,
                'pf_post_id' => $post_id,
                'pf_title' => $pfemail,
                'pf_label' => "Email",
                'pf_dvalue' => "",
                'pf_help_text' => "",
                'pf_error_msg' => "",
                'pf_meta_key' => "",
                'pf_placeholder' => "Please enter a email",
                'pf_min' => "",
                'pf_max' => "",
                'pf_theight' => "",
                'pf_accept_html' => "no",
                'pf_options' => "",
                'pf_file_type' => "",
                'pf_file_length' => "",
                'pf_filed_text' => "",
                'pf_required' => "yes",
                'pf_field_class' => "email",
                'pf_field_id' => $pfemail
            ),
            array(
                'pf_field_type' => "pf_text_field",
                'pf_column_id' => "power_forms_columns_one",
                'pf_column' => "one",
                'pf_row' => 1,
                'pf_post_id' => $post_id,
                'pf_title' => $pfsubject,
                'pf_label' => "Subject",
                'pf_dvalue' => "",
                'pf_help_text' => "",
                'pf_error_msg' => "",
                'pf_meta_key' => "",
                'pf_placeholder' => "Please enter a subject",
                'pf_min' => "",
                'pf_max' => "",
                'pf_theight' => "",
                'pf_accept_html' => "no",
                'pf_options' => "",
                'pf_file_type' => "",
                'pf_file_length' => "",
                'pf_filed_text' => "",
                'pf_required' => "yes",
                'pf_field_class' => "subject",
                'pf_field_id' => $pfsubject
            ),
            array(
                'pf_field_type' => "pf_textarea_field",
                'pf_column_id' => "power_forms_columns_one",
                'pf_column' => "one",
                'pf_row' => 1,
                'pf_post_id' => $post_id,
                'pf_title' => $pftextarea,
                'pf_label' => "Message",
                'pf_dvalue' => "",
                'pf_help_text' => "",
                'pf_error_msg' => "",
                'pf_meta_key' => "",
                'pf_placeholder' => "Please enter a message",
                'pf_min' => "",
                'pf_max' => "",
                'pf_theight' => 100,
                'pf_accept_html' => "no",
                'pf_options' => "",
                'pf_file_type' => "",
                'pf_file_length' => "",
                'pf_filed_text' => "",
                'pf_required' => "yes",
                'pf_field_class' => "",
                'pf_field_id' => $pftextarea
            ),
        );

        $power_forms_styling = array(
            'bgcolor' => '#F1F1F1',
            'textcolor' => '#444444',
            'submitbgcolor' => '#0085ba',
            'submittextcolor' => '#ffffff',
            'padding' => 20,
            'bdr' => 5,
        );

        add_post_meta($post_id, 'power_forms_shortcode_key', sanitize_text_field('[POWER_FORMS FORM_ID="' . $post_id . '"]'));
        add_post_meta($post_id, 'pf_active_column', sanitize_text_field('one'));
        add_post_meta($post_id, 'pf_form_fields', maybe_serialize($formfields));
        add_post_meta($post_id, 'pf_smart_confirmation', sanitize_text_field('thankyou'));
        add_post_meta($post_id, 'pf_email_setting', '{"pf_email_to":"[' . $pfemail . ']","pf_email_from":"support@powerformbuilder.com","pf_email_subject":"' . $formtitle . ' Query : [' . $pfsubject . ']","pf_email_body":"'.html_entity_decode("<h3>Dear [$pftitle] ,</h3>rn<h6>I hope this note finds you well. We will contact you within 24 hours.</h6>rn<h4>Best Regards,</h4>rn<h5>Team Power Form Builder.</h5>").'","pf_email_attachment":""}');
        add_post_meta($post_id, 'pf_form_captcha', sanitize_text_field('no'));
        add_post_meta($post_id, 'pf_form_gdpr', sanitize_text_field('yes'));
        add_post_meta($post_id, 'pf_forms_styling', recursive_sanitize_text_field($power_forms_styling));
        update_option("power_form_builtin_template", sanitize_text_field('no'));
    }
}

/**
 * Recursive sanitation for an array
 * 
 * @param $array
 *
 * @return mixed
 */
function recursive_sanitize_text_field($array) {
    foreach ($array as $key => &$value) {
        if (is_array($value)) {
            $value = recursive_sanitize_text_field($value);
        } else {
            $value = sanitize_text_field($value);
        }
    }

    return $array;
}