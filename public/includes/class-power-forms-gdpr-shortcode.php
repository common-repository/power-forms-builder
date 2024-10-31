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
class Power_Forms_Public_GDPR_Shortcode {

    /**
     * Constructor
     *
     * @since    1.0.0
     * @param void
     * @return void
     */
    public function __construct() {

        add_shortcode('POWER_FORMS_REQ_DATA', array($this, 'power_form_gdpr_display'));

        add_action('wp_ajax_nopriv_pf_save_frontend_gdpr_form', array($this, 'pf_save_frontend_gdpr_form'));
        add_action('wp_ajax_pf_save_frontend_gdpr_form', array($this, 'pf_save_frontend_gdpr_form'));

        add_action('wp_ajax_nopriv_pf_save_delete_request', array($this, 'pf_save_delete_request'));
        add_action('wp_ajax_pf_save_delete_request', array($this, 'pf_save_delete_request'));

        add_action('wp_ajax_nopriv_pf_open_frontend_gdpr_data_detail', array($this, 'pf_open_frontend_gdpr_data_detail'));
        add_action('wp_ajax_pf_open_frontend_gdpr_data_detail', array($this, 'pf_open_frontend_gdpr_data_detail'));
    }

    /**
     * Funtion for opening data detail
     *
     * @since    1.0.0
     * @param void
     * @return $request
     */
    function pf_open_frontend_gdpr_data_detail() {
        global $wpdb;
        $formid = sanitize_key(intval($_POST['formId']));
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpf_entry_meta WHERE entry_id = $formid", ARRAY_A);
        $body = ' <table class="form-table"><tbody><tr>';
        $keys = 0;
        foreach ($results as $key => $record) {
            if (substr($record['entry_name'], 0, 7) == 'pf_File' && substr($record['entry_name'], -3) != 'url') {
                continue;
            } else {
                $body .= '<tr>';
                if (substr($record['entry_name'], 0, 7) == 'pf_File' && substr($record['entry_name'], -3) == 'url') {
                    if (pathinfo($record['entry_value'])['extension'] == 'jpg' || pathinfo($record['entry_value'])['extension'] == 'jpeg' || pathinfo($record['entry_value'])['extension'] == 'png' || pathinfo($record['entry_value'])['extension'] == 'gif') {
                        $body .= '<td style="font-size:12px !important;min-width:70px"><img src="' . $record['entry_value'] . '" style="width:150px;height:150px" /></td>';
                    } else {
                        $body .= '<td style="font-size:12px !important;min-width:70px"><a style="font-size:12px !important;" class="page-title-action" href="' . $record['entry_value'] . '">Download File</a></td>';
                    }
                } else if (substr($record['entry_name'], 0, 7) != 'pf_File') {

                    $body .= '<td style="font-size:12px !important;min-width:70px"> ' . $record['entry_value'] . ' </td>';
                }
                $body .= '</tr>';
                $keys++;
            }
        }

        $body .= '</tr>';
        $body .= '</tbody>';
        $body .= '</table>';
        $html = $body;
        echo json_encode(array('html' => $html));
        die();
    }

    /**
     * Funtion addition of request data
     *
     * @since    1.0.0
     * @param void
     * @return $request
     */
    function pf_save_delete_request() {
        global $wpdb;
        $pfid = sanitize_key(intval($_POST['pfid']));
        $pfemail = sanitize_email($_POST['pfemail']);
        $updated_at = current_time('mysql');
        $table_name = $wpdb->prefix . 'wpf_gdpr_delete_data';
        $request = $wpdb->insert(
                $table_name, array(
            'email' => $pfemail,
            'data_id' => $pfid,
            'status' => 'Processing',
            'requested_at' => $updated_at,
                )
        );
        $html = '';
        if ($request) {
            $formsuccess = get_option('opt-form-sucess-message');
            $html = '<div class="sucess_message row" style="margin-bottom:10px !important">' . __('You Request for delete this data has been Submiited.', 'power-forms') . '</div>';
        } else {
            $error = get_option('opt-form-error-message');
            $html = '<div class="error_other row" style="margin-bottom:10px !important">' . __('You Request for delete this data can not be submiited, please try again.', 'power-forms') . '</div>';
        }
        echo json_encode(array('html' => $html));
        die();
    }

    /**
     * Funtion for returning entry meta
     *
     * @since    1.0.0
     * @param int $entryid
     * @return $entry meta
     */
    function getRequestUsingSlug($slug) {
        global $wpdb;
        $results = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wpf_gdpr_requested_data WHERE slug = '$slug'");
        return $results;
    }

    /**
     * Funtion for returning entry meta
     *
     * @since    1.0.0
     * @param int $entryid
     * @return $entry meta
     */
    function getEntryMetaUsingEmail($email) {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM wp_wpf_entry_meta WHERE entry_value = '$email' AND entry_name NOT LIKE '%url'", ARRAY_A);
        return $results;
    }

    /**
     * Funtion for displaying shortcode output
     *
     * @since    1.0.0
     * @param array $atts
     * @return Form
     */
    function power_form_gdpr_display($atts) {
        $slug = isset($_GET['pflink']) ? sanitize_key($_GET['pflink']) : '';
        if ($slug) {
            global $wpdb;
            $result = $this->getRequestUsingSlug($slug);
            if ($result) {
                $table = "{$wpdb->prefix}wpf_gdpr_requested_data";
                if ($result->status != 'Visited') {
                    $wpdb->update(
                            $table, array(
                        'status' => 'Visited',
                            ), array('id' => $result->id), array(
                        '%s',
                            ), array('%d')
                    );
                }
                $emailResults = $this->getEntryMetaUsingEmail($result->email);
                require_once plugin_dir_path(dirname(__FILE__)) . '/includes/power-forms-gdpr-request-data-view.php';
            }
        } else {
            $chekcbixtext = get_option('opt-form-gdpr-checkbox-text');
            $form = '<div id="power_forms_main_continer"><form method="post" enctype="multipart/form-data" action="" id="power_forms_gdpr_code" class="">'
                    . '<div class="loadersOut"><span class="loaders"></span></div>'
                    . '<div class="col-xs-12 col-sm-12 col-lg-12" style="padding:0px">'
                    . '<div class="power_form_handle pf_form_field_wrap ">'
                    . '<label style="display:inline-block;color:#444444" id="pf_gdpr_email_label" for="pf_gdpr_email_label">Email</label><strong style="color:#dd3333 !important" id="pf_gdpr_email_label_text_required">*</strong>'
                    . '<input name="pf_gdpr_email" id="pf_gdpr_email" required="" type="email" placeholder="Please enter an email"></div></div>'
                    . '<div class="col-xs-12 col-sm-12 col-lg-12" style="padding:0px">'
                    . '<input type="checkbox" required="" name="pf_gdpr_checkbox" id="pf_gdpr_checkbox" />' . $chekcbixtext
                    . '</div>'
                    . '<div style="clear:both" class="pf_form_field_wrap container-fluid">'
                    . '<input style="width:auto;padding:10px 20px 10px 20px !important;border:none;font-weight: normal;font-size: 14px;" class="custom" id="power_forms_button_192" type="submit" value="Submit" name="submit">'
                    . '</div>'
                    . '</form><script>jQuery("#power_forms_gdpr_code").validate();</script></div>';
            return $form;
        }
    }

    /**
     * Funtion for hanling frontend for submission
     *
     * @since    1.0.0
     * @param string $string
     * @return response 
     */
    function pf_save_frontend_gdpr_form() {
        global $wpdb;
        $gdpremail = isset($_POST['gdpremail']) ? sanitize_email($_POST['gdpremail']) : '';
        $gdprCheck = isset($_POST['gdprCheck']) ? sanitize_key($_POST['gdprCheck']) : '';
        $updated_at = current_time('mysql');
        $html = '';
        if ($this->CheckEmailExists($gdpremail) == false) {
            $salt = $this->buildEmailTemplate($gdpremail);
            if ($salt) {
                $table_name = $wpdb->prefix . 'wpf_gdpr_requested_data';
                $request = $wpdb->insert(
                        $table_name, array(
                    'email' => $gdpremail,
                    'status' => 'Email Sent',
                    'slug' => $salt,
                    'requested_at' => $updated_at,
                        )
                );
                if ($request) {
                    $formsuccess = get_option('opt-form-sucess-message');
                    $html = '<div class="sucess_message">' . __($formsuccess, 'power-froms') . '</div>';
                } else {
                    $error = get_option('opt-form-error-message');
                    $html = '<div class="error_other">' . __($error, 'power-froms') . '</div>';
                }
            } else {
                $html = '<div class="error_other">Email can not be sent, Please check your smtp settings.</div>';
            }
        } else {
            $html = '<div class="error_other">You already requested for the data against this email.</div>';
        }

        echo json_encode(array('html' => $html));
        die();
    }

    function CheckEmailExists($email) {
        global $wpdb;
        $result = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wpf_gdpr_requested_data WHERE email = '$email'");
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Funtion for hanling User Email
     *
     * @since    1.0.0
     * @param int $formid
     * @param int $entryid
     * @return bool 
     */
    function buildEmailTemplate($email) {
        ob_start();
        $pageid = get_option('opt-form-page-id');
        $salt = md5(rand(1000, 99999999));
        $link = get_page_link($pageid) . '?pflink=' . $salt;
        if ($link) {
            $to = $email;
            $from = "support@powerformbuilder.com";
            $subject = "GDPR - Data Request";
            $body = "<h3>Dear sir/madam,</h3><p>You asked to access your personal data of power forms.By clicking on the button you can view or delete your entries created on the power forms.</p>
                <div><a href='" . $link . "' style='text-decoration:none;width:auto;color:#ffffff;background-color:#0085ba;padding:10px 20px 10px 20px !important;border:none;font-weight: normal;font-size: 14px;'>Check Your Data</a></div>
                <h4>Regards,</h4>
                <h5>Team Power Forms.</h5>";
            $headers = 'From: ' . $from . '';
            $attachments = array();
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

            apply_filters('power_forms_before_user_gdpr_request_email_send', $hookssArray);
            if (wp_mail($pfto, $pfsubject, $pfbody, $headers, $attachments)) {
                return $salt;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}

new Power_Forms_Public_GDPR_Shortcode();
