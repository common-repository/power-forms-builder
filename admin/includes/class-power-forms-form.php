<?php

/**
 * The admin-specific functionality of the plugin for adding the SMTP functionality
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
class PhpFormBuilder {

    private $inputs = array();
    private $form = array();
    private $has_submit = false;

    /**
     * Constructor.
     * 
     * @since   1.0
     * 
     * @param   $action Form action
     * @param   $args Form arguments
     * @return  void
     * 
     */
    function __construct($action = '', $args = false) {

        $defaults = array(
            'action' => $action,
            'method' => 'post',
            'enctype' => 'application/x-www-form-urlencoded',
            'class' => array(''),
            'id' => '',
            'markup' => 'html',
            'novalidate' => false,
            'add_nonce' => false,
            'add_honeypot' => true,
            'form_element' => true,
            'add_submit' => false,
            'active_column' => 'one',
            'postID' => '',
            'loader' => false,
        );

        if ($args) {
            $settings = array_merge($defaults, $args);
        } else {
            $settings = $defaults;
        }

        foreach ($settings as $key => $val) {
            if (!$this->set_att($key, $val)) {
                $this->set_att($key, $defaults[$key]);
            }
        }
    }

    /**
     * Setting Form Attributes.
     * 
     * @since   1.0
     * 
     * @param   $key A valid key; switch statement ensures validity
     * @param   $val A valid value; validated for each key
     * @return  bool
     * 
     */
    function set_att($key, $val) {

        switch ($key) :

            case 'action':
                break;

            case 'method':
                if (!in_array($val, array('post', 'get'))) {
                    return false;
                }
                break;

            case 'enctype':
                if (!in_array($val, array('application/x-www-form-urlencoded', 'multipart/form-data'))) {
                    return false;
                }
                break;

            case 'markup':
                if (!in_array($val, array('html', 'xhtml'))) {
                    return false;
                }
                break;

            case 'class':
            case 'id':
                if (!$this->_check_valid_attr($val)) {
                    return false;
                }
                break;
            case 'active_column':
                if (!$this->_check_valid_attr($val)) {
                    return false;
                }
                break;
            case 'postID':
                if (!$this->_check_valid_attr($val)) {
                    return false;
                }
                break;
            case 'loader':
                if (!$this->_check_valid_attr($val)) {
                    return false;
                }
                break;

            case 'novalidate':
            case 'add_honeypot':
            case 'form_element':
            case 'add_submit':
                if (!is_bool($val)) {
                    return false;
                }
                break;

            case 'add_nonce':
                if (!is_string($val) && !is_bool($val)) {
                    return false;
                }
                break;

            default:
                return false;

        endswitch;

        $this->form[$key] = $val;

        return true;
    }

    /**
     * Add an input field to the form for outputting later
     * 
     * @since   1.0
     * 
     * @param   $label Field Label
     * @param   $args Arguments
     * @param   $slug Slug
     * @return  void
     * 
     */
    function add_input($label, $args = '', $slug = '') {


        if (empty($args)) {
            $args = array();
        }

        if (empty($slug)) {
            $slug = $this->_make_slug($label);
        }

        $defaults = array(
            'type' => 'text',
            'name' => $slug,
            'id' => $slug,
            'label' => $label,
            'value' => '',
            'placeholder' => '',
            'class' => array(),
            'min' => '',
            'max' => '',
            'step' => '',
            'autofocus' => false,
            'checked' => false,
            'selected' => false,
            'required' => false,
            'add_label' => true,
            'options' => array(),
            'wrap_tag' => 'div',
            'wrap_class' => array('pf_form_field_wrap'),
            'wrap_id' => '',
            'wrap_style' => '',
            'before_html' => '',
            'after_html' => '',
            'filed_text' => '',
            'disable' => false,
            'request_populate' => true,
            'column' => 'one',
            'theight' => 50,
        );

        $args = array_merge($defaults, $args);
        $this->inputs[$slug] = $args;
    }

    /**
     * Add multiple inputs to the input queue
     * 
     * @since   1.0
     * 
     * @param   $args Arguments
     * @return  bool
     * 
     */
    function add_inputs($arr) {

        if (!is_array($arr)) {
            return false;
        }

        foreach ($arr as $field) {
            $this->add_input(
                    $field[0], isset($field[1]) ? $field[1] : '', isset($field[2]) ? $field[2] : ''
            );
        }

        return true;
    }

    /**
     * Build the HTML for the form based on the input queue
     * 
     * @since   1.0
     * 
     * @param   $echo Should the HTML be echoed or returned?
     * @return $output string
     * 
     */
    function build_form($echo = true) {

        $output = '';
        $formclass = get_option('opt-form-class');
        $optformgdprformcheckboxtext = get_option('opt-form-gdpr-form-checkbox-text');
        $power_forms_styling = get_post_meta($this->form['postID'], 'pf_forms_styling', true);
        $power_forms_gdpr = get_post_meta($this->form['postID'], 'pf_form_gdpr', true);
        $formBG = isset($power_forms_styling['bgcolor']) ? $power_forms_styling['bgcolor'] : '';
        $formtextcolor = isset($power_forms_styling['textcolor']) ? $power_forms_styling['textcolor'] : '';
        $formBBG = isset($power_forms_styling['submitbgcolor']) ? $power_forms_styling['submitbgcolor'] : '';
        $formBtextcolor = isset($power_forms_styling['submittextcolor']) ? $power_forms_styling['submittextcolor'] : '';
        $formpadding = isset($power_forms_styling['padding']) ? $power_forms_styling['padding'] : '';
        $formbdr = isset($power_forms_styling['bdr']) ? $power_forms_styling['bdr'] : '';
        $submitwidth = isset($power_forms_styling['submitwidth']) ? $power_forms_styling['submitwidth'] : '';
        $submitposition = isset($power_forms_styling['submitposition']) ? $power_forms_styling['submitposition'] : '';

        $output .= '<div id="power_forms_container_' . $this->form['postID'] . '" class="power_forms_container container-fluid ' . $formclass . '" style="padding:0px">';

        if (isset($this->form['loader']) && $this->form['loader'] == true) {
            $output .= '<div class="loadersOut"><span class="loaders"></span></div>';
        }
        if ($this->form['form_element']) {
            $output .= '<form style="background-color:' . $formBG . ';color:' . $formtextcolor . ';padding:' . $formpadding . 'px;border-radius:' . $formbdr . 'px" data-formid="' . $this->form['postID'] . '" method="' . $this->form['method'] . '"';

            if (!empty($this->form['enctype'])) {
                $output .= ' enctype="' . $this->form['enctype'] . '"';
            }

            if (!empty($this->form['action'])) {
                $output .= ' action="' . $this->form['action'] . '"';
            }

            if (!empty($this->form['id'])) {
                $output .= ' id="' . $this->form['id'] . '"';
            }

            if (count($this->form['class']) > 0) {
                $output .= $this->_output_classes($this->form['class']);
            }

            if ($this->form['novalidate']) {
                $output .= ' novalidate';
            }

            $output .= '>';
        }
        if ($this->form['add_honeypot']) {
            $this->add_input('Leave blank to submit', array(
                'name' => 'honeypot',
                'slug' => 'honeypot',
                'id' => $this->form['id'],
                'wrap_tag' => 'div',
                'wrap_class' => array('form_field_wrap', 'hidden'),
                'wrap_id' => '',
                'wrap_style' => 'display: none',
                'request_populate' => false,
            ));
        }

        if ($this->form['add_nonce'] && function_exists('wp_create_nonce')) {
            $this->add_input('WordPress nonce', array(
                'value' => wp_create_nonce($this->form['add_nonce']),
                'add_label' => false,
                'type' => 'hidden',
                'request_populate' => false,
            ));
        }

        $i = 1;
        foreach ($this->inputs as $val) :

            $min_max_range = $element = $end = $attr = $field = $label_html = '';

            if ($val['request_populate'] && isset($_REQUEST[$val['name']])) {

                if (!in_array($val['type'], array('html', 'title', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'br', 'hr', 'radio', 'checkbox', 'select', 'submit'))) {
                    $val['value'] = sanitize_text_field($_REQUEST[$val['name']]);
                }
            }

            if (
                    $val['request_populate'] &&
                    ( $val['type'] == 'radio' || $val['type'] == 'checkbox' ) &&
                    empty($val['options'])
            ) {
                $val['checked'] = isset($_REQUEST[$val['name']]) ? true : $val['checked'];
            }

            switch ($val['type']) {

                case 'html':
                    $element = '';
                    $end = '<div class="' . $val['class'] . '">' . __($val['filed_text'], 'power-forms') . '</div>';
                    break;

                case 'p':
                    $element = 'p';
                    $end = '>' . esc_attr__($val['filed_text'], 'power-forms') . '</p>';
                    break;
                case 'h1':
                    $element = 'h1';
                    $end = '>' . esc_attr__($val['filed_text'], 'power-forms') . '</h1>';
                    break;
                case 'h2':
                    $element = 'h2';
                    $end = '>' . esc_attr__($val['filed_text'], 'power-forms') . '</h2>';
                    break;
                case 'h3':
                    $element = 'h3';
                    $end = '>' . esc_attr__($val['filed_text'], 'power-forms') . '</h3>';
                    break;
                case 'h4':
                    $element = 'h4';
                    $end = '>' . esc_attr__($val['filed_text'], 'power-forms') . '</h4>';
                    break;
                case 'h5':
                    $element = 'h5';
                    $end = '>' . esc_attr__($val['filed_text'], 'power-forms') . '</h5>';
                    break;
                case 'h6':
                    $element = 'h6';
                    $end = '>' . esc_attr__($val['filed_text'], 'power-forms') . '</h6>';
                    break;
                case 'hr':
                    $element = 'hr';
                    $end = '>';
                    break;
                case 'br':
                    $element = 'br';
                    $end = '>';
                    break;

                case 'title':
                    $element = '';
                    $end = '<h1>' . esc_attr__($val['label'], 'power-forms') . '</h1>';
                    break;


                case 'textarea':
                    $element = 'textarea';
                    $end = ' style="height:' . $val['theight'] . 'px" placeholder="' . $val['placeholder'] . '">' . __($val['value'], 'power-forms') . '</textarea>';
                    break;

                case 'select':
                    $element = 'select';
                    $optionString = isset($val['options']) ? $val['options'] : '';
                    $optionArray = explode(",", $optionString);
                    $end .= '>';
                    foreach ($optionArray as $key => $opt) {
                        if (isset($val['value'])) {
                            $selected = '';
                            if (strtolower($opt) == strtolower($val['value'])) {
                                $selected = 'selected';
                            }
                        }
                        $opt_insert = '';
                        if (
                                $val['request_populate'] &&
                                isset($_REQUEST[$val['name']]) &&
                                $_REQUEST[$val['name']] === $key
                        ) {
                            $opt_insert = ' selected';
                        } else if ($val['selected'] === $key) {
                            $opt_insert = ' selected';
                        }
                        $end .= '<option ' . $selected . ' value="' . $key . '"' . $opt_insert . '>' . __($opt, 'power-forms') . '</option>';
                    }
                    $end .= '</select>';
                    break;

                case 'radio':
                case 'checkbox':

                    if (count($val['options']) > 0) :
                        $optionString = isset($val['options']) ? $val['options'] : '';
                        $optionArray = explode(",", $optionString);
                        $element = '';
                        $checked = "";
                        $selected = "";
                        foreach ($optionArray as $key => $opt) {
                            if (isset($val['value'])) {
                                if (strtolower($opt) == strtolower($val['value'])) {
                                    $checked = "checked";
                                    $selected = 'selected';
                                }
                            }
                            $slug = $this->_make_slug($opt);
                            $chrequired = $val['required'] ? ' required' : '';
                            $end .= sprintf(
                                    '<input ' . $checked . ' ' . $selected . ' ' . $chrequired . ' style="width:auto;display:inline-block" type="%s" name="%s[]" value="%s" id="%s"', $val['type'], $val['name'], $key, $slug
                            );
                            if (
                                    $val['request_populate'] &&
                                    isset($_REQUEST[$val['name']]) &&
                                    in_array($key, sanitize_text_field($_REQUEST[$val['name']]))
                            ) {
                                $end .= ' checked';
                            }
                            $end .= $this->field_close();
                            $end .= ' <label style="margin-right: 10px;display:inline-block;color:' . $formtextcolor . '" for="' . $slug . '">' . __($opt, 'power-forms') . '</label>';
                        }
                        if ($val['required'] && $val['required'] == 'yes') {
                            $label_re = '<strong style="color:#dd3333 !important" id="' . $val['id'] . '_' . $val['type'] . '_required">*</strong>';
                        } else {
                            $label_re = '';
                        }
                        $label_html = '<label style="margin-right:10px;color:' . $formtextcolor . '">' . __($val['label'] . $label_re, 'power-forms') . '</label>';
                        break;
                endif;

                default :
                    $element = 'input';
                    $end .= ' type="' . $val['type'] . '" value="' . $val['value'] . '" placeholder="' . __($val['placeholder'],'power-forms') . '"';
                    $end .= $val['checked'] ? ' checked' : '';
                    $end .= $this->field_close();
                    break;
            }

            if ($val['type'] === 'submit') {
                $this->has_submit = true;
            }

            if ($val['type'] === 'range' || $val['type'] === 'number') {
                $min_max_range .=!empty($val['min']) ? ' min="' . $val['min'] . '"' : '';
                $min_max_range .=!empty($val['max']) ? ' max="' . $val['max'] . '"' : '';
                $min_max_range .=!empty($val['step']) ? ' step="' . $val['step'] . '"' : '';
            }

            $id = !empty($val['id']) ? ' id="' . $val['id'] . '_' . $val['type'] . '"' : '';

            $class = $this->_output_classes($val['class']);

            $attr .= $val['autofocus'] ? ' autofocus' : '';
            $attr .= $val['checked'] ? ' checked' : '';
            $attr .= $val['required'] ? ' required' : '';

            if (!empty($label_html)) {
                $field .= $label_html;
            } elseif ($val['add_label'] && !in_array($val['type'], array('hidden', 'submit', 'title', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'br', 'hr', 'html'))) {
                if ($val['required'] && $val['required'] == 'yes') {
                    $label_re = '<strong style="color:#dd3333 !important" id="' . $val['id'] . '_' . $val['type'] . '_required">*</strong>';
                } else {
                    $label_re = '';
                }
                $field .= '<label style="display:inline-block;color:' . $formtextcolor . '" id="' . $val['id'] . '_' . $val['type'] . '_label" for="' . $val['id'] . '">' . __($val['label'], 'power-forms') . '</label>' . $label_re;
            }

            if (!empty($element)) {
                if ($val['disable'] && $val['disable'] == true) {
                    $disable = 'disabled';
                } else {
                    $disable = '';
                }
                if ($val['type'] === 'checkbox') {
                    $field .= '<' . $element . $id . ' name="' . $val['name'] . '"' . $min_max_range . $class . $disable . $attr . $end;
                } else if ($val['type'] === 'textarea') {
                    $field .= '<' . $element . ' name="' . $val['name'] . '"  ' . $id . $min_max_range . $class . $disable . $attr . $end;
                } else if ($val['type'] === 'hr' || $val['type'] === 'br') {
                    $field .= '<' . $element . $class . $end;
                } else {
                    $field .= '<' . $element . $id . ' name="' . $val['name'] . '"' . $min_max_range . $class . $disable . $attr . $end;
                }
            } else {
                $field .= $end;
            }

            if ($val['type'] != 'hidden') {

                $wrap_before = $val['before_html'];
                if (!empty($val['wrap_tag'])) {
                    $wrap_before .= '<' . $val['wrap_tag'];
                    $wrap_before .= count($val['wrap_class']) > 0 ? $this->_output_classes($val['wrap_class']) : '';
                    $wrap_before .=!empty($val['wrap_style']) ? ' style="' . $val['wrap_style'] . '"' : '';
                    $wrap_before .=!empty($val['wrap_id']) ? ' id="' . $val['wrap_id'] . '"' : '';
                    $wrap_before .= '>';
                }

                $wrap_after = $val['after_html'];
                if (!empty($val['wrap_tag'])) {
                    $wrap_after = '</' . $val['wrap_tag'] . '>' . $wrap_after;
                }
                if ($val['column'] == 'one' && $this->form['active_column'] == 'one') {
                    if (isset($i) && $i == 1) {
                        $output .= '<div class="col-xs-12 col-sm-12 col-lg-12" style="padding:0px">' . $wrap_before . $field . $wrap_after;
                        $i++;
                    } else {
                        $output .= $wrap_before . $field . $wrap_after;
                    }
                } else if ($this->form['active_column'] == 'two') {

                    if ($val['column'] == 'one') {
                        if (isset($i) && $i == 1) {
                            $output .= '<div class="col-xs-12 col-sm-12 col-lg-6" style="float:left;padding:0px">' . $wrap_before . $field . $wrap_after;
                            $i++;
                            $e = 1;
                        } else {
                            $output .= $wrap_before . $field . $wrap_after;
                        }
                    }
                    if ($val['column'] == 'two') {
                        if (isset($e) && $e == 1) {
                            $output .= '</div><div class="col-xs-12 col-sm-12 col-lg-6" style="float:right;padding:0px">' . $wrap_before . $field . $wrap_after;
                            $e++;
                        } else {
                            $output .= $wrap_before . $field . $wrap_after;
                        }
                    }
                } else if ($this->form['active_column'] == 'three') {

                    if ($val['column'] == 'one') {
                        if (isset($i) && $i == 1) {
                            $output .= '<div class="col-xs-12 col-sm-12 col-lg-4" style="float:left;padding:0px">' . $wrap_before . $field . $wrap_after;
                            $i++;
                            $e = 1;
                            $d = 1;
                        } else {
                            $output .= $wrap_before . $field . $wrap_after;
                        }
                    }
                    if ($val['column'] == 'two') {
                        if (isset($e) && $e == 1) {
                            $output .= '</div><div class="col-xs-12 col-sm-12 col-lg-4" style="padding:0px">' . $wrap_before . $field . $wrap_after;
                            $e++;
                            $d = 1;
                        } else {
                            $output .= $wrap_before . $field . $wrap_after;
                        }
                    }
                    if ($val['column'] == 'three') {
                        if (isset($d) && $d == 1) {
                            $output .= '</div><div class="col-xs-12 col-sm-12 col-lg-4" style="float:right;padding:0px">' . $wrap_before . $field . $wrap_after;
                            $d++;
                        } else {
                            $output .= $wrap_before . $field . $wrap_after;
                        }
                    }
                }
            } else {
                $output .= $field;
            }

        endforeach;
        $output .= '</div>';

        if (!$this->has_submit && $this->form['add_submit']) {
            $value = '';
            $formsubmit = get_option('opt-form-submit');
            if (!empty($formsubmit)) {
                $value = esc_attr__($formsubmit, 'power-forms');
            } else {
                $value = esc_attr__('Submit', 'power-forms');
            }
            $formsitekey = get_option('opt-form-site-key');
            $pf_form_captcha = get_post_meta($this->form['postID'], 'pf_form_captcha', true);
            if ($power_forms_gdpr == 'yes') {
                $output .='<div class="col-xs-12 col-sm-12 col-lg-12" style="padding:0px"><input style="margin-right:5px" type="checkbox" required="" name="pf_gdpr_checkbox" id="pf_gdpr_checkbox">' . $optformgdprformcheckboxtext . '</div>';
            }

            if ($pf_form_captcha == 'yes') {
                $output .= '<div style="clear:both" class="pf_form_field_wrap container-fluid"><script src="https://www.google.com/recaptcha/api.js"></script><div class="g-recaptcha" data-callback="capcha_filled" data-expired-callback="capcha_expired" data-sitekey="' . $formsitekey . '"></div><label id="captcha-error" style="display:none" for="captcha" style="display: block;">' . __('Captcha field is required.', 'power-forms') . '</label></div>';
            }
            if ($submitwidth == 0) {
                $width = 'auto';
            } else {
                $width = $submitwidth . '%';
            }
            if ($submitposition == 'left') {
                $position = 'float:left !important;';
            } else if ($submitposition == 'center') {
                $position = 'margin:auto !important;display:block !important';
            } else {
                $position = 'float:right !important;';
            }
            $output .= '<div style="clear:both" class="pf_form_field_wrap container-fluid"><input style="width:' . $width . ' !important;color:' . $formBtextcolor . ';background-color:' . $formBBG . ';padding:10px 20px 10px 20px !important;border:none;font-weight: normal;font-size: 14px;'.$position.'" class="custom" id="power_forms_button_' . $this->form['postID'] . '" type="submit" value="' . $value . '" name="submit"></div>';
        }

        if ($this->form['form_element']) {
            $output .= '</form>';
            $output .= '</div>';
        }

        $formpermission = get_option('opt-form-permission');
        if ($formpermission == 'log') {
            if (is_user_logged_in()) {
                if ($echo) {
                    echo $output;
                } else {
                    return $output;
                }
            } else {
                $output = esc_attr__('You have not permission to view the form', 'power-forms');
                return $output;
            }
        } else if ($formpermission == 'unlog') {
            if (!is_user_logged_in()) {
                if ($echo) {
                    echo $output;
                } else {
                    return $output;
                }
            } else {
                $output = esc_attr__('You have not permission to view the form', 'power-forms');
                return $output;
            }
        } else {
            if ($echo) {
                echo $output;
            } else {
                return $output;
            }
        }
    }

    /**
     * Easy way to auto-close fields, if necessary
     * 
     * @since   1.0
     * 
     * @param   void
     * @return $this->form['markup'] Form Markup Type
     * 
     */
    function field_close() {
        return $this->form['markup'] === 'xhtml' ? ' />' : '>';
    }

    /**
     * Validates id and class attributes
     * 
     * @since   1.0
     * 
     * @param   $string String
     * @return $result Valid Results
     * 
     */
    private function _check_valid_attr($string) {

        $result = true;
        return $result;
    }

    /**
     * Create a slug from a label name
     * 
     * @since   1.0
     * 
     * @param   $string String
     * @return $result Valid Slug
     * 
     */
    private function _make_slug($string) {

        $result = '';

        $result = str_replace('"', '', $string);
        $result = str_replace("'", '', $result);
        $result = str_replace('_', '-', $result);
        $result = preg_replace('~[\W\s]~', '-', $result);

        $result = strtolower($result);

        return $result;
    }

    /**
     * Parses and builds the classes in multiple places
     * 
     * @since   1.0
     * 
     * @param   $classes Classes
     * @return $output
     * 
     */
    private function _output_classes($classes) {

        $output = '';


        if (is_array($classes) && count($classes) > 0) {
            $output .= ' class="power_form_handle ';
            foreach ($classes as $class) {
                $output .= $class . ' ';
            }
            $output .= '"';
        } else if (is_string($classes)) {
            $output .= ' class="' . $classes . '"';
        }

        return $output;
    }

}
