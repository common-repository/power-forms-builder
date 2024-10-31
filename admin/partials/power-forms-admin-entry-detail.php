<?php
/**
 * The admin-specific functionality of the plugin for Form Builder entry detail view
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
?>
<div id="power_form_show_entry_page" style="margin: 0px">
    <div class="pfbcontainer">
        <header class="codrops-header">
            <h1>PowerFormBuilder Entry Detail <span>WordPress Contact Form Plugin PowerFormBuilder is the ultimate FREE and intuitive FORM creation tool for WordPress</span></h1>
            <p class="support">Your browser does not support <strong>flexbox</strong>! <br />Please view this demo with a <strong>modern browser</strong>.</p>
        </header>
        <section>
            <div class="tabs tabs-style-linebox">
                <nav id="stickynavbar">
                    <ul>
                        <li class="tab-current"><a onclick="location.href = '<?php echo site_url(); ?>/wp-admin/edit.php?post_type=powerform&page=power-form-entries';" href="<?php echo site_url(); ?>/wp-admin/edit.php?post_type=powerform&page=power-form-entries" class=""><span>Entries</span></a></li>
                        <li class=""><a onclick="location.href = '<?php echo site_url(); ?>/wp-admin/edit.php?post_type=powerform&page=power-form-gdpr-requests';" href="<?php echo site_url(); ?>/wp-admin/edit.php?post_type=powerform&page=power-form-gdpr-requests" class=""><span>Data Request</span></a></li>
                        <li class=""><a onclick="location.href = '<?php echo site_url(); ?>/wp-admin/edit.php?post_type=powerform&page=power-form-gdpr-delete';" href="<?php echo site_url(); ?>/wp-admin/edit.php?post_type=powerform&page=power-form-gdpr-delete" class=""><span>Delete Data Request</span></a></li>
                        <li class=""><a onclick="location.href = '<?php echo site_url(); ?>/wp-admin/edit.php?post_type=powerform&page=power-form-settings';" href="<?php echo site_url(); ?>/wp-admin/edit.php?post_type=powerform&page=power-form-settings" class=""><span>Settings</span></a></li>
                    </ul>
                </nav>
                <div class="content-wrap" style="width: 100%">

                    <section id="section-underline-1" class="content-current">
                        <div class="power_forms">
                            <div id="poststuff" style="padding: 0px;">
                                <div id="post-body" class="metabox-holder columns-3">
                                    <div id="postbox-container-1" class="postbox-container power_no_print">
                                        <div id="submitdiv" class="postbox">
                                            <h3 class="hndle"><span><?php echo esc_attr__('Entry Actions', 'power-forms'); ?></span></h3>
                                            <div class="inside">
                                                <div class="submitbox">
                                                    <div id="minor-publishing" class="power_remove_border">
                                                        <div class="misc-pub-section">
                                                            <div class="clear"></div>
                                                        </div>
                                                        <div id="misc-publishing-actions">
                                                            <div class="misc-pub-section curtime misc-pub-curtime">
                                                                <span id="timestamp">
                                                                    <?php echo esc_attr__('Published on:', 'power-forms'); ?> <b><?php echo date('M j, Y, g:i a', strtotime($entryrecords['created_at'])); ?></b></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="major-publishing-actions">
                                                        <a style="color: red;text-decoration: none" href="edit.php?post_type=powerform&page=power-form-entries&action=delete&entry=<?php echo $entryrecords['id']; ?>" onclick="return confirm('Are you sure you want to delete that entry?');" title="Delete"><?php echo __('Delete', 'power-forms'); ?></a>
                                                        <div class="clear"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="postbox power_with_icons">
                                            <h3 class="hndle"><span><?php echo esc_attr__('Entry Detail', 'power-forms'); ?></span></h3>
                                            <div class="inside">

                                                <div class="misc-pub-section">
                                                    <span class="dashicons dashicons-id wp-media-buttons-icon"></span>
                                                    <?php echo esc_attr__('Entry ID:', 'power-forms'); ?>
                                                    <b><?php echo esc_attr(intval($entryrecords['id'])); ?></b>
                                                </div>

                                                <div class="misc-pub-section">
                                                    <span class="dashicons dashicons-post-status wp-media-buttons-icon"></span>
                                                    <?php echo esc_attr__('Form ID:', 'power-forms'); ?>
                                                    <b><?php echo esc_attr(intval($entryrecords['form_id'])); ?></b>
                                                </div>

                                                <div class="misc-pub-section">
                                                    <span class="dashicons dashicons-editor-table wp-media-buttons-icon"></span>
                                                    <?php echo esc_attr__('Form Title:', 'power-forms'); ?>
                                                    <b><?php echo esc_attr($entryrecords['form_name']); ?></b>
                                                </div>
                                                <div class="misc-pub-section">
                                                    <span class="dashicons dashicons-admin-users wp-media-buttons-icon"></span>
                                                    <?php echo esc_attr__('User:', 'power-forms'); ?>
                                                    <b><?php
                                                        if (!empty($entryrecords['user_id'])) {
                                                            $user_info = get_userdata($entryrecords['user_id']);
                                                            $username = $user_info->user_login;
                                                            echo __($username, 'power-forms');
                                                        } else {
                                                            echo esc_attr__('anonymous', 'power-forms');
                                                        }
                                                        ?></b>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="postbox">
                                            <h3 class="hndle"><span><?php echo esc_attr__('User Information', 'power-forms'); ?></span></h3>
                                            <div class="inside">
                                                <div class="misc-pub-section">
                                                    <?php echo esc_attr__('IP Address:', 'power-forms'); ?>
                                                    <b><?php echo esc_attr($entryrecords['ip']); ?></b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="post-body-content">
                                        <div id="titlediv">
                                        </div>
                                        <div class="postbox">
                                            <h3 class="hndle"><span><?php echo esc_attr__('Entry#' . intval($entryrecords['id']), 'power-forms'); ?></span></h3>
                                            <div class="inside">
                                                <table class="form-table" style="margin-bottom: 50px;">
                                                    <tbody>
                                                        <?php
                                                        foreach ($records as $key => $record) {
                                                            if (substr($record['entry_name'], 0, 7) == 'pf_File' && substr($record['entry_name'], -3) != 'url') {
                                                                continue;
                                                            } else {
                                                                if (substr($record['entry_name'], 0, 7) == 'pf_File' && substr($record['entry_name'], -3) == 'url') {
                                                                    ?>
                                                                    <tr>
                                                                        <?php if (pathinfo($record['entry_value'])['extension'] == 'jpg' || pathinfo($record['entry_value'])['extension'] == 'jpeg' || pathinfo($record['entry_value'])['extension'] == 'png' || pathinfo($record['entry_value'])['extension'] == 'gif') { ?>   
                                                                            <td style="font-size:14px;font-weight: bold"><?php echo getKeylabel($record['entry_name'], $entryrecords['form_id']); ?></td><td style="font-size:14px;"><img src="<?php echo $record['entry_value']; ?>" style="width:100px;height:100px" /></td>
                                                                        <?php } else { ?>
                                                                            <td style="font-size:14px;font-weight: bold"><?php echo getKeylabel($record['entry_name'], $entryrecords['form_id']); ?></td><td><a class="page-title-action" href="<?php echo $record['entry_value']; ?>"><?php echo __('Download File', 'power-forms'); ?></a></td>
                                                                        <?php } ?>   
                                                                    <?php } else if (substr($record['entry_name'], 0, 7) != 'pf_File') {
                                                                        ?>    
                                                                        <td style="font-size:14px;font-weight: bold">
                                                                            <?php
                                                                            if (isset($record['entry_name']) && $record['entry_name'] !== 'Total') {
                                                                                $entryKey = $record['entry_name'];
                                                                            } else {
                                                                                $entryKey = $record['entry_name'];
                                                                            }
                                                                            echo getKeylabel($entryKey, $entryrecords['form_id']);
                                                                            ?></td><td style="font-size:14px;"><?php echo $record['entry_value']; ?></td>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <a style="margin-left: 0px;" href="<?php echo site_url(); ?>/wp-admin/edit.php?post_type=powerform&page=power-form-entries" class="page-title-action"><?php echo esc_attr__('Back to Entries', 'power-forms'); ?></a>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div><!-- /content -->
            </div><!-- /tabs -->
        </section>
    </div>


</div>

<?php

function getKeylabel($kee,$formid) {
    $label = '';
    $power_forms_form_fieldss = maybe_unserialize(get_post_meta($formid, 'pf_form_fields', true));
    foreach ($power_forms_form_fieldss as $key => $field) {
        if ($kee == $field['pf_title']) {
            $label = $field['pf_label'];
        }
    }
    if (empty($label)) {
        if (substr($kee, -4) == 'year') {
            $kee = 'Expiry Year';
        } else if (substr($kee, -5) == 'month') {
            $kee = 'Expiry Month';
        } else if (substr($kee, -4) == 'code') {
            $kee = 'Security Code';
        } else if (substr($kee, -4) == 'card') {
            $kee = 'Card Number';
        } else if (substr($kee, -4) == 'last') {
            $kee = 'Last Name';
        } else if (substr($kee, 3, -6) == 'total') {
            $kee = 'Total';
        } else if (substr($kee, -9) == 'gfconsent') {
            $kee = 'Entry Consent';
        } else if (substr($kee, -5) == 'email') {
            $kee = 'Confirm Email';
        } else {
            $kee = $kee;
        }
        return $kee;
    } else {
        return $label;
    }
}
?>