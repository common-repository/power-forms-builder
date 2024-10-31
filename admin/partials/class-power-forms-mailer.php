<?php
/**
 * The admin-specific functionality of the plugin for SMTP mailer
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
if (!defined('ABSPATH')) {
    exit;
}

class POWER_FORMS_MAILER {

    var $plugin_version = POWER_FORMS_VERSION;
    var $phpmailer_version = '5.2.22';
    var $plugin_url;
    var $plugin_path;

    /**
     * Constructor
     * 
     * @since   1.0
     * 
     * @param   void
     * @return void
     * 
     */
    function __construct() {
        define('POWER_FORMS_MAILER_VERSION', $this->plugin_version);
        define('POWER_FORMS_MAILER_SITE_URL', site_url());
        define('POWER_FORMS_MAILER_HOME_URL', home_url());
        define('POWER_FORMS_MAILER_URL', $this->plugin_url());
        define('POWER_FORMS_MAILER_PATH', $this->plugin_path());
    }

    /**
     * Funtion for Loading plugin URL
     * 
     * @since   1.0
     * 
     * @param   void
     * @return $this->plugin_url Plugin URL
     * 
     */
    function plugin_url() {
        if ($this->plugin_url)
            return $this->plugin_url;
        return $this->plugin_url = plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__));
    }

    /**
     * Funtion for Loading plugin Path
     * 
     * @since   1.0
     * 
     * @param   void
     * @return $this->plugin_path Plugin Path
     * 
     */
    function plugin_path() {
        if ($this->plugin_path)
            return $this->plugin_path;
        return $this->plugin_path = untrailingslashit(plugin_dir_path(__FILE__));
    }

    /**
     * Funtion for Adding Content of option page
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     * 
     */
    function options_page() {
        $this->general_settings();
        $this->test_email_settings();
    }

    /**
     * Funtion for Test Email Submission
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     * 
     */
    function test_email_settings() {
        ?>
        <form method="post" id="power_form_smtp_mailer_send_test_email" action="<?php echo esc_url($_SERVER["REQUEST_URI"] . '&tab=mailer_settings'); ?>">
            <table class="form-table">

                <tbody>

                    <tr valign="top">
                        <th scope="row"><label for="power_form_smtp_mailer_to_email"><?php _e('To', 'power-forms'); ?></label></th>
                        <td><input required="" name="power_form_smtp_mailer_to_email" type="text" id="power_form_smtp_mailer_to_email" value="" class="regular-text">
                            <p class="description"><?php esc_attr_e('Email address of the recipient', 'power-forms'); ?></p></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="power_form_smtp_mailer_email_subject"><?php _e('Subject', 'power-forms'); ?></label></th>
                        <td><input required="" name="power_form_smtp_mailer_email_subject" type="text" id="power_form_smtp_mailer_email_subject" value="" class="regular-text">
                            <p class="description"><?php esc_attr_e('Subject of the email', 'power-forms'); ?></p></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="power_form_smtp_mailer_email_body"><?php _e('Message', 'power-forms'); ?></label></th>
                        <td><textarea required="" name="power_form_smtp_mailer_email_body" id="power_form_smtp_mailer_email_body"></textarea>
                            <p class="description"><?php esc_attr_e('Email body', 'power-forms'); ?></p></td>
                    </tr>

                </tbody>

            </table>

            <p class="submit"><input type="submit" name="power_form_smtp_mailer_send_test_email" id="power_form_smtp_mailer_send_test_email" class="button button-primary" value="<?php _e('Send Email', 'power-forms'); ?>"></p>
        </form>

        <?php
    }

    /**
     * Funtion for Gernal SMTP Settings
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     * 
     */
    function general_settings() {

        $options = power_form_smtp_mailer_get_option();
        if (!is_array($options)) {
            $options = array();
            $options['power_form_smtp_host'] = '';
            $options['power_form_smtp_auth'] = '';
            $options['power_form_smtp_username'] = '';
            $options['power_form_smtp_password'] = '';
            $options['power_form_type_of_encryption'] = '';
            $options['power_form_smtp_port'] = '';
            $options['power_form_from_email'] = '';
            $options['power_form_from_name'] = '';
            $options['power_form_disable_ssl_verification'] = '';
        }

        if (!isset($options['power_form_disable_ssl_verification'])) {
            $options['power_form_disable_ssl_verification'] = '';
        }

        $smtp_password = '';
        if (isset($options['power_form_smtp_password']) && !empty($options['power_form_smtp_password'])) {
            $smtp_password = base64_decode($options['power_form_smtp_password']);
        }
        ?>

        <form method="post" id="power_form_smtp_gernel_settings" action="<?php echo esc_url($_SERVER["REQUEST_URI"] . '&tab=mailer_settings'); ?>">
            <?php wp_nonce_field('power_form_smtp_mailer_general_settings'); ?>
            <table class="form-table">

                <tbody> 

                    <tr valign="top">
                        <th scope="row"><label for="power_form_smtp_host"><?php _e('SMTP Host', 'power-forms'); ?></label></th>
                        <td><input required="" name="power_form_smtp_host" type="text" id="power_form_smtp_host" value="<?php echo esc_attr($options['power_form_smtp_host']); ?>" class="regular-text code">
                            <p class="description"><?php _e('The SMTP server which will be used to send email. For example: smtp.gmail.com', 'power-forms'); ?></p></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="power_form_smtp_auth"><?php _e('SMTP Authentication', 'power-forms'); ?></label></th>
                        <td>
                            <select required="" name="power_form_smtp_auth" id="power_form_smtp_auth">
                                <option value="true" <?php echo selected($options['power_form_smtp_auth'], 'true', false); ?>><?php esc_attr_e('True', 'power-forms'); ?></option>
                                <option value="false" <?php echo selected($options['power_form_smtp_auth'], 'false', false); ?>><?php esc_attr_e('False', 'power-forms'); ?></option>
                            </select>
                            <p class="description"><?php _e('Whether to use SMTP Authentication when sending an email (recommended: True).', 'power-forms'); ?></p>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="power_form_smtp_username"><?php _e('SMTP Username', 'power-forms'); ?></label></th>
                        <td><input required="" name="power_form_smtp_username" type="text" id="power_form_smtp_username" value="<?php echo esc_attr($options['power_form_smtp_username']); ?>" class="regular-text code">
                            <p class="description"><?php _e('Your SMTP Username.', 'power-forms'); ?></p></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="power_form_smtp_password"><?php _e('SMTP Password', 'power-forms'); ?></label></th>
                        <td><input required="" name="power_form_smtp_password" type="password" id="power_form_smtp_password" value="<?php echo esc_attr($smtp_password); ?>" class="regular-text code">
                            <p class="description"><?php _e('Your SMTP Password.', 'power-forms'); ?></p></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="power_form_type_of_encryption"><?php _e('Type of Encryption', 'power-forms'); ?></label></th>
                        <td>
                            <select required="" name="power_form_type_of_encryption" id="power_form_type_of_encryption">
                                <option value="tls" <?php echo selected($options['power_form_type_of_encryption'], 'tls', false); ?>><?php esc_attr_e('TLS', 'power-forms'); ?></option>
                                <option value="ssl" <?php echo selected($options['power_form_type_of_encryption'], 'ssl', false); ?>><?php esc_attr_e('SSL', 'power-forms'); ?></option>
                                <option value="none" <?php echo selected($options['power_form_type_of_encryption'], 'none', false); ?>><?php esc_attr_e('No Encryption', 'power-forms'); ?></option>
                            </select>
                            <p class="description"><?php _e('The encryption which will be used when sending an email (recommended: TLS).', 'power-forms'); ?></p>
                        </td>
                    </tr>                   

                    <tr valign="top">
                        <th scope="row"><label for="power_form_smtp_port"><?php _e('SMTP Port', 'power-forms'); ?></label></th>
                        <td><input required="" name="power_form_smtp_port" type="text" id="power_form_smtp_port" value="<?php echo esc_attr(intval($options['power_form_smtp_port'])); ?>" class="regular-text code">
                            <p class="description"><?php _e('The port which will be used when sending an email (587/465/25). If you choose TLS it should be set to 587. For SSL use port 465 instead.', 'power-forms'); ?></p></td>
                    </tr>                                      

                    <tr valign="top">
                        <th scope="row"><label for="power_form_from_email"><?php _e('From Email Address', 'power-forms'); ?></label></th>
                        <td><input name="power_form_from_email" type="text" id="power_form_from_email" value="<?php echo esc_attr(is_email($options['power_form_from_email'])); ?>" class="regular-text code">
                            <p class="description"><?php _e('The email address which will be used as the From Address if it is not supplied to the mail function.', 'power-forms'); ?></p></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="power_form_from_name"><?php _e('From Name', 'power-forms'); ?></label></th>
                        <td><input name="power_form_from_name" type="text" id="power_form_from_name" value="<?php echo esc_attr($options['power_form_from_name']); ?>" class="regular-text code">
                            <p class="description"><?php _e('The name which will be used as the From Name if it is not supplied to the mail function.', 'power-forms'); ?></p></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="power_form_disable_ssl_verification"><?php _e('Disable SSL Certificate Verification', 'power-forms'); ?></label></th>
                        <td><input name="power_form_disable_ssl_verification" type="checkbox" id="power_form_disable_ssl_verification" <?php checked($options['power_form_disable_ssl_verification'], 1); ?> value="1">
                            <p class="description"><?php _e('As of PHP 5.6 you will get a warning/error if the SSL certificate on the server is not properly configured. You can check this option to disable that default behaviour. Please note that PHP 5.6 made this change for a good reason. So you should get your host to fix the SSL configurations instead of bypassing it', 'power-forms'); ?></p></td>
                    </tr>

                </tbody>

            </table>

            <p class="submit"><input type="submit" name="power_form_smtp_mailer_update_settings" id="smtp_mailer_update_settings" class="button button-primary" value="<?php _e('Save Changes', 'power-forms') ?>"></p>
        </form>

        <?php
    }

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

/**
 * Funtion for Update SMTP Mailer Options
 * 
 * @since   1.0
 * 
 * @param   void
 * @return  void
 * 
 */
function power_form_smtp_mailer_update_option($options) {
    update_option('power_form_smtp_mailer_options', $options);
}

$GLOBALS['power_form_smtp_mailer'] = new POWER_FORMS_MAILER();

/**
 * Funtion for override the wp_mail funtion
 * 
 * @since   1.0
 * 
 * @param   $to
 * @param   $subject
 * @param   $message
 * @param   $headers
 * @param   $attachments = array()
 * @return  bool
 * 
 */
if (!function_exists('wp_mail')) {

    function wp_mail($to, $subject, $message, $headers = '', $attachments = array()) {
        $atts = apply_filters('wp_mail', compact('to', 'subject', 'message', 'headers', 'attachments'));

        if (isset($atts['to'])) {
            $to = $atts['to'];
        }

        if (!is_array($to)) {
            $to = explode(',', $to);
        }

        if (isset($atts['subject'])) {
            $subject = $atts['subject'];
        }

        if (isset($atts['message'])) {
            $message = $atts['message'];
        }

        if (isset($atts['headers'])) {
            $headers = $atts['headers'];
        }

        if (isset($atts['attachments'])) {
            $attachments = $atts['attachments'];
        }

        if (!is_array($attachments)) {
            $attachments = explode("\n", str_replace("\r\n", "\n", $attachments));
        }

        $options = power_form_smtp_mailer_get_option();

        global $phpmailer;

        if (!( $phpmailer instanceof PHPMailer )) {
            require_once ABSPATH . WPINC . '/class-phpmailer.php';
            require_once ABSPATH . WPINC . '/class-smtp.php';
            $phpmailer = new PHPMailer(true);
        }

        $cc = $bcc = $reply_to = array();

        if (empty($headers)) {
            $headers = array();
        } else {
            if (!is_array($headers)) {
                $tempheaders = explode("\n", str_replace("\r\n", "\n", $headers));
            } else {
                $tempheaders = $headers;
            }
            $headers = array();

            if (!empty($tempheaders)) {
                foreach ((array) $tempheaders as $header) {
                    if (strpos($header, ':') === false) {
                        if (false !== stripos($header, 'boundary=')) {
                            $parts = preg_split('/boundary=/i', trim($header));
                            $boundary = trim(str_replace(array("'", '"'), '', $parts[1]));
                        }
                        continue;
                    }
                    list( $name, $content ) = explode(':', trim($header), 2);

                    $name = trim($name);
                    $content = trim($content);

                    switch (strtolower($name)) {
                        case 'from':
                            $bracket_pos = strpos($content, '<');
                            if ($bracket_pos !== false) {
                                if ($bracket_pos > 0) {
                                    $from_name = substr($content, 0, $bracket_pos - 1);
                                    $from_name = str_replace('"', '', $from_name);
                                    $from_name = trim($from_name);
                                }

                                $from_email = substr($content, $bracket_pos + 1);
                                $from_email = str_replace('>', '', $from_email);
                                $from_email = trim($from_email);
                            } elseif ('' !== trim($content)) {
                                $from_email = trim($content);
                            }
                            break;
                        case 'content-type':
                            if (strpos($content, ';') !== false) {
                                list( $type, $charset_content ) = explode(';', $content);
                                $content_type = trim($type);
                                if (false !== stripos($charset_content, 'charset=')) {
                                    $charset = trim(str_replace(array('charset=', '"'), '', $charset_content));
                                } elseif (false !== stripos($charset_content, 'boundary=')) {
                                    $boundary = trim(str_replace(array('BOUNDARY=', 'boundary=', '"'), '', $charset_content));
                                    $charset = '';
                                }
                            } elseif ('' !== trim($content)) {
                                $content_type = trim($content);
                            }
                            break;
                        case 'cc':
                            $cc = array_merge((array) $cc, explode(',', $content));
                            break;
                        case 'bcc':
                            $bcc = array_merge((array) $bcc, explode(',', $content));
                            break;
                        case 'reply-to':
                            $reply_to = array_merge((array) $reply_to, explode(',', $content));
                            break;
                        default:
                            $headers[trim($name)] = trim($content);
                            break;
                    }
                }
            }
        }

        $phpmailer->clearAllRecipients();
        $phpmailer->clearAttachments();
        $phpmailer->clearCustomHeaders();
        $phpmailer->clearReplyTos();

        if (!isset($from_name)) {
            $from_name = $options['power_form_from_name'];
        }
        if (!isset($from_email)) {
            $sitename = strtolower($_SERVER['SERVER_NAME']);
            if (substr($sitename, 0, 4) == 'www.') {
                $sitename = substr($sitename, 4);
            }

            $from_email = $options['power_form_from_email'];
        }

        $from_email = apply_filters('wp_mail_from', $from_email);
        $from_name = apply_filters('wp_mail_from_name', $from_name);

        try {
            $phpmailer->setFrom($from_email, $from_name, false);
        } catch (phpmailerException $e) {
            $mail_error_data = compact('to', 'subject', 'message', 'headers', 'attachments');
            $mail_error_data['phpmailer_exception_code'] = $e->getCode();

            do_action('wp_mail_failed', new WP_Error('wp_mail_failed', $e->getMessage(), $mail_error_data));

            return false;
        }

        $phpmailer->Subject = $subject;
        $phpmailer->Body = $message;

        $address_headers = compact('to', 'cc', 'bcc', 'reply_to');

        foreach ($address_headers as $address_header => $addresses) {
            if (empty($addresses)) {
                continue;
            }

            foreach ((array) $addresses as $address) {
                try {
                    $recipient_name = '';

                    if (preg_match('/(.*)<(.+)>/', $address, $matches)) {
                        if (count($matches) == 3) {
                            $recipient_name = $matches[1];
                            $address = $matches[2];
                        }
                    }

                    switch ($address_header) {
                        case 'to':
                            $phpmailer->addAddress($address, $recipient_name);
                            break;
                        case 'cc':
                            $phpmailer->addCc($address, $recipient_name);
                            break;
                        case 'bcc':
                            $phpmailer->addBcc($address, $recipient_name);
                            break;
                        case 'reply_to':
                            $phpmailer->addReplyTo($address, $recipient_name);
                            break;
                    }
                } catch (phpmailerException $e) {
                    continue;
                }
            }
        }

        $phpmailer->isSMTP();
        $phpmailer->Host = $options['power_form_smtp_host'];
        if (isset($options['power_form_smtp_auth']) && $options['power_form_smtp_auth'] == "true") {
            $phpmailer->SMTPAuth = true;
            $phpmailer->Username = $options['power_form_smtp_username'];
            $phpmailer->Password = base64_decode($options['power_form_smtp_password']);
        }
        $type_of_encryption = $options['power_form_type_of_encryption'];
        if ($type_of_encryption == "none") {
            $type_of_encryption = '';
        }
        $phpmailer->SMTPSecure = $type_of_encryption;
        $phpmailer->Port = $options['power_form_smtp_port'];

        $phpmailer->SMTPAutoTLS = false;
        if (isset($_POST['power_form_smtp_mailer_send_test_email'])) {
            $phpmailer->SMTPDebug = 4;
            $phpmailer->Debugoutput = 'html';
        }

        if (isset($options['power_form_disable_ssl_verification']) && !empty($options['power_form_disable_ssl_verification'])) {
            $phpmailer->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        }

        if (!isset($content_type))
            $content_type = 'text/html';

        $content_type = apply_filters('wp_mail_content_type', $content_type);

        $phpmailer->ContentType = $content_type;

        if ('text/html' == $content_type)
            $phpmailer->isHTML(true);

        if (!isset($charset))
            $charset = get_bloginfo('charset');


        $phpmailer->CharSet = apply_filters('wp_mail_charset', $charset);

        if (!empty($headers)) {
            foreach ((array) $headers as $name => $content) {
                $phpmailer->addCustomHeader(sprintf('%1$s: %2$s', $name, $content));
            }

            if (false !== stripos($content_type, 'multipart') && !empty($boundary))
                $phpmailer->addCustomHeader(sprintf("Content-Type: %s;\n\t boundary=\"%s\"", $content_type, $boundary));
        }

        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                try {
                    $phpmailer->addAttachment($attachment);
                } catch (phpmailerException $e) {
                    continue;
                }
            }
        }

        do_action_ref_array('phpmailer_init', array(&$phpmailer));

        try {
            return $phpmailer->send();
        } catch (phpmailerException $e) {

            $mail_error_data = compact('to', 'subject', 'message', 'headers', 'attachments');
            $mail_error_data['phpmailer_exception_code'] = $e->getCode();
            do_action('wp_mail_failed', new WP_Error('wp_mail_failed', $e->getMessage(), $mail_error_data));

            return false;
        }
    }

}
