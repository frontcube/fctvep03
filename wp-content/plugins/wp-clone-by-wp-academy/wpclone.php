<?php
/*
Plugin name: WP Clone by WP Academy
Plugin URI: http://wpacademy.com/software/
Description: Move or copy a WordPress site to another server or to another domain name, move to/from local server hosting, and backup sites.
Author: WP Academy
Version: 2.1.8
Author URI: http://wpacademy.com/
*/

include_once 'lib/functions.php';

$upload_dir = wp_upload_dir();

define('WPBACKUP_FILE_PERMISSION', 0755);
define('WPCLONE_ROOT',  rtrim(str_replace("\\", "/", ABSPATH), "/\\") . '/');
define('WPCLONE_BACKUP_FOLDER',  'wp-clone');
define('WPCLONE_DIR_UPLOADS',  str_replace('\\', '/', $upload_dir['basedir']));
define('WPCLONE_DIR_PLUGIN', str_replace('\\', '/', plugin_dir_path(__FILE__)));
define('WPCLONE_URL_PLUGIN', plugin_dir_url(__FILE__));
define('WPCLONE_DIR_BACKUP',  WPCLONE_DIR_UPLOADS . '/' . WPCLONE_BACKUP_FOLDER . '/');
define('WPCLONE_INSTALLER_PATH', WPCLONE_DIR_PLUGIN);
define('WPCLONE_WP_CONTENT' , str_replace('\\', '/', WP_CONTENT_DIR));


/* Init options & tables during activation & deregister init option */

register_activation_hook((__FILE__), 'wpa_wpclone_activate');
register_deactivation_hook(__FILE__ , 'wpa_wpclone_deactivate');
add_action('admin_menu', 'wpclone_plugin_menu');

function wpclone_plugin_menu() {
    add_menu_page (
        'WP Clone Plugin Options',
        'WP Clone',
        'manage_options',
        'wp-clone',
        'wpclone_plugin_options'
    );
}

function wpclone_plugin_options() {
    include_once 'lib/view.php';
}

function wpa_enqueue_scripts(){
    wp_register_script('jquery-zclip', plugin_dir_url(__FILE__) . '/lib/js/zeroclipboard.min.js', array('jquery'));
    wp_register_script('wpclone', plugin_dir_url(__FILE__) . '/lib/js/backupmanager.js', array('jquery'));
    wp_register_style('wpclone', plugin_dir_url(__FILE__) . '/lib/css/style.css');
    wp_enqueue_script('jquery-zclip');
    wp_enqueue_script('wpclone');
    wp_enqueue_style('wpclone');
}
if( isset($_GET['page']) && 'wp-clone' == $_GET['page'] ) add_action('admin_enqueue_scripts', 'wpa_enqueue_scripts');

function wpa_wpclone_activate() {
    wpa_create_directory();
    wpa_install_database();
}

function wpa_wpclone_deactivate() {
    //removing the table
    global $wpdb;
    $wp_backup = $wpdb->prefix . 'wpclone';
    $wpdb->query ("DROP TABLE IF EXISTS $wp_backup");
    $data = "<Files>\r\n\tOrder allow,deny\r\n\tDeny from all\r\n\tSatisfy all\r\n</Files>";
    $file = WPCLONE_DIR_BACKUP . '.htaccess';
    file_put_contents($file, $data);
}


function wpa_create_directory() {
    $indexFile = (WPCLONE_DIR_BACKUP.'index.html');
    $htacc = WPCLONE_DIR_BACKUP . '.htaccess';
    $htacc_data = "Options All -Indexes";
    if (!file_exists($indexFile)) {
        if(!file_exists(WPCLONE_DIR_BACKUP)) {
            if(!mkdir(WPCLONE_DIR_BACKUP, WPBACKUP_FILE_PERMISSION)) {
                die("Unable to create directory '" . rtrim(WPCLONE_DIR_BACKUP, "/\\"). "'. Please set 0755 permission to wp-content.");
            }
        }
        $handle = fopen($indexFile, "w");
        fclose($handle);
    }
    file_put_contents($htacc, $htacc_data);
}

function wpa_install_database() {
    global $wpdb , $wp_roles, $wp_version;
    require_once(WPCLONE_ROOT . 'wp-admin/upgrade-functions.php');
    // add charset & collate like wp core
    $charset_collate = '';
    if ( version_compare(mysql_get_server_info(), '4.1.0', '>=') ) {
        if ( ! empty($wpdb->charset) )
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if ( ! empty($wpdb->collate) )
            $charset_collate .= " COLLATE $wpdb->collate";
    }
    $wp_backup = $wpdb->prefix . 'wpclone';
    /* could be case senstive : http://dev.mysql.com/doc/refman/5.1/en/identifier-case-sensitivity.html */
    if( !$wpdb->get_var( "SHOW TABLES LIKE '{$wp_backup}'" ) ) {
        $sql = "CREATE TABLE {$wp_backup} (
                id BIGINT(20)  UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                backup_name 	VARCHAR(250) NOT NULL,
                backup_size     INT (11),
                data_time 	    DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                creator 		VARCHAR(60) NOT NULL) $charset_collate;";
        dbDelta($sql);
    }
}

function wpa_wpc_msnotice() {
    echo '<div class="error">';
    echo '<h4>WP Clone Notice.</h4>';
    echo '<p>WP Clone is not compatible with multisite installations.</p></div>';
}

if ( is_multisite() )
    add_action( 'admin_notices', 'wpa_wpc_msnotice');