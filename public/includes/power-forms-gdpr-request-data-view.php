<?php
/**
 * The public-facing functionality of the plugin GDPR.
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
?>
<div class="table-responsive">
    <div class="loadersOut"><span class="loaders"></span></div>
    <table id="example" class="table table-striped table-bordered" cellspacing="0" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </tfoot>
        <tbody>
            <?php
            foreach ($emailResults as $key => $value) {
                ?>
                <tr>
                    <?php
                    echo '<td>' . $value['entry_id'] . '</td>';
                    echo '<td>' . $value['entry_value'] . '</td>';
                    echo '<td>';
                    echo '<form method="post" style="display:inline-block;margin-right:2px"><button style="background-color:#0085ba !important;padding: 0px 5px !important;" type="button" data-id="' . $value['entry_id'] . '" class="" id="viewButton" data-toggle="modal" value="view" data-target="#exampleModal"><i class="fa fa-eye"></i></button></form>';
                    if (!CheckDeleteRequest($value['entry_id'])) {
                        echo '<form method="post" style="display:inline-block;margin-right:2px"><button style="background-color:#dc3545 !important;padding: 0px 5px !important;" type="button" class="deleteRequest" data-id="' . $value['entry_id'] . '" data-email="' . $value['entry_value'] . '"><i class="fa fa-trash"></i></button></form>';
                    }
                    echo '</td>';
                    ?>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
<!-- For 1.0.2-->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Entry Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body view-content" style="background: #f5f5f5;">

            </div>
            <div class="modal-footer">
                <button type="button" class="dt-button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- For 1.0.2-->
<?php

function getFormTitle($entryid) {
    global $wpdb;
    $result = $wpdb->get_row("SELECT form_name FROM {$wpdb->prefix}wpf_entries WHERE id = '$entryid'");
    return $result->form_name;
}

//<!-- For 1.0.2-->
function getFormID($entryid) {
    global $wpdb;
    $result = $wpdb->get_row("SELECT id FROM {$wpdb->prefix}wpf_entries WHERE form_id = '$entryid'");
    return $result->form_id;
}

//<!-- For 1.0.2-->
function CheckDeleteRequest($entryid) {
    global $wpdb;
    $result = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wpf_gdpr_delete_data WHERE data_id = '$entryid'");
    return $result;
}
