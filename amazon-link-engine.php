<?php
/*
Plugin Name: Amazon Link Engine
Plugin URI:
Description: Automatically optimize Amazon product links for your global audience and earn commissions on sales.
Version: 1.0.0
Author: Steven Sundheim
Author URI: http://sundhe.im
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

function activate_georiot_autolinker() {
  add_option('georiot_tsid', '');
  add_option('georiot_api_key', '');
  add_option('georiot_api_secret', '');
  add_option('georiot_api_remind', 'yes');
}

function deactive_georiot_autolinker() {
  delete_option('georiot_tsid');
  delete_option('georiot_api_key');
  delete_option('georiot_api_secret');
  delete_option('georiot_api_remind');
}

function admin_init_georiot_autolinker() {
  register_setting('amazon-link-engine', 'georiot_tsid');
  register_setting('amazon-link-engine', 'georiot_api_key');
  register_setting('amazon-link-engine', 'georiot_api_secret');
  register_setting('amazon-link-engine', 'georiot_api_remind');
}


function admin_menu_georiot_autolinker() {
  add_options_page('Amazon Link Engine', 'Amazon Link Engine', 'manage_options', 'amazon-link-engine', 'options_page_georiot_autolinker');
}

function options_page_georiot_autolinker() {
  include(WP_PLUGIN_DIR.'/amazon-link-engine/options.php');
}


// Show notice in dashboard if API isn't connected
function georiot_admin_notice()
{
  if ($_SERVER['PHP_SELF'] == '/wp-admin/index.php') {
    if (get_option('georiot_api_remind') == 'yes' && get_option('georiot_tsid') == '') {
      ?>
      <div class="update-nag">
        <p><?php _e('<strong>Your Amazon Link Engine plugin is installed and working.</strong> <br>To use reporting and commissions, <a href="/wp-admin/options-general.php?page=amazon-link-engine">enter your free GeoRiot API values.</a>. Or, you can <a href="/wp-admin/options-general.php?page=amazon-link-engine">disable this reminder.</a>'); ?></p>
      </div>
    <?php
    }
  }
}

// BEGIN FUNCTION TO SHOW GEORIOT JS

function georiot_autolinker() {
  $georiot_tsid = get_option('georiot_tsid');
?>

  <script src="//cdn.georiot.com/snippet.js"></script>

  <!--workaround-->
  <script>
    function convertToGeoRiotLinks(tsid) {
      var numberOfLinks = document.links.length;
      var currentLinkIndex = 0;

      for (currentLinkIndex = 0; currentLinkIndex < numberOfLinks; currentLinkIndex++) {
        var currentLink = document.links[currentLinkIndex];
        var linkType = getLinkType(currentLink.href);

        if (linkType == "amazon") {
          currentLink.href = "http://target.georiot.com/Proxy.ashx?TSID=" + tsid + "&GR_URL=" + encodeURIComponent(currentLink.href);
        }else continue;
      }
    }

    function extractItunesLinkFromAffiliateUrl(currentLink, linkType) {
      if (currentLink.href.indexOf("?") > 0) {
        var arrParams = currentLink.href.split("?");
        var arrURLParams = arrParams[1].split("&");
        var arrParamNames = new Array(arrURLParams.length);
        var arrParamValues = new Array(arrURLParams.length);
        var i = 0;
        for (i = 0; i < arrURLParams.length; i++) {
          var sParam = arrURLParams[i].split("=");
          arrParamNames[i] = sParam[0];
          if (sParam[1] != "") {
            arrParamValues[i] = sParam[1];
          } else arrParamValues[i] = "";
        }
      }
      return "";
    }

    /* Returns link type: unknown, amazon
     */
    function getLinkType(currentLinkHref) {
      var amazonRegex = /\.amazon\./;
      var amazonLocalRegex = /local\.amazon\./;

      if (currentLinkHref.indexOf("target.georiot.com") > 0 || currentLinkHref.indexOf("geni.us") > 0) {
        return "unknown";
      }


      if (amazonRegex.test(currentLinkHref) && !amazonLocalRegex.test(currentLinkHref)) return "amazon";
      else return "unknown";
    }
  </script>
  <!-- End workaround -->

  <script type="text/javascript">


  jQuery(document).ready(function( $ ) {
    var tsid = <?php echo $georiot_tsid ?>;
    convertToGeoRiotLinks(tsid);
  });

</script>
<?php
}
// END FUNCTION TO SHOW GEORIOT JS

register_activation_hook(__FILE__, 'activate_georiot_autolinker');
register_deactivation_hook(__FILE__, 'deactivate_georiot_autolinker');

if (is_admin()) {
  add_action('admin_init', 'admin_init_georiot_autolinker');
  add_action('admin_menu', 'admin_menu_georiot_autolinker');
  add_action('admin_notices', 'georiot_admin_notice');
}

if (!is_admin()) {
  add_action('wp_head', 'georiot_autolinker');
}


// SHOW SETTINGS OPTION IN THE PLUGIN PAGE
// Settings link
function georiot_add_settings_link($actions) {
  $actions = array('settings' => sprintf('<a href="%s" title="%s">%s</a>', '/wp-admin/options-general.php?page=amazon-link-engine', __('Configure GeoRiot Plugin.'), __('Settings'))) + $actions;
  return $actions;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'georiot_add_settings_link');


?>
