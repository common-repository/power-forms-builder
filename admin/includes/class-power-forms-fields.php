<?php

/**
 * The admin-specific functionality of the plugin for saving form and ajax requests.
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
class Power_Forms_Fields {

    /**
     * Constructor.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     * 
     */
    public function __construct() {

        add_action('wp_ajax_save_form_field_detail', array($this, 'pf_save_form_field_detail'));
        add_action('wp_ajax_save_form_field_detail', array($this, 'pf_save_form_field_detail'));

        add_action('wp_ajax_preview_form', array($this, 'pf_preview_form'));
        add_action('wp_ajax_preview_form', array($this, 'pf_preview_form'));

        add_action('wp_ajax_pf_save_global_settings', array($this, 'pf_save_global_settings'));
        add_action('wp_ajax_pf_save_global_settings', array($this, 'pf_save_global_settings'));

        add_action('wp_ajax_pf_save_smtp_settings', array($this, 'pf_save_smtp_settings'));
        add_action('wp_ajax_pf_save_smtp_settings', array($this, 'pf_save_smtp_settings'));

        add_action('wp_ajax_pf_send_smtp_test_email', array($this, 'pf_send_smtp_test_email'));
        add_action('wp_ajax_pf_send_smtp_test_email', array($this, 'pf_send_smtp_test_email'));

        add_action('wp_ajax_pf_save_global_recaptcha_form', array($this, 'pf_save_global_recaptcha_form'));
        add_action('wp_ajax_pf_save_global_recaptcha_form', array($this, 'pf_save_global_recaptcha_form'));

        add_action('wp_ajax_pf_get_downloads_form', array($this, 'pf_get_downloads_form'));
        add_action('wp_ajax_pf_get_downloads_form', array($this, 'pf_get_downloads_form'));

        add_action('wp_ajax_pf_download_file', array($this, 'pf_download_file'));
        add_action('wp_ajax_pf_download_file', array($this, 'pf_download_file'));
    }

    /**
     * Function for donwloading the csv file of form submissions in response to Ajax request
     * 
     * @param $_POST
     *
     * @return JSON array()
     */
    function pf_download_file() {
        global $wpdb;
        $fid = $_POST['export_formid'];
        $file_format = $_POST['file_format'];
        $exportall = $_POST['exportrange']['all'];
        $exportallstart = $_POST['exportrange']['start'];
        $exportallend = $_POST['exportrange']['end'];
        $keys = $_POST['exportkeys'];
        $html = '';


        if (isset($file_format) && $file_format == 'csv' && isset($fid)) {

            if (isset($exportall) && $exportall == 'all_range' && !empty($exportallstart) && $exportallstart != NULL) {

                if (!empty($exportallend) && $exportallend != NULL) {
                    $range = ($exportallend - $exportallstart);
                    if ($range >= 0) {
                        $limit = $exportallstart . ', ' . $range;
                        $entries = $wpdb->get_results("SELECT e.id,e.form_name,e.ip,e.form_id,e.user_id,e.created_at, group_concat(m.entry_name separator ', ') as Keyss, group_concat(m.entry_value separator ', ') as Meta FROM {$wpdb->prefix}wpf_entries e RIGHT JOIN {$wpdb->prefix}wpf_entry_meta m ON e.id=m.entry_id WHERE form_id = $fid group by m.entry_id LIMIT $limit", ARRAY_A);
                    } else {
                        $range = 0;
                        $limit = $exportallstart . ', ' . $range;
                        $entries = $wpdb->get_results("SELECT e.id,e.form_name,e.ip,e.form_id,e.user_id,e.created_at, group_concat(m.entry_name separator ', ') as Keyss, group_concat(m.entry_value separator ', ') as Meta FROM {$wpdb->prefix}wpf_entries e RIGHT JOIN {$wpdb->prefix}wpf_entry_meta m ON e.id=m.entry_id WHERE form_id = $fid group by m.entry_id LIMIT $limit", ARRAY_A);
                    }
                } else {
                    $limit = $exportallstart;
                    $entries = $wpdb->get_results("SELECT e.id,e.form_name,e.ip,e.form_id,e.user_id,e.created_at, group_concat(m.entry_name separator ', ') as Keyss, group_concat(m.entry_value separator ', ') as Meta FROM {$wpdb->prefix}wpf_entries e RIGHT JOIN {$wpdb->prefix}wpf_entry_meta m ON e.id=m.entry_id WHERE form_id = $fid group by m.entry_id LIMIT $limit", ARRAY_A);
                }
            } else {
                $entries = $wpdb->get_results("SELECT e.id,e.form_name,e.ip,e.form_id,e.user_id,e.created_at, group_concat(m.entry_name separator ', ') as Keyss, group_concat(m.entry_value separator ', ') as Meta FROM {$wpdb->prefix}wpf_entries e RIGHT JOIN {$wpdb->prefix}wpf_entry_meta m ON e.id=m.entry_id WHERE form_id = $fid group by m.entry_id", ARRAY_A);
            }

            if (count($entries)) {
                $delimiter = ",";
                $file = '/wp-content/plugins/power-forms-builder/admin/csv/power_forms_' . date("Y-m-d") . '_' . $fid . '.csv';
                $filename = ABSPATH . $file;
                //create a file pointer
                $f = fopen($filename, 'w');
                $keysss = array();
                foreach ($entries as $key => $value) {

                    if (in_array('id', $keys)) {
                        array_push($keysss, 'Entry ID');
                    }
                    if (in_array('form_name', $keys)) {
                        array_push($keysss, 'Form Name');
                    }
                    if (in_array('ip', $keys)) {
                        array_push($keysss, 'IP Address');
                    }
                    if (in_array('form_id', $keys)) {
                        array_push($keysss, 'Form ID');
                    }
                    if (in_array('user_id', $keys)) {
                        array_push($keysss, 'User ID');
                    }
                    if (in_array('created_at', $keys)) {
                        array_push($keysss, 'Submission Date');
                    }

                    $arraMeta = explode(',', $value['Keyss']);
                    foreach ($arraMeta as $key => $values) {
                        if (in_array($values, $keys)) {
                            array_push($keysss, $values);
                        }
                    }
                    break;
                }
                fputcsv($f, $keysss, $delimiter);
                foreach ($entries as $key => $value) {
                    $vauless = array();

                    if (in_array('id', $keys)) {
                        array_push($vauless, $value['id']);
                    }
                    if (in_array('form_name', $keys)) {
                        array_push($vauless, $value['form_name']);
                    }
                    if (in_array('ip', $keys)) {
                        array_push($vauless, $value['ip']);
                    }
                    if (in_array('form_id', $keys)) {
                        array_push($vauless, $value['form_id']);
                    }
                    if (in_array('user_id', $keys)) {
                        array_push($vauless, $value['user_id']);
                    }
                    if (in_array('created_at', $keys)) {
                        array_push($vauless, $value['created_at']);
                    }


                    $arrayMeta = explode(',', $value['Meta']);
                    $arrayKeyss = explode(',', $value['Keyss']);
                    foreach ($arrayMeta as $key => $values) {
                        if (in_array($arrayKeyss[$key], $keys)) {
                            array_push($vauless, $values);
                        }
                    }
                    fputcsv($f, $vauless, $delimiter);
                }
                fseek($f, 0);
                fclose($f);
                $msg = 'CSV has been created';
                $returnpath = site_url() . $file;
                $status = true;
            } else {
                $msg = 'There is no record exists against your criteria';
                $returnpath = '';
                $status = false;
            }
        }
        echo json_encode(array('status' => $status, 'msg' => $msg, 'filename' => $returnpath));
        die();
    }

    /**
     * Function for getting donwloading the csv file form in response to Ajax request
     * 
     * @param $_POST
     *
     * @return JSON array()
     */
    function pf_get_downloads_form() {

        $formid = $_POST['formid'];

        $html = '<div class="containers">';

        $html .= '<fieldset style="border:1px solid #ddd;padding:20px;width:100%"><legend><strong>Download Options</strong></legend>';


        $html .= '<form action="" class="pfb_export_form" id="pfb_export_form">';

        $html .= '<ul class="export_list">';

        $html .= '<li>Export format</li>';
        $html .= '<li><input type="radio" name="file_format" id="file_format" class="file_format" checked="checked" value="csv"> CSV File</li>';

        $html .= '<div class="pfb_accordion">';

        $html .= '<div class="pfb_accordion-header">Include Export Fields</div>';

        $html .= '<div class="pfb_accordion-content"><p style="margin-top:0px">The selected component will be included in the export file</p>';

        $results = $this->getEntryWithMeta($formid);

        foreach ($results as $key => $name) {
            if (is_array($name)) {
                foreach ($name as $key => $subname) {
                    $html .= '<li><input type="checkbox" name="exportkeys[]" id="pfb_' . $key . '" class="pfb_' . $key . ' sub_entry" checked="checked" value="' . $key . '"> ' . $subname . '</li>';
                }
            } else {
                $s = ucfirst($name);
                $bar = ucwords(strtolower($s));
                $data = preg_replace('/\s+/', '', $bar);
                $html .= '<li><input type="checkbox" name="exportkeys[]" id="pfb_' . $data . '" class="pfb_' . $data . '" checked="checked" value="' . $name . '"> ' . $name . '</li>';
            }
        }

        $html .= '</div>';


        $html .= '<div class="pfb_accordion-header">Download Range Options</div>';

        $html .= '<div class="pfb_accordion-content">';

        $html .= '<li><input type="radio" name="exportrange[all]" id="pfb_all" class="pfb_all" checked="checked" value="all"> All Submissions</li>';
        $html .= '<li><input type="radio" name="exportrange[all]" id="pfb_start" class="pfb_all_range" value="all_range">All Submissions Starting ';
        $html .= ' From: ';
        $html .= '<input type="number" name="exportrange[start]" id="pfb_all_range_start" class="pfb_all_range_start" value="">';
        $html .= ' To: ';
        $html .= '<input type="number" name="exportrange[end]" id="pfb_all_range_end" class="pfb_all_range_end" value=""></li>';

        $html .= '</div>';


        $html .= '</div>';

        $html .= '<li><input type="hidden" name="export_formid" id="export_formid" class="export_formid" value="' . $formid . '"></li>';

        $html .= '<li><input type="submit" name="export_submit" id="export_submit" class="export_submit" value="Download"></li>';

        $html .= '</ul>';

        $html .= '</form';

        $html .= '</fieldset>';

        $html .= '</div>';


        echo json_encode(array('status' => true, 'form' => $html));
        die();
    }

    /**
     * Function for getting a entry keys
     * 
     * @param $formid
     *
     * @return $finalarray
     */
    function getEntryWithMeta($formid) {
        global $wpdb;
        $idss = $wpdb->get_results("SELECT id FROM {$wpdb->prefix}wpf_entries WHERE form_id = $formid", ARRAY_A);
        $array = array();
        foreach ($idss as $key => $value) {
            $array[] = $value['id'];
        }
        $commaids = implode(",", $array);
        $formKeys = array('submission information' => array('id' => 'Entry ID', 'form_name' => 'Form Name', 'ip' => 'IP Address', 'form_id' => 'Form ID', 'user_id' => 'User ID', 'created_at' => 'Submission Date'));
        $results = $wpdb->get_row("SELECT group_concat(entry_name separator ', ') as Keyss FROM {$wpdb->prefix}wpf_entry_meta WHERE entry_id IN($commaids) group by entry_id", ARRAY_A);
        $metakeyArray = explode(',', $results['Keyss']);
        $finalarray = array_merge($formKeys, $metakeyArray);
        return $finalarray;
    }

    function pf_save_global_recaptcha_form() {
        global $wpdb;

        $optformsitekey = sanitize_text_field($_POST['power_form_global_recaptcha_site_key']);
        $optformsecretkey = sanitize_text_field($_POST['power_form_global_form_secret_key']);

        update_option('opt-form-site-key', $optformsitekey);
        update_option('opt-form-secret-key', $optformsecretkey);
        $status = true;
        $msg = 'Form reCaptcha Settings has been Saved!';
        echo json_encode(array('status' => $status, 'message' => $msg));
        die();
    }

    function pf_send_smtp_test_email() {
        global $wpdb;

        $to = '';
        if (isset($_POST['power_form_smtp_mailer_to_email']) && !empty($_POST['power_form_smtp_mailer_to_email'])) {
            $to = sanitize_text_field($_POST['power_form_smtp_mailer_to_email']);
        }
        $subject = '';
        if (isset($_POST['power_form_smtp_mailer_email_subject']) && !empty($_POST['power_form_smtp_mailer_email_subject'])) {
            $subject = sanitize_text_field($_POST['power_form_smtp_mailer_email_subject']);
        }
        $message = '';
        if (isset($_POST['power_form_smtp_mailer_email_body']) && !empty($_POST['power_form_smtp_mailer_email_body'])) {
            $message = sanitize_text_field($_POST['power_form_smtp_mailer_email_body']);
        }
        if (wp_mail($to, $subject, $message)) {
            $status = true;
            $msg = 'Test Email has been Sent!';
        } else {
            $status = false;
            $msg = 'Test Email can not Sent!';
        }

        echo json_encode(array('status' => $status, 'message' => $msg));
        die();
    }

    function pf_save_smtp_settings() {
        global $wpdb;

        $nonce = $_REQUEST['_wpnonce'];
        if (!wp_verify_nonce($nonce, 'power_form_smtp_mailer_general_settings')) {
            echo json_encode(array('status' => false, 'message' => 'Error! Nonce Security Check Failed! please save the settings again.'));
            die();
        }
        $smtp_host = '';
        if (isset($_POST['power_form_smtp_host']) && !empty($_POST['power_form_smtp_host'])) {
            $smtp_host = sanitize_text_field($_POST['power_form_smtp_host']);
        }
        $smtp_auth = '';
        if (isset($_POST['power_form_smtp_auth']) && !empty($_POST['power_form_smtp_auth'])) {
            $smtp_auth = sanitize_text_field($_POST['power_form_smtp_auth']);
        }
        $smtp_username = '';
        if (isset($_POST['power_form_smtp_username']) && !empty($_POST['power_form_smtp_username'])) {
            $smtp_username = sanitize_text_field($_POST['power_form_smtp_username']);
        }
        $smtp_password = '';
        if (isset($_POST['power_form_smtp_password']) && !empty($_POST['power_form_smtp_password'])) {
            $smtp_password = sanitize_text_field($_POST['power_form_smtp_password']);
            $smtp_password = wp_unslash($smtp_password);
            $smtp_password = base64_encode($smtp_password);
        }
        $type_of_encryption = '';
        if (isset($_POST['power_form_type_of_encryption']) && !empty($_POST['power_form_type_of_encryption'])) {
            $type_of_encryption = sanitize_text_field($_POST['power_form_type_of_encryption']);
        }
        $smtp_port = '';
        if (isset($_POST['power_form_smtp_port']) && !empty($_POST['power_form_smtp_port'])) {
            $smtp_port = sanitize_text_field($_POST['power_form_smtp_port']);
        }
        $from_email = '';
        if (isset($_POST['power_form_from_email']) && !empty($_POST['power_form_from_email'])) {
            $from_email = sanitize_email($_POST['power_form_from_email']);
        }
        $from_name = '';
        if (isset($_POST['power_form_from_name']) && !empty($_POST['power_form_from_name'])) {
            $from_name = sanitize_text_field(stripslashes($_POST['power_form_from_name']));
        }
        $disable_ssl_verification = '';
        if (isset($_POST['power_form_disable_ssl_verification']) && !empty($_POST['power_form_disable_ssl_verification'])) {
            $disable_ssl_verification = sanitize_text_field($_POST['power_form_disable_ssl_verification']);
        }
        $options = array();
        $options['power_form_smtp_host'] = $smtp_host;
        $options['power_form_smtp_auth'] = $smtp_auth;
        $options['power_form_smtp_username'] = $smtp_username;
        $options['power_form_smtp_password'] = $smtp_password;
        $options['power_form_type_of_encryption'] = $type_of_encryption;
        $options['power_form_smtp_port'] = $smtp_port;
        $options['power_form_from_email'] = $from_email;
        $options['power_form_from_name'] = $from_name;
        $options['power_form_disable_ssl_verification'] = $disable_ssl_verification;
        $this->power_form_smtp_mailer_update_option($options);

        $status = true;
        $msg = 'Form SMTP Settings has been Saved!';

        echo json_encode(array('status' => $status, 'message' => $msg));
        die();
    }

    /**
     * Funtion for get SMTP Mailer Options
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     * 
     */
    function power_form_smtp_mailer_get_option() {
        $options = get_option('power_form_smtp_mailer_options');
        return $options;
    }

    function power_form_smtp_mailer_update_option($options) {
        update_option('power_form_smtp_mailer_options', $options);
    }

    function pf_save_global_settings() {
        global $wpdb;
        $optformclass = isset($_POST['formclass']) ? sanitize_text_field($_POST['formclass']) : '';
        $optformpermission = isset($_POST['formpermission']) ? sanitize_text_field($_POST['formpermission']) : '';
        $optformsucess = isset($_POST['formsuccess']) ? sanitize_text_field($_POST['formsuccess']) : '';
        $optformerror = isset($_POST['formerror']) ? sanitize_text_field($_POST['formerror']) : '';
        $optformgdprcheckboxtext = isset($_POST['formcheckbox']) ? sanitize_textarea_field($_POST['formcheckbox']) : '';
        $allowtags = $this->pf_allowed_html();
        $optformgdprformcheckboxtext = isset($_POST['formconsent']) ? wp_kses($_POST['formconsent'], $allowtags) : '';

        update_option('opt-form-class', $optformclass);
        update_option('opt-form-permission', $optformpermission);
        update_option('opt-form-sucess-message', $optformsucess);
        update_option('opt-form-error-message', $optformerror);
        update_option('opt-form-gdpr-checkbox-text', $optformgdprcheckboxtext);
        update_option('opt-form-gdpr-form-checkbox-text', $optformgdprformcheckboxtext);
        $status = true;
        $msg = 'Form Global Settings has been Saved!';

        echo json_encode(array('status' => $status, 'message' => $msg));
        die();
    }

    /**
     * Recursive sanitation for an array
     * 
     * @param void
     *
     * @return array
     */
    function pf_allowed_html() {

        $allowed_tags = array(
            'a' => array(
                'class' => array(),
                'href' => array(),
                'rel' => array(),
                'title' => array(),
            ),
            'abbr' => array(
                'title' => array(),
            ),
            'b' => array(),
            'blockquote' => array(
                'cite' => array(),
            ),
            'cite' => array(
                'title' => array(),
            ),
            'code' => array(),
            'del' => array(
                'datetime' => array(),
                'title' => array(),
            ),
            'dd' => array(),
            'div' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'dl' => array(),
            'dt' => array(),
            'em' => array(),
            'h1' => array(),
            'h2' => array(),
            'h3' => array(),
            'h4' => array(),
            'h5' => array(),
            'h6' => array(),
            'i' => array(),
            'img' => array(
                'alt' => array(),
                'class' => array(),
                'height' => array(),
                'src' => array(),
                'width' => array(),
            ),
            'li' => array(
                'class' => array(),
            ),
            'ol' => array(
                'class' => array(),
            ),
            'p' => array(
                'class' => array(),
            ),
            'q' => array(
                'cite' => array(),
                'title' => array(),
            ),
            'span' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'strike' => array(),
            'strong' => array(),
            'ul' => array(
                'class' => array(),
            ),
        );

        return $allowed_tags;
    }

    /**
     * Function for print with pre and then die();
     * 
     * @since   1.0
     * 
     * @param   array $arg arguments
     * @return  void
     * 
     */
    function pf_pd($arg) {
        echo '<pre>';
        print_r($arg);
        echo '</pre>';
        die();
    }

    /**
     * Function for Save Form Fields..
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  $html HTML
     * 
     */
    function pf_save_form_field_detail() {
        $postId = isset($_POST['postid']) ? sanitize_key(intval($_POST['postid'])) : '';
        $active_column = isset($_POST['active_column']) ? sanitize_text_field($_POST['active_column']) : '';
        $fields = isset($_POST['fields']) ? $this->recursive_sanitize_text_field($_POST['fields']) : '';
        $meta_key = 'pf_form_fields';
        $html = '';
        /*
         * Before Save Power Form
         */
        apply_filters('power_forms_before_save_form', $fields);
        update_post_meta($postId, 'pf_active_column', $active_column);
        if (update_post_meta($postId, $meta_key, maybe_serialize($fields))) {
            $html .= esc_attr__('Form Saved', 'power-forms');
        } else {
            $html .= esc_attr__('Form Saved', 'power-forms');
        }
        apply_filters('power_forms_after_save_form', $html);
        echo json_encode(array('html' => $html));
        die();
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

    /**
     * Function for Preview Form in Model
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  $html HTML
     * @return  $title Title
     * 
     */
    function pf_preview_form() {

        $postID = sanitize_key(isset($_POST['datapoppostID']) ? intval($_POST['datapoppostID']) : '');
        $title = esc_html(get_the_title($postID));
        $fields = isset($_POST['fields']) ? $this->recursive_sanitize_text_field($_POST['fields']) : '';
        $pf_active_column = sanitize_text_field(isset($_POST['active_column']) ? $_POST['active_column'] : '');
        $power_forms_form_fields = $fields;

        $form = new PhpFormBuilder();

        $form->set_att('method', 'post');
        $form->set_att('enctype', 'multipart/form-data');
        $form->set_att('markup', 'html');
        $form->set_att('class', array(''));
        $form->set_att('id', 'power_form_' . $postID);
        $form->set_att('add_honeypot', true);
        $form->set_att('add_nonce', 'a_contact_form');
        $form->set_att('form_element', false);
        $form->set_att('add_submit', true);
        $form->set_att('active_column', $pf_active_column);
        $form->set_att('postID', $postID);
        $form->set_att('novalidate', true);

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

        $formHtml = $form->build_form(false);

        echo json_encode(array('html' => $formHtml, 'title' => $title));
        die();
    }

}

$Power_Forms_Fields = new Power_Forms_Fields();
