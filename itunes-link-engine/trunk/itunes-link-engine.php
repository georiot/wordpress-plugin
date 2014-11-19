<?php
/*
Plugin Name: iTunes Link Engine
Plugin URI:
Description: Automatically optimizes iTunes product links for your global audience and allows you to earn commissions on sales.
Version: 1.0.0
Author: GeoRiot Networks, Inc.
Author URI: http://georiot.com
*/

if (!defined('WP_CONTENT_URL'))
      define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
if (!defined('WP_CONTENT_DIR'))
      define('WP_CONTENT_DIR', ABSPATH.'wp-content');
if (!defined('WP_PLUGIN_URL'))
      define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
if (!defined('WP_PLUGIN_DIR'))
      define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');


// OPTIONS

function activate_georiot_ile() {
  add_option('georiot_tsid', '');
  add_option('georiot_api_key', '');
  add_option('georiot_api_secret', '');
  add_option('georiot_api_remind', 'yes');
}

function deactivate_georiot_ile() {
  delete_option('georiot_tsid');
  delete_option('georiot_api_key');
  delete_option('georiot_api_secret');
  delete_option('georiot_api_remind');
}

function admin_init_georiot_ile() {
  register_setting('itunes-link-engine', 'georiot_tsid');
  register_setting('itunes-link-engine', 'georiot_api_key');
  register_setting('itunes-link-engine', 'georiot_api_secret');
  register_setting('itunes-link-engine', 'georiot_api_remind');
}


function admin_menu_georiot_ile() {
  add_options_page('iTunes Link Engine', 'iTunes Link Engine', 'manage_options', 'itunes-link-engine', 'options_page_georiot_ile');
}

function options_page_georiot_ile() {
  include(WP_PLUGIN_DIR.'/itunes-link-engine/options.php');
}



// Show notice in dashboard home page and plugin page if API isn't connected
function georiot_ile_admin_notice(){
  if (strpos($_SERVER['PHP_SELF'],'wp-admin/index.php') !== false  || strpos($_SERVER['PHP_SELF'],'wp-admin/plugins.php') !== false ) {
    if (get_option('georiot_api_remind') == 'yes' && get_option('georiot_tsid') == '') {
      ?>
      <div class="update-nag">
        <p><?php _e('<strong>Your iTunes Link Engine plugin is installed and working.</strong> <br>To use reporting and commissions, <a href="'.admin_url().'options-general.php?page=itunes-link-engine">enter your free GeoRiot API values.</a>. Or, you can <a href="'.admin_url().'options-general.php?page=itunes-link-engine">disable this reminder.</a>'); ?></p>
      </div>
    <?php
    }
  }
}

// BEGIN FUNCTION TO SHOW GEORIOT JS

function georiot_ile() {
  if (get_option('georiot_tsid') == '') {
    $gr_use_tsid = 4632;
  } else {
    $gr_use_tsid = get_option('georiot_tsid');
  }
?>

  <script src="//cdn.georiot.com/snippet.js"></script>
  <script type="text/javascript">
    jQuery(document).ready(function( $ ) {
      var tsid = <?php echo $gr_use_tsid ?>;
      convertToGeoRiotLinks(tsid);
    });
  </script>
<?php
}
// END FUNCTION TO SHOW GEORIOT JS

register_activation_hook(__FILE__, 'activate_georiot_ile');
register_deactivation_hook(__FILE__, 'deactivate_georiot_ile');

if (is_admin()) {
  add_action('admin_init', 'admin_init_georiot_ile');
  add_action('admin_menu', 'admin_menu_georiot_ile');
  add_action('admin_notices', 'georiot_ile_admin_notice');
}

if (!is_admin()) {
  add_action('wp_head', 'georiot_ile');
}


// SHOW SETTINGS OPTION IN THE PLUGIN PAGE
// Settings link
function georiot_ile_add_settings_link($actions) {
  $actions = array('settings' => sprintf('<a href="%s" title="%s">%s</a>', admin_url().'options-general.php?page=itunes-link-engine', __('Configure GeoRiot Plugin.'), __('Settings'))) + $actions;
  return $actions;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'georiot_ile_add_settings_link');


?>