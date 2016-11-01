<?php
/*
Plugin Name: iTunes Link Engine
Plugin URI:
Description: Automatically optimizes iTunes product links for your global audience and allows you to earn commissions on sales.
Version: 1.2.4
Author: GeoRiot Networks, Inc.
Author URI: http://geni.us
*/
//Change this if you need to run a migration (eg change setting names, dbm etc). See genius_ile_update_db_check()
global $genius_ile_db_version;
$genius_ile_db_version = '1.1';


if (!defined('WP_CONTENT_URL'))
      define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
      define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if (!defined('WP_PLUGIN_URL'))
      define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if (!defined('WP_PLUGIN_DIR'))
      define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');


// OPTIONS

function activate_genius_ile() {
  global $genius_ile_db_version;

  add_option('genius_ile_domain', '');
  add_option('genius_ile_tsid', '');
  add_option('genius_ile_api_key', '');
  add_option('genius_ile_api_secret', '');
  add_option('genius_ile_api_remind', 'yes');
  add_option('genius_ile_db_version', $genius_ile_db_version);
}

function deactivate_genius_ile() {
  delete_option('genius_ile_domain');
  delete_option('genius_ile_tsid');
  delete_option('genius_ile_api_key');
  delete_option('genius_ile_api_secret');
  delete_option('genius_ile_api_remind');
  delete_option('genius_ile_db_version');
}

function admin_init_genius_ile() {
  register_setting('itunes-link-engine', 'genius_ile_domain');
  register_setting('itunes-link-engine', 'genius_ile_tsid');
  register_setting('itunes-link-engine', 'genius_ile_api_key');
  register_setting('itunes-link-engine', 'genius_ile_api_secret');
  register_setting('itunes-link-engine', 'genius_ile_api_remind');
  register_setting('itunes-link-engine', 'genius_ile_db_version');
}


//Backwards compatibility: Migrate old vals to new ones
function genius_ile_migrate_1() {
  global $genius_ile_db_version;

  update_option('genius_ile_tsid', get_option('georiot_tsid'));
  update_option('genius_ile_api_key', get_option('georiot_api_key'));
  update_option('genius_ile_api_secret', get_option('georiot_api_secret'));
  update_option('genius_ile_api_remind', get_option('georiot_api_remind'));
  update_option('genius_ile_db_version', $genius_ile_db_version);

  //Delete the obsolete values, only if the old Amazon plugin isn't installed
  if( !function_exists( 'georiot_autolinker' ) ) {
    delete_option('georiot_tsid');
    delete_option('georiot_api_key');
    delete_option('georiot_api_secret');
    delete_option('georiot_api_remind');
    delete_option('georiot_preserve_tracking');
  }
}


function admin_menu_genius_ile() {
  add_options_page('iTunes Link Engine', 'iTunes Link Engine', 'manage_options', 'itunes-link-engine', 'options_page_genius_ile');
}

function options_page_genius_ile() {
  include(WP_PLUGIN_DIR.'/itunes-link-engine/options.php');
}



// Show notice in dashboard home page and plugin page if API isn't connected
function genius_ile_admin_notice(){
  if (strpos($_SERVER['PHP_SELF'],'wp-admin/index.php') !== false  || strpos($_SERVER['PHP_SELF'],'wp-admin/plugins.php') !== false ) {
    if (get_option('genius_ile_api_remind') == 'yes' && get_option('genius_ile_tsid') == '') {
      ?>
      <div class="update-nag">
        <p><?php _e('<strong>iTunes Link Engine is installed. Want to earn commissions and use reporting? </strong>
        <br> Please <a href="'.admin_url().'options-general.php?page=itunes-link-engine">enter your GeniusLink API values</a>. Or, you can <a href="'.admin_url().'options-general.php?page=itunes-link-engine">disable this reminder</a>'); ?>.</p>
      </div>
    <?php
    }
  }
}

// BEGIN FUNCTION TO SHOW GENIUSLINK JS

function genius_ile()
{
  if (get_option('genius_ile_tsid') == '') {
    $gr_use_tsid = 6218;
  } else {
    $gr_use_tsid = get_option('genius_ile_tsid');
  }

  if (get_option('genius_ile_domain') != 'geni.us' && get_option('genius_ile_domain') != '') {
    $gr_use_domain = ", 'http://" . get_option("genius_ile_domain") . "'";
  } else {
    $gr_use_domain = '';
  }
?>


  <script src="//cdn.georiot.com/snippet.js" defer></script>
  <script type="text/javascript">
    jQuery(document).ready(function( $ ) {
      Georiot.itunes.convertToGeoRiotLinks(<?php echo $gr_use_tsid . ', false' .  $gr_use_domain ?>);
    });
  </script>
<?php
}
// END FUNCTION TO SHOW GENIUS JS

register_activation_hook(__FILE__, 'activate_genius_ile');
register_deactivation_hook(__FILE__, 'deactivate_genius_ile');

if (is_admin()) {
  add_action('admin_init', 'admin_init_genius_ile');
  add_action('admin_menu', 'admin_menu_genius_ile');
  add_action('admin_notices', 'genius_ile_admin_notice');
}

if (!is_admin()) {
  add_action('wp_head', 'genius_ile');
}


//Update the plugin if needed
function genius_ile_update_db_check() {
  global $genius_ile_db_version;
  $current_ile_db_version = get_option('genius_ile_db_version');

  if ( $current_ile_db_version != $genius_ile_db_version ) {

    //Check if they are on the oldest version of the genius plugin db
    if( !$current_ile_db_version ) {
      genius_ile_migrate_1();
    }
  }
}

add_action( 'plugins_loaded', 'genius_ile_update_db_check' );


// SHOW SETTINGS OPTION IN THE PLUGIN PAGE
// Settings link
function geniusILEAddSettingsLink($actions) {
  $actions = array('settings' => sprintf('<a href="%s" title="%s">%s</a>', admin_url().'options-general.php?page=itunes-link-engine', __('Configure this plugin'), __('Settings'))) + $actions;
  return $actions;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'geniusILEAddSettingsLink');


?>