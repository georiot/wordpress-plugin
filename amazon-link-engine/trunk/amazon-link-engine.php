<?php
/*
Plugin Name: Amazon Link Engine
Plugin URI:
Description: Automatically optimizes Amazon product links for your global audience and allows you to earn commissions on sales.
Version: 1.2.1
Author: GeoRiot Networks, Inc.
Author URI: http://geni.us
*/

//Change this if you need to run a migration (eg change setting names, dbm etc). See genius_update_db_check()
global $genius_ale_db_version;
$genius_ale_db_version = 1.1;

if (!defined('WP_CONTENT_URL'))
      define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
      define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if (!defined('WP_PLUGIN_URL'))
      define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if (!defined('WP_PLUGIN_DIR'))
      define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');


// OPTIONS
// Will set to old values if the new genius ones don't already exist in the db, and old georiot ones are available
function activate_genius_autolinker() {
  add_option('genius_ale_domain', '');
  add_option('genius_ale_tsid', '');
  add_option('genius_ale_api_key', '');
  add_option('genius_ale_api_secret', '');
  add_option('genius_ale_api_remind', 'yes');
  add_option('genius_ale_preserve_tracking', 'no');
  add_option('genius_ale_db_version', $genius_ale_db_version);
}

function deactivate_genius_autolinker() {
  delete_option('genius_ale_domain');
  delete_option('genius_ale_tsid');
  delete_option('genius_ale_api_key');
  delete_option('genius_ale_api_secret');
  delete_option('genius_ale_api_remind');
  delete_option('genius_ale_preserve_tracking');
  delete_option('genius_ale_db_version');
}

function admin_init_genius_autolinker() {
  register_setting('amazon-link-engine', 'genius_ale_domain');
  register_setting('amazon-link-engine', 'genius_ale_tsid');
  register_setting('amazon-link-engine', 'genius_ale_api_key');
  register_setting('amazon-link-engine', 'genius_ale_api_secret');
  register_setting('amazon-link-engine', 'genius_ale_api_remind');
  register_setting('amazon-link-engine', 'genius_ale_preserve_tracking');
  register_setting('amazon-link-engine', 'genius_ale_db_version');
}


//Backwards compatibility: Migrate old vals to new ones
function genius_migrate_1() {
  update_option('genius_ale_tsid', get_option('georiot_tsid'));
  update_option('genius_ale_api_key', get_option('georiot_api_key'));
  update_option('genius_ale_api_secret', get_option('georiot_api_secret'));
  update_option('genius_ale_api_remind', get_option('georiot_api_remind'));
  update_option('genius_ale_preserve_tracking', get_option('georiot_preserve_tracking'));

  //Delete the obsolete values
  delete_option('georiot_tsid');
  delete_option('georiot_api_key');
  delete_option('georiot_api_secret');
  delete_option('georiot_api_remind');
  delete_option('georiot_preserve_tracking');
}


function admin_menu_genius_autolinker() {
  add_options_page('Amazon Link Engine', 'Amazon Link Engine', 'manage_options', 'amazon-link-engine', 'options_page_genius_autolinker');
}

function options_page_genius_autolinker() {
  include(WP_PLUGIN_DIR.'/amazon-link-engine/options.php');
}



// Show notice in dashboard home page and plugin page if API isn't connected
function genius_admin_notice(){
  if (strpos($_SERVER['PHP_SELF'],'wp-admin/index.php') !== false  || strpos($_SERVER['PHP_SELF'],'wp-admin/plugins.php') !== false ) {
    if (get_option('genius_ale_api_remind') == 'yes' && get_option('genius_ale_tsid') == '') {
      ?>
      <div class="update-nag">
        <p><?php _e('<strong>Your Amazon Link Engine plugin is installed and working.</strong> <br>To use reporting and commissions, <a href="'.admin_url().'options-general.php?page=amazon-link-engine">enter your GeniusLink API values.</a>. Or, you can <a href="'.admin_url().'options-general.php?page=amazon-link-engine">disable this reminder.</a>'); ?></p>
      </div>
    <?php
    }
  }
}

// BEGIN FUNCTION TO SHOW GENIUS JS

function genius_autolinker() {

  if (get_option('genius_ale_tsid') == '') {
    $gr_use_tsid = 4632;
  } else {
    $gr_use_tsid = get_option('genius_ale_tsid');
  }

  if (get_option('genius_ale_domain') != 'geni.us' && get_option('genius_ale_domain') != '') {
    $gr_use_domain = ", 'http://" . get_option("genius_ale_domain")."'" ;
  } else {
    $gr_use_domain = '';
  }

  $preserve_tracking = 'false';

  if (get_option('genius_ale_preserve_tracking') == 'yes') {
    $preserve_tracking = 'true';
  }

?>

  <script src="//cdn.georiot.com/snippet.js"></script>
  <script type="text/javascript">
    jQuery(document).ready(function( $ ) {
      Georiot.amazon.convertToGeoRiotLinks(<?php echo $gr_use_tsid ?>, <?php print($preserve_tracking)?><?php print($gr_use_domain) ?>);
    });
  </script>
<?php
}
// END FUNCTION TO SHOW GENIUS JS

register_activation_hook(__FILE__, 'activate_genius_autolinker');
register_deactivation_hook(__FILE__, 'deactivate_genius_autolinker');

if (is_admin()) {
  add_action('admin_init', 'admin_init_genius_autolinker');
  add_action('admin_menu', 'admin_menu_genius_autolinker');
  add_action('admin_notices', 'genius_admin_notice');
}

if (!is_admin()) {
  add_action('wp_head', 'genius_autolinker');
}


//Update the plugin if needed
function genius_update_db_check() {
  global $genius_ale_db_version;
  if ( get_site_option( 'genius_ale_db_version' ) != $genius_ale_db_version ) {
    //Check if they are on the oldest version of the genius plugin db
    if( !get_site_option( 'genius_ale_db_version' )) {
      genius_migrate_1();
    }
  }
}
add_action( 'plugins_loaded', 'genius_update_db_check' );


// SHOW SETTINGS OPTION IN THE PLUGIN PAGE
// Settings link
function genius_add_settings_link($actions) {
  $actions = array('settings' => sprintf('<a href="%s" title="%s">%s</a>', admin_url().'options-general.php?page=amazon-link-engine', __('Configure this plugin'), __('Settings'))) + $actions;
  return $actions;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'genius_add_settings_link');


?>