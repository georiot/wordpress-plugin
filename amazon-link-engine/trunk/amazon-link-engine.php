<?php
/*
Plugin Name: Amazon Link Engine
Plugin URI:
Description: Automatically optimizes Amazon product links for your global audience and allows you to earn commissions on sales.
Version: 1.2.5
Author: GeoRiot Networks, Inc.
Author URI: http://geni.us
*/

//Change this if you need to run a migration (eg change setting names, dbm etc). See genius_ale_update_db_check()
global $genius_ale_db_version;
$genius_ale_db_version = '1.1';

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
  global $genius_ale_db_version;

  add_option('genius_ale_domain', '');
  add_option('genius_ale_tsid', '');
  add_option('genius_ale_api_key', '');
  add_option('genius_ale_api_secret', '');
  add_option('genius_ale_api_remind', 'yes');
  add_option('genius_ale_preserve_tracking', 'yes');
  add_option('genius_ale_db_version', $genius_ale_db_version);
  add_option('genius_ale_liking', '');
  add_option('genius_ale_dismiss_feedback', '');
}

function deactivate_genius_autolinker() {
  delete_option('genius_ale_domain');
  delete_option('genius_ale_tsid');
  delete_option('genius_ale_api_key');
  delete_option('genius_ale_api_secret');
  delete_option('genius_ale_api_remind');
  delete_option('genius_ale_preserve_tracking');
  delete_option('genius_ale_db_version');
  delete_option('genius_ale_liking');
  delete_option('genius_ale_dismiss_feedback');
}

function admin_init_genius_autolinker() {
  register_setting('amazon-link-engine', 'genius_ale_domain');
  register_setting('amazon-link-engine', 'genius_ale_tsid');
  register_setting('amazon-link-engine', 'genius_ale_api_key');
  register_setting('amazon-link-engine', 'genius_ale_api_secret');
  register_setting('amazon-link-engine', 'genius_ale_api_remind');
  register_setting('amazon-link-engine', 'genius_ale_preserve_tracking');
  register_setting('amazon-link-engine', 'genius_ale_db_version');
  register_setting('amazon-link-engine', 'genius_ale_liking');
  register_setting('amazon-link-engine', 'genius_ale_dismiss_feedback');
}


//Backwards compatibility: Migrate old vals to new ones
function genius_ale_migrate_1() {
  global $genius_ale_db_version;

  update_option('genius_ale_tsid', get_option('georiot_tsid'));
  update_option('genius_ale_api_key', get_option('georiot_api_key'));
  update_option('genius_ale_api_secret', get_option('georiot_api_secret'));
  update_option('genius_ale_api_remind', get_option('georiot_api_remind'));
  update_option('genius_ale_preserve_tracking', get_option('georiot_preserve_tracking'));
  update_option('genius_ale_db_version', $genius_ale_db_version);

  //Delete the obsolete values, only if the old iTunes plugin isn't installed
  if( !function_exists( 'georiot_ile' ) ) {
    delete_option('georiot_tsid');
    delete_option('georiot_api_key');
    delete_option('georiot_api_secret');
    delete_option('georiot_api_remind');
    delete_option('georiot_preserve_tracking');
  }
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
        <p><?php _e('<strong>Amazon Link Engine is installed. Want to earn commissions and use reporting? </strong>
        <br> Please <a href="'.admin_url().'options-general.php?page=amazon-link-engine">enter your GeniusLink API values</a>. Or, you can <a href="'.admin_url().'options-general.php?page=amazon-link-engine">disable this reminder</a>'); ?>.</p>
      </div>
    <?php
    }
    else if (get_option("genius_ale_dismiss_feedback") !== 'yes') { //
    ?>
      <script>
        jQuery(document).ready(function($) {

          $( "#ale-feedback-like").click(function() {
            console.log('asdasd');
            $("#genius_ale_liking").val('yes');
            $( "#ale-feedback-form" ).submit();
          });
          $("#ale-feedback-dislike").click(function() {
            $("#genius_ale_liking").val('no');
            $("#ale-feedback-form").submit();
          });
          $("#ale-feedback-dismiss").click(function() {
            $("#genius_ale_dismiss_feedback").val('yes');
            $("#ale-feedback-form").submit();
          });

        });
      </script>


      <div class="update-nag">
        <?php
        if (get_option("genius_ale_liking") == 'yes') {
          ?>
          Great to hear! Would you mind leaving a rating so other users can learn about this plugin?
          <button type="button" id="ale-feedback-dismiss">Dismiss</button>
          <?php
        } else if (get_option("genius_ale_liking") == 'no'){
          ?>
          Sorry to hear that! We would love to hear form you and learn how we can do better.
          <button type="button" id="ale-feedback-dismiss">Dismiss</button>
          <?php
        } else {
        ?>
          <p>
            <strong>Thanks for using the Amazon Link Engine plugin. Are you happy with it?</strong>
            <br>
          </p>
          <?php
        } //End if they have neither liked nor disliked
        ?>

        <form id="ale-feedback-form" method="post" action="options.php">
          <input type="hidden" name="option_page" value="amazon-link-engine"/>
          <input type="hidden" name="action" value="update"/>
          <input type="hidden" id="_wpnonce" name="_wpnonce" value="5c77377e61"/>
          <input type="hidden" name="_wp_http_referer" value="/wp-admin/"/>

          <?php // Kludge? Send all existing values, otherwise they revert to defaults ?>
          <span style="display: none">
          <input maxlength="34" size="34" type="text" placeholder="Paste your api key" id="genius_ale_api_key"
                 name="genius_ale_api_key" value="<?php echo get_option('genius_ale_api_key'); ?>"/></td>
            <input maxlength="34" size="34" type="text" placeholder="Paste your api secret" id="genius_ale_api_secret"
                   name="genius_ale_api_secret" value="<?php echo get_option('genius_ale_api_secret'); ?>"/>
          <input type="checkbox" name="genius_ale_preserve_tracking"
                 value="yes" <?php if (get_option('genius_ale_preserve_tracking') == 'yes') print "checked" ?> />
          <input type="checkbox" name="genius_ale_api_remind"
                 value="yes" <?php if (get_option('genius_ale_api_remind') == 'yes') print "checked" ?> />
          </span>
          <input size="10" type="hidden" name="genius_ale_tsid" id="genius_ale_tsid"
                 value="<?php echo get_option("genius_ale_tsid"); ?>"/>
          <input size="100" type="hidden" name="genius_ale_domain" id="genius_ale_domain"
                 value="<?php echo get_option("genius_ale_domain"); ?>"/>
          <input size="10" type="hidden" name="genius_ale_db_version" id="genius_ale_db_version"
                 value="<?php echo get_option("genius_ale_db_version"); ?>"/>
          <?php // End Kludge ?>

          <!-- Feedback values-->
          <input size="10" type="" name="genius_ale_liking" id="genius_ale_liking"
                 value="<?php echo get_option("genius_ale_liking"); ?>"/>
          <input size="10" type="" name="genius_ale_dismiss_feedback" id="genius_ale_dismiss_feedback"
                 value="<?php echo get_option("genius_ale_dismiss_feedback"); ?>"/>

          <button type="button" id="ale-feedback-like">Good</button>
          <button type="button" id="ale-feedback-dislike">Bad</button>
          <button type="button" id="ale-feedback-dismiss">Dismiss</button>
          <button>.</button>
        </form>


      </div>

      <?php
    } //End if they haven't dismissed the feedback prompt
  }
}

// BEGIN FUNCTION TO SHOW GENIUS JS

function genius_ale() {

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

  <script src="//cdn.georiot.com/snippet.js" defer></script>
  <script type="text/javascript">
    jQuery(document).ready(function( $ ) {
      if (typeof Georiot !== 'undefined') {
        Georiot.amazon.convertToGeoRiotLinks(<?php echo $gr_use_tsid ?>, <?php print($preserve_tracking)?><?php print($gr_use_domain) ?>);
      };
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
  add_action('wp_head', 'genius_ale', 9999);
}


//Update the plugin if needed
function genius_ale_update_db_check() {
  global $genius_ale_db_version;
  $current_ale_db_version = get_option('genius_ale_db_version');

  if ( $current_ale_db_version != $genius_ale_db_version ) {

    //Check if they are on the oldest version of the genius plugin db
    if( !$current_ale_db_version ) {
      genius_ale_migrate_1();
    }
  }
}


add_action( 'plugins_loaded', 'genius_ale_update_db_check' );


// SHOW SETTINGS OPTION IN THE PLUGIN PAGE
// Settings link
function genius_add_settings_link($actions) {
  $actions = array('settings' => sprintf('<a href="%s" title="%s">%s</a>', admin_url().'options-general.php?page=amazon-link-engine', __('Configure this plugin'), __('Settings'))) + $actions;
  return $actions;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'genius_add_settings_link');


?>
