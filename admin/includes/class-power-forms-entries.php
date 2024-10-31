<?php
/**
 * The admin-specific functionality of the plugin for showing the Power Forms Entries
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
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Entries_List_Table extends WP_List_Table {

    function __construct() {
        global $status, $page;
        /**
         * Constructor.
         * 
         * @since   1.0
         * 
         * @param   void
         * @return  void
         * 
         */
        parent::__construct(array(
            'singular' => 'entry', //singular name of the listed records
            'plural' => 'entries', //plural name of the listed records
            'ajax' => true        //does this table support ajax?
        ));
    }

    /**
     * Funtion for Getting the power form all entries.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  $results Entries
     * 
     */
    function getEntries() {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpf_entries", ARRAY_A);
        return $results;
    }

    /**
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title() 
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as 
     * possible. 
     * 
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     * 
     * For more detailed insight into how columns are handled, take a look at 
     * WP_List_Table::single_row_columns()
     * 
     * @since   1.0
     * 
     * @param   $item  Item
     * @param   $column_name  Column Name
     * @return  $item
     * 
     */
    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'id':
            case 'form_id':
            case 'form_name':
            case 'user_id':
            case 'ip':
            case 'created_at':
            case 'updated_at':
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }

    /**
     *  Recommended. This is a custom column method and is responsible for what
     *  is rendered in any column with a name/slug of 'title'. Every time the class
     *  needs to render a column, it first looks for a method named 
     *  column_{$column_title} - if it exists, that method is run. If it doesn't
     *  exist, column_default() is called instead.
     * 
     *  This example also illustrates how to implement rollover actions. Actions
     *  should be an associative array formatted as 'slug'=>'link html' - and you
     *  will need to generate the URLs yourself. You could even ensure the links
     * 
     * @since   1.0
     * 
     * @param   $item  Item
     * @return  $actions Row Action
     * 
     */
    function column_id($item) {
        // edit.php?post_type=powerform&page=%s&action=%s&entry=%s    
        $actions = array(
            'edit' => sprintf('<a class="viewEntrys" id="viewEnters" data-entryid="' . intval($item['id']) . '" href="edit.php?post_type=powerform&page=%s&action=%s&entry=%s">' . esc_attr__('View', 'power-forms') . '</a>', $_REQUEST['page'], 'view', intval($item['id'])),
            'delete' => sprintf('<a class="deleteEntrys" href="edit.php?post_type=powerform&page=%s&action=%s&entry=%s">' . esc_attr__('Delete', 'power-forms') . '</a>', $_REQUEST['page'], 'delete', intval($item['id'])),
        );
        return sprintf('%1$s <span style="color:#000">%2$s</span>%3$s', '', $item['id'], $this->row_actions($actions)
        );
    }

    /**
     *  REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     *  is given special treatment when columns are processed. It ALWAYS needs to
     *  have it's own method.
     * 
     * @since   1.0
     * 
     * @param   $item  Item
     * @return  $actions Row Action
     * 
     */
    function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item['id']
        );
    }

    /**
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  $columns Column
     * 
     */
    function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'id' => esc_attr__('ID', 'power-forms'),
            'form_name' => esc_attr__('Form', 'power-forms'),
            'created_at' => esc_attr__('Submitted', 'power-forms'),
            'user_id' => esc_attr__('User', 'power-forms'),
            'ip' => esc_attr__('IP Address', 'power-forms'),
        );
        return $columns;
    }

    /**
     * Get the user base on user id
     * 
     * @since   1.0
     * 
     * @param   $item Item
     * @return  $username Username
     * 
     */
    function column_user_id($item) {
        if (!empty($item['user_id'])) {
            $user_info = get_userdata($item['user_id']);
            $username = $user_info->user_login;
            return $username;
        } else {
            return esc_attr__('anonymous', 'power-forms');
        }
    }

    /**
     *  Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     *  you will need to register it here. This should return an array where the 
     *  key is the column that needs to be sortable, and the value is db column to 
     *  sort by. Often, the key and value will be the same, but this is not always
     *  the case (as the value is a column name from the database, not the list table).
     * 
     *  This method merely defines which columns should be sortable and makes them
     *  clickable - it does not handle the actual sorting. You still need to detect
     *  the ORDERBY and ORDER querystring variables within prepare_items() and sort
     *  your data accordingly (usually by modifying your query).
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  $sortable_columns Sortable Column
     * 
     */
    function get_sortable_columns() {
        $sortable_columns = array(
            'id' => array('id', true), //true means it's already sorted
            'created_at' => array('created_at', false),
            'updated_at' => array('update_at', false)
        );
        return $sortable_columns;
    }

    /**
     *  Optional. If you need to include bulk actions in your list table, this is
     *  the place to define them. Bulk actions are an associative array in the format
     *  'slug'=>'Visible Title'
     * 
     *  If this method returns an empty value, no bulk action will be rendered. If
     *  you specify any bulk actions, the bulk actions box will be rendered with
     *  the table automatically on display().
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  $actions Bulk Action
     * 
     */
    function get_bulk_actions() {
        $new = array();
        $new['bulk-delete'] = esc_attr__('Delete', 'power-forms');
        $actions = $new;
        return $actions;
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

        $deleteRequest = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wpf_gdpr_delete_data WHERE data_id = $id", ARRAY_A);
        if ($deleteRequest) {
            $table = "{$wpdb->prefix}wpf_gdpr_delete_data";
            $wpdb->update(
                    $table, array(
                'status' => 'Deleted',
                    ), array('data_id' => $id), array(
                '%s',
                    ), array('%d')
            );
            $wpdb->delete("{$wpdb->prefix}wpf_entries", [ 'id' => $id], [ '%d']);
            $wpdb->delete("{$wpdb->prefix}wpf_entry_meta", [ 'entry_id' => $id], [ '%d']);
        } else {
            $wpdb->delete("{$wpdb->prefix}wpf_entries", [ 'id' => $id], [ '%d']);
            $wpdb->delete("{$wpdb->prefix}wpf_entry_meta", [ 'entry_id' => $id], [ '%d']);
        }
    }

    /**
     * Get an entry Meta.
     * 
     * @since   1.0
     * 
     * @param   $id ID
     * @return  $results Entry Meta
     * 
     */
    public static function get_entry_meta($id) {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wpf_entry_meta WHERE entry_id = $id", ARRAY_A);
        return $results;
    }

    /**
     * Get an entry Data.
     * 
     * @since   1.0
     * 
     * @param   $id ID
     * @return  $results Entry Data
     * 
     */
    public static function get_entry($id) {
        global $wpdb;
        $result = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wpf_entries WHERE id = $id", ARRAY_A);
        return $result;
    }

    /**
     * Get all Forms.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  $post->posts Forms
     * 
     */
    public static function get_forms() {
        $args = array('post_type' => 'powerform', 'posts_per_page' => -1);
        $post = new WP_Query($args);
        return $post->posts;
    }

    /**
     *  Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     *  For this example package, we will handle it in the class to keep things
     *  clean and organized.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     * 
     */
    function process_bulk_action() {
        if ('delete' === $this->current_action() && isset($_GET['entry'])) {
            self::delete_entry(absint($_GET['entry']));
        }
        if ('view' === $this->current_action() && isset($_GET['entry'])) {
            $records = self::get_entry_meta(absint($_GET['entry']));
            if ($records) {
                $entryrecords = self::get_entry(absint($_GET['entry']));
                require_once plugin_dir_path(dirname(__FILE__)) . 'partials/power-forms-admin-entry-detail.php';
                die();
            }
        }
        if (!empty($_POST['action']) && 'bulk-delete' === $_POST['action']) {
            if (isset($_POST['entry'])) {
                $delete_ids = esc_sql($_POST['entry']);
                foreach ($delete_ids as $id) {
                    self::delete_entry(absint($id));
                }
            }
        }
    }

    /**
     *  REQUIRED! for displaying the view of the table
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     * 
     */
//    protected function get_views() {
//        $status_links = array(
//            "submissions" => __("<a class='current' href=''>Submissions</a>", 'power-forms'),
//            "analysis" => __("<a  href='#'>Analysis</a>", 'power-forms'),
//            "table" => __("<a  href='#'>Table</a>", 'power-forms'),
//            "download" => __("<a  href='#'>Download</a>", 'power-forms'),
//            "clear" => __("<a  href='#'>Clear</a>", 'power-forms')
//        );
//        return $status_links;
//    }

    /**
     *  REQUIRED! This is where you prepare your data for display. This method will
     *  usually be used to query the database, sort and filter the data, and generally
     *  get it ready to be displayed. At a minimum, we should set $this->items and
     *  $this->set_pagination_args(), although the following properties and methods
     *  are frequently interacted with here.
     * 
     * @since   1.0
     * 
     * @param   void
     * @return  void
     * 
     */
    function prepare_items() {
        global $wpdb;
        $per_page = 20;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();

        $data = $this->getEntries();

        function usort_reorder($a, $b) {
            $orderby = (!empty($_REQUEST['orderby'])) ? sanitize_text_field($_REQUEST['orderby']) : 'id';
            $order = (!empty($_REQUEST['order'])) ? sanitize_text_field($_REQUEST['order']) : 'desc';
            $result = strcmp($a[$orderby], $b[$orderby]);
            return ($order === 'asc') ? $result : -$result;
        }

        usort($data, 'usort_reorder');
        $current_page = $this->get_pagenum();
        $total_items = count($data);

        $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);
        $this->items = $data;
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }

}

/**
 * Funtion for adding entries menu under custom post type powerforms
 * 
 * @since   1.0
 * 
 * @param   void
 * @return  void
 * 
 */
function entry_add_menu_items() {
    add_submenu_page('edit.php?post_type=powerform', esc_attr__('Entries', 'power-forms'), esc_attr__('Entries', 'power-forms'), 'manage_options', 'power-form-entries', 'entries_render_list_page');
}

add_action('admin_menu', 'entry_add_menu_items');

/**
 * Funtion for adding entries menu under custom post type powerforms
 * 
 * @since   1.0
 * 
 * @param   void
 * @return  void
 * 
 */
function entries_render_list_page() {
    $entryListTable = new Entries_List_Table();
    $entryListTable->prepare_items();
    ?>
    <div class="pfbcontainer">
        <header class="codrops-header">
            <h1>PowerFormBuilder Entries <span>WordPress Contact Form Plugin PowerFormBuilder is the ultimate FREE and intuitive FORM creation tool for WordPress</span></h1>
            <p class="support">Your browser does not support <strong>flexbox</strong>! <br />Please view this demo with a <strong>modern browser</strong>.</p>
        </header>
        <section>
            <div class="tabs tabs-style-linebox">
                <nav id="stickynavbar">
                    <ul>
                        <li class="tab-current"><a href="#section-underline-1" class=""><span>Entries</span></a></li>
                        <li class=""><a onclick="location.href = '<?php echo site_url(); ?>/wp-admin/edit.php?post_type=powerform&page=power-form-gdpr-requests';" href="<?php echo site_url(); ?>/wp-admin/edit.php?post_type=powerform&page=power-form-gdpr-requests" class=""><span>Data Request</span></a></li>
                        <li class=""><a onclick="location.href = '<?php echo site_url(); ?>/wp-admin/edit.php?post_type=powerform&page=power-form-gdpr-delete';" href="<?php echo site_url(); ?>/wp-admin/edit.php?post_type=powerform&page=power-form-gdpr-delete" class=""><span>Delete Data Request</span></a></li>
                        <li class=""><a onclick="location.href = '<?php echo site_url(); ?>/wp-admin/edit.php?post_type=powerform&page=power-form-settings';" href="<?php echo site_url(); ?>/wp-admin/edit.php?post_type=powerform&page=power-form-gdpr-settings" class=""><span>Settings</span></a></li>
                    </ul>
                </nav>
                <div class="content-wrap" style="width: 100%">

                    <section id="section-underline-1" class="content-current">
                        <form id="gdpr_deletes-filter" method="post">
                            <?php $entryListTable->display() ?>
                        </form>
                    </section>
                </div><!-- /content -->
            </div><!-- /tabs -->
        </section>
    </div>
    <?php
}
