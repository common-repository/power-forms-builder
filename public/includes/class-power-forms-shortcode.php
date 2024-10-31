<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.powerformbuilder.com/
 * @since      1.0.0
 *
 * @package    Power_Forms
 * @subpackage Power_Forms/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Power_Forms
 * @subpackage Power_Forms/public
 * @author     PressTigers <support@presstigers.com>
 */
class Power_Forms_Public_Shortcode {

    /**
     * Constructor
     *
     * @since    1.0.0
     * @param void
     * @return void
     */
    public function __construct() {

        add_shortcode('POWER_FORMS', array($this, 'power_form_display'));

        add_action('wp_ajax_nopriv_pf_save_frontend_form', array($this, 'pf_save_frontend_form'));
        add_action('wp_ajax_pf_save_frontend_form', array($this, 'pf_save_frontend_form'));
    }

    /**
     * Funtion for unserialize Form 
     *
     * @since    1.0.0
     * @param string $str
     * @return $returndata 
     */
    function unserializeForm($str) {
        foreach ($str as $key => $value) {
            if (is_array($value)) {
                $values = implode(",", $value);
                $returndata[$key] = $values;
            } else {
                $returndata[$key] = $value;
            }
        }

        return $returndata;
    }

    /**
     * Funtion for hanling frontend for submission
     *
     * @since    1.0.0
     * @param string $string
     * @return response 
     */
    function pf_save_frontend_form() {

        global $wpdb, $wppowerforms;
        if (isset($_FILES)) {
            $files = $_FILES;
            $filestatus = array();
            foreach ($files as $key => $value) {
                if (substr($key, 0, 7) == 'pf_File') {
                    $uploadedfile = $value;
                    $upload_overrides = array('test_form' => false);
                    $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
                    if ($movefile && !isset($movefile['error'])) {
                        $filestatus[$key] = $movefile['file'];
                        $filestatus[$key . '_url'] = $movefile['url'];
                    } else {
                        $html = __('Please upload the approperiate file & re-submit the form!', 'power-froms');
                        $id = 'power_forms_container_' . $_POST['formid'];
                        echo json_encode(array('html' => $html, 'id' => $id));
                        die();
                    }
                }
            }
        }
        $post = $this->unserializeForm($_POST);
        if (is_array($filestatus) && isset($filestatus)) {
            $finalArray = array_merge($post, $filestatus);
        } else {
            $finalArray = $post;
        }

        /*
         * Before Form Submission
         */
        apply_filters('power_forms_before_form_submission', $finalArray);

        $formid = $finalArray['formId'];
        $pf_smart = get_post_meta($formid, 'pf_smart_confirmation', true);

        $form_name = get_the_title($formid);
        $ip = $_SERVER['REMOTE_ADDR'];
        if (is_user_logged_in()) {
            $userid = get_current_user_id();
        }
        $created_at = current_time('mysql');
        $updated_at = current_time('mysql');

        $table_name = $wpdb->prefix . 'wpf_entries';
        $entry = $wpdb->insert(
                $table_name, array(
            'form_name' => $form_name,
            'ip' => $ip,
            'form_id' => $formid,
            'user_id' => $userid,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
                )
        );
        if ($entry) {
            $lastid = $wpdb->insert_id;
            $table_names = $wpdb->prefix . 'wpf_entry_meta';
            $entries = array();
            $emailTemplate = json_decode(get_post_meta($formid, 'pf_email_setting', true));
            foreach ($finalArray as $key => $value) {
                if ($key !== 'honeypot' && $key !== 'wordpress-nonce' && $key !== 'action' && $key !== 'formId' && $key !== 'pf_gdpr_checkbox') {
                    $entries [] = $wpdb->insert(
                            $table_names, array(
                        'entry_name' => $key,
                        'entry_value' => $value,
                        'entry_id' => $lastid,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                            )
                    );
                }
            }
        }
        if (is_array($entries)) {
            $html = $this->buildEmailTemplate($formid, $lastid);
            if ($html == TRUE) {
                if ($html == TRUE) {
                    $formsuccess = get_option('opt-form-sucess-message');

                    $pf_form_stop = get_post_meta($formid, 'pf_form_stop', true);

                    if (isset($pf_form_stop) && $pf_form_stop == 'yes' && $html == TRUE) {
                        self::delete_entry(absint($lastid));
                    }

                    if ($pf_smart == 'noredirect') {
                        $html = '<div class="sucess_message col-xs-12 col-sm-12 col-lg-12">' . __($formsuccess, 'power-froms') . '</div>';
                        $id = 'power_forms_container_' . $formid;
                    } else {
                        global $wp;
                        $url = '?sucess=true&formid=' . $formid;
                        $html = '<div class="sucess_message col-xs-12 col-sm-12 col-lg-12">' . __($formsuccess, 'power-froms') . '</div>';
                        $id = 'power_forms_container_' . $formid;
                    }
                }
            } else {
                $html = '<div class="error_other col-xs-12 col-sm-12 col-lg-12">' . __('Email can not be sent, Please check your Smtp/Email settings.', 'power-froms') . '</div>';
                $id = 'power_forms_container_' . $formid;
            }
        }
        /*
         * After Form Submission
         */
        apply_filters('power_forms_after_form_submission', $formid);
        echo json_encode(array('html' => $html, 'id' => $id, 'redirect' => $url));
        die();
    }
    /**
     * Delete a entry record.
     * 
     * @since   1.0
     * 
     * @param   $id ID
     * @return  void
     * 
     */
    public static function delete_entry($id) {
        global $wpdb;
        $wpdb->delete("{$wpdb->prefix}wpf_entries", [ 'id' => $id], [ '%d']);
        $wpdb->delete("{$wpdb->prefix}wpf_entry_meta", [ 'entry_id' => $id], [ '%d']);
    }

    /**
     * Funtion for hanling User Email
     *
     * @since    1.0.0
     * @param int $formid
     * @param int $entryid
     * @return bool 
     */
    function buildEmailTemplate($formid, $entryid) {
        ob_start();

        $emailData = json_decode(get_post_meta($formid, 'pf_email_setting', true));
        $to = $this->getValue($emailData->pf_email_to, $id, $entryid);
        $from = $this->getValue($emailData->pf_email_from, $formid, $entryid);
        $subject = $this->getValue($emailData->pf_email_subject, $formid, $entryid);
        $body = $this->getValue(wp_specialchars_decode($emailData->pf_email_body), $formid, $entryid);
        $body = str_replace("rn", "", $body);
        $headers = 'From: ' . $from . '';
        $attachments = $this->getValue($emailData->pf_email_attachment, $formid, $entryid);
        if (!is_array($attachments)) {
            $attachments = explode(',', $attachments);
        }
        $array = array(
            'to' => $to,
            'from' => $from,
            'subject' => $subject,
            'body' => $body,
            'headers' => $headers,
        );
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/user-email-template.php';

        $output = ob_get_contents();

        ob_end_clean();

        $pfto = $to;
        $pfsubject = $subject;
        $pfbody = $output;
        /*
         * Before Email Send
         */
        $hooksArray = array(
            'to' => $pfto,
            'from' => $from,
            'subject' => $pfsubject,
            'body' => $pfbody,
            'headers' => $headers,
            'attacments' => $attachments
        );

        apply_filters('power_forms_before_user_email_send', $hooksArray);
        if (wp_mail($pfto, $pfsubject, $pfbody, $headers, $attachments)) {
            $value = $this->buildAdminEmailTemplate($formid, $entryid);
            if ($value) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Funtion for hanling Admin Email
     *
     * @since    1.0.0
     * @param int $formid
     * @param int $entryidsd
     * @return bool 
     */
    function buildAdminEmailTemplate($formid, $entryisd) {

        global $wpdb;

        ob_start();

        $to = get_option('admin_email');
        $from = "support@powerformbuilder.com";
        $subject = get_the_title($formid) . ' New Entry';
        $headerss = 'From: ' . $from . '';
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpf_entry_meta WHERE entry_id = $entryisd", ARRAY_A);

        $body = ' <table class="form-table"><tbody><tr>';

        $power_forms_form_fieldss = maybe_unserialize(get_post_meta($formid, 'pf_form_fields', true));
        if ($power_forms_form_fieldss) {
            $start = array();
            $end = array();
            foreach ($power_forms_form_fieldss as $key => $attr) {
                if (substr($attr['pf_title'], 0, 7) == 'pf_file') {
                    array_push($end, $attr['pf_label']);
                } else {
                    if ($attr['pf_label'] != 'H1' && $attr['pf_label'] != 'H2' && $attr['pf_label'] != 'H3' && $attr['pf_label'] != 'H4' && $attr['pf_label'] != 'H5' && $attr['pf_label'] != 'H6' && $attr['pf_label'] != 'p' && $attr['pf_label'] != 'hr' && $attr['pf_label'] != 'br' && $attr['pf_label'] != 'html')
                        array_push($start, $attr['pf_label']);
                }
            }
            $res = array_merge($start, $end);
        }
        $keys = 0;
        foreach ($results as $key => $record) {
            if (substr($record['entry_name'], 0, 7) == 'pf_File' && substr($record['entry_name'], -3) != 'url') {
                continue;
            } else {
                $body .= '<tr>';
                if (substr($record['entry_name'], 0, 7) == 'pf_File' && substr($record['entry_name'], -3) == 'url') {
                    if (pathinfo($record['entry_value'])['extension'] == 'jpg' || pathinfo($record['entry_value'])['extension'] == 'jpeg' || pathinfo($record['entry_value'])['extension'] == 'png' || pathinfo($record['entry_value'])['extension'] == 'gif') {
                        $body .= '<th style="font-size:14px !important;min-width:70px;text-align:left" scope = "row">' . $res[$keys] . '</th><td style="font-size:12px !important;min-width:70px"><img src="' . $record['entry_value'] . '" style="width:150px;height:150px" /></td>';
                    } else {
                        $body .= '<th style="font-size:14px !important;min-width:70px;text-align:left" scope = "row">' . $res[$keys] . '</th><td style="font-size:12px !important;min-width:70px"><a style="font-size:12px !important;" class="page-title-action" href="' . $record['entry_value'] . '">Download File</a></td>';
                    }
                } else if (substr($record['entry_name'], 0, 7) != 'pf_File') {

                    $body .= '<th style="font-size:14px !important;min-width:70px;text-align:left" scope = "row">' . $res[$keys] . '</th><td style="font-size:12px !important;min-width:70px"> ' . $record['entry_value'] . ' </td>';
                }
                $body .= '</tr>';
                $keys++;
            }
        }

        $body .= '</tr>';
        $body .= '</tbody>';
        $body .= '</table>';

        $res = array(
            'subject' => $subject,
            'body' => $body
        );

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/admin-email-template.php';

        $output = ob_get_contents();

        ob_end_clean();

        $pftos = $to;
        $pfsubjects = $subject;
        $pfbodys = $output;

        /*
         * Before admin Email Send
         */
        $hookssArray = array(
            'to' => $pftos,
            'from' => $from,
            'subject' => $pfsubjects,
            'body' => $pfbodys,
            'headers' => $headers,
            'attacments' => $attachments
        );

        apply_filters('power_forms_before_admin_email_send', $hookssArray);
        if (wp_mail($pftos, $pfsubjects, $pfbodys, $headerss)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Funtion for returning value of the mail tockens
     *
     * @since    1.0.0
     * @param int $formid
     * @param int $entryid
     * @param string $token
     * @return $token | $content 
     */
    function getValue($token, $formid, $entryid) {

        $result = $this->getFieldsUsingEntryID($entryid);
        if (!empty($token)) {
            $string = $token;
            $pattern = '/(\[){1}[A-z0-9]+(\]){1}/i';

            preg_match_all($pattern, $string, $out);

            if (empty($out[0]) && $out[0] == FALSE) {
                return $string;
            } else {
                foreach ($out[0] as $key => $value) {
                    foreach ($result as $entryname => $entryvalue) {
                        if ($value == '[' . $entryname . ']') {
                            $values[] = $value;
                            $entryvalues[] = $entryvalue;
                        }
                    }
                }
                $mail_content_new = str_replace($values, $entryvalues, $string);
                return $mail_content_new;
            }
        } else {
            return $token;
        }
    }

    /**
     * Funtion for returning entry meta
     *
     * @since    1.0.0
     * @param int $entryid
     * @return $entry meta
     */
    function getFieldsUsingEntryID($entryid) {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpf_entry_meta WHERE entry_id = $entryid", OBJECT);
        $newArray = array();
        foreach ($results as $key => $value) {
            $newArray[$value->entry_name] = $value->entry_value;
        }
        return $newArray;
    }

    /**
     * Funtion for displaying shortcode output
     *
     * @since    1.0.0
     * @param array $atts
     * @return Form
     */
    function power_form_display($atts) {

        $postID = $atts['form_id'];
        if (get_post_status($postID)) {
            $power_forms_form_fields = maybe_unserialize(get_post_meta($postID, 'pf_form_fields', true));
            $pf_active_column = get_post_meta($postID, 'pf_active_column', true);

            $form = new PhpFormBuilder();

            $form->set_att('method', 'post');
            $form->set_att('enctype', 'multipart/form-data');
            $form->set_att('markup', 'html');
            $form->set_att('class', array(''));
            $form->set_att('id', 'power_forms_' . $postID);
            $form->set_att('novalidate', false);
            $form->set_att('add_honeypot', true);
            $form->set_att('add_nonce', 'a_contact_form');
            $form->set_att('form_element', true);
            $form->set_att('add_submit', true);
            $form->set_att('action', get_permalink());
            $form->set_att('active_column', $pf_active_column);
            $form->set_att('postID', $postID);
            $form->set_att('loader', true);

            if ($power_forms_form_fields) {

                foreach ($power_forms_form_fields as $key => $attr) {

                    if ($attr['pf_required'] == "yes") {
                        $required = true;
                    } else {
                        $required = false;
                    }
                    $form->add_input(
                            isset($attr['pf_label']) ? $attr['pf_label'] : '', array(
                        'type' => explode("_", $attr['pf_field_type'])[1],
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
                }
            }
            if (isset($_GET['sucess']) && isset($_GET['formid']) && $_GET['formid'] == $postID) {
                $formsuccess = get_option('opt-form-sucess-message');

                return '<div class="sucess_message col-xs-12">' . $formsuccess . '</div>';
            } else {
                $formHtml = $form->build_form(false);
                return '<div>' . $formHtml . '<script>jQuery("#power_forms_' . $postID . '").validate();</script></div>';
            }
        }
    }

}

new Power_Forms_Public_Shortcode();
