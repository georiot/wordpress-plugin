<?php
/*
Plugin Name: iTunes Link Engine
Plugin URI:
Description: Automatically optimizes iTunes product links for your global audience and allows you to earn commissions on siles.
Version: 1.2.9
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
  add_option('genius_ile_liking', '');
  add_option('genius_ile_dismiss_feedback', '');
  add_option('genius_ile_install_date', time());
  add_option('genius_ile_urls_on_click', 'no');
}

function deactivate_genius_ile() {
  delete_option('genius_ile_domain');
  delete_option('genius_ile_tsid');
  delete_option('genius_ile_api_key');
  delete_option('genius_ile_api_secret');
  delete_option('genius_ile_api_remind');
  delete_option('genius_ile_db_version');
  delete_option('genius_ile_liking');
  delete_option('genius_ile_dismiss_feedback');
  delete_option('genius_ile_urls_on_click');
}

function admin_init_genius_ile() {
  register_setting('itunes-link-engine', 'genius_ile_domain');
  register_setting('itunes-link-engine', 'genius_ile_tsid');
  register_setting('itunes-link-engine', 'genius_ile_api_key');
  register_setting('itunes-link-engine', 'genius_ile_api_secret');
  register_setting('itunes-link-engine', 'genius_ile_api_remind');
  register_setting('itunes-link-engine', 'genius_ile_db_version');
  register_setting('itunes-link-engine', 'genius_ile_liking');
  register_setting('itunes-link-engine', 'genius_ile_dismiss_feedback');
  register_setting('itunes-link-engine', 'genius_ile_urls_on_click');
}


//Backwards compatibility: Migrate old vals to new ones
function genius_ile_migrate_1() {
  global $genius_ile_db_version;

  update_option('genius_ile_tsid', get_option('georiot_tsid'));
  update_option('genius_ile_api_key', get_option('georiot_api_key'));
  update_option('genius_ile_api_secret', get_option('georiot_api_secret'));
  update_option('genius_ile_api_remind', get_option('georiot_api_remind'));
  update_option('genius_ile_db_version', $genius_ile_db_version);

  //Delete the obsolete values, only if the old iTunes plugin isn't installed
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

  $now = time();
  $date_diff = $now - get_option('genius_ile_install_date');
  $age_in_days = floor($date_diff / (60 * 60 * 24));
  $age_to_show_prompt = 14;
  $gr_image_path = plugins_url().'/itunes-link-engine/img/';

  $form_class = '';
  if(get_option("genius_ile_liking") == 'yes') {
      $form_class = 'liking';
  } else if (get_option("genius_ile_liking") == 'no') {
      $form_class = 'disliking';
  }



  if (strpos($_SERVER['PHP_SELF'],'wp-admin/index.php') !== false  || strpos($_SERVER['PHP_SELF'],'wp-admin/plugins.php') !== false ) {
    if (get_option('genius_ile_api_remind') == 'yes' && get_option('genius_ile_tsid') == '') {
      ?>
      <div class="update-nag">
        <p><?php _e('<strong>iTunes Link Engine is installed. Want to earn commissions and use reporting? </strong>
        <br> Please <a href="'.admin_url().'options-general.php?page=itunes-link-engine">enter your GeniusLink API values</a>. Or, you can <a href="'.admin_url().'options-general.php?page=itunes-link-engine">disable this reminder</a>'); ?>.</p>
      </div>
    <?php
    }

    //Show Feedback form if it's been X days since signup and they haven't already dismissed it
    else if (get_option("genius_ile_dismiss_feedback") !== 'yes' && $age_in_days >= $age_to_show_prompt ) { //
    ?>
      <script>
       jQuery(document).ready(function($) {

          $( ".ile-feedback-like").click(function() {
            $("#genius_ile_liking").val('yes');
            $("#ile-feedback-form" ).submit();
          });
          $(".ile-feedback-dislike").click(function() {
            $("#genius_ile_liking").val('no');
            $("#ile-feedback-form" ).submit();
          });
          $(".ile-feedback-dismiss").click(function() {
            $("#genius_ile_dismiss_feedback").val('yes');
            $("#ile-feedback-form").submit();
          });
          $(".ile-feedback-reset").click(function() {
            $("#genius_ile_liking").val('');
            $("#genius_ile_dismiss_feedback").val('');
            $("#ile-feedback-form").submit();
          });

        });
      </script>

      <style>
        .genius-feedback {
          position: relative;
          max-width: 500px;
        }

        .genius-feedback.liking .ile-feedback-dismiss , .genius-feedback.disliking .ile-feedback-dismiss {
          position: static;
        }

        .genius-feedback button {
          background: transparent;
          border: none;
          cursor: pointer;
        }
        .genius-feedback button:focus {
          outline: none;
        }

        .ile-feedback-button {
          width: 48px;
          height: 48px;
          margin-top: 15px;
        }

        .ile-feedback-dismiss {
          display: inline-block;
          position: absolute;
          bottom: 10px;
          right: 10px;
        }


      </style>


      <div class="update-nag genius-feedback <?php echo $form_class ?>">
        <?php
        if (get_option("genius_ile_liking") == 'yes') {
          ?>
          <strong>Great to hear you like iTunes Link Engine!</strong>
          <p>Would you mind helping us out by leaving a rating at Wordpress.org? Each review makes a huge difference!</p>
          <p style="text-align: center">
            <a target="_blank" href="https://wordpress.org/support/plugin/itunes-link-engine/reviews/">Sure, take me there!</a> &nbsp; &nbsp;
            <a href="#" class="ile-feedback-dismiss">Not now</>
          </p>
          <?php
        } else if (get_option("genius_ile_liking") == 'no'){
          ?>
          <strong>Sorry to hear that!</strong>
          <p>Please let us know if there is anything we can help with or if you have any suggestions on how to improve Amazon Link Engine.</p>
          <p style="text-align: center">
            <a target="_blank" href="mailto:help@geni.us">Write to help@geni.us</a> &nbsp; &nbsp;
            <a href="#" class="ile-feedback-dismiss">Not now</>
          </p>
         <?php
        } else {
        ?>
         <p style="text-align: center">
            <strong>Thank you for using iTunes Link Engine.</strong> How has your experience been so far?
            <br>
            <button type="button" class="ile-feedback-like ile-feedback-button"><img src="<?php print $gr_image_path ?>thumbup.png" /></button>
            &nbsp;
            <button type="button" class="ile-feedback-dislike ile-feedback-button"><img src="<?php print $gr_image_path ?>thumbdown.png" /></button>
            <button type="button" class="ile-feedback-dismiss">Dismiss</button>
          </p>
          <?php
        } //End if they have neither liked nor disliked
        ?>

        <form id="ile-feedback-form" method="post" action="options.php" class="">
          <?php settings_fields('itunes-link-engine'); ?>

          <?php // Kludge? Send all existing values, otherwise they revert to defaults ?>
          <span style="display: none">
            <input maxlength="34" size="34" type="text" placeholder="Paste your api key" id="genius_ile_api_key" name="genius_ile_api_key" value="<?php echo get_option('genius_ile_api_key'); ?>"/>
            <input maxlength="34" size="34" type="text" placeholder="Paste your api secret" id="genius_ile_api_secret" name="genius_ile_api_secret" value="<?php echo get_option('genius_ile_api_secret'); ?>"/>
            <input type="checkbox" name="genius_ile_api_remind" value="yes" <?php if (get_option('genius_ile_api_remind') == 'yes') print "checked" ?> />
            <p>Signup timestamp: <?php echo get_option('genius_ile_install_date') ?></p>
          </span>
          <input size="10" type="hidden" name="genius_ile_tsid" id="genius_ile_tsid" value="<?php echo get_option("genius_ile_tsid"); ?>"/>
          <input size="100" type="hidden" name="genius_ile_domain" id="genius_ile_domain" value="<?php echo get_option("genius_ile_domain"); ?>"/>
          <input size="10" type="hidden" name="genius_ile_db_version" id="genius_ile_db_version" value="<?php echo get_option("genius_ile_db_version"); ?>"/>
          <?php // End Kludge ?>

          <!-- Feedback values-->
          <input size="10" type="hidden" name="genius_ile_liking" id="genius_ile_liking" value="<?php echo get_option("genius_ile_liking"); ?>"/>
          <input size="10" type="hidden" name="genius_ile_dismiss_feedback" id="genius_ile_dismiss_feedback" value="<?php echo get_option("genius_ile_dismiss_feedback"); ?>"/>

          <!--<button>.</button>-->
        </form>

      </div>
      <a style="opacity: 0" href="#" class="ile-feedback-reset">reset</a>

      <?php
    } //End if they haven't dismissed the feedback prompt

  }
}

//gets the state of the checkbox for onClick functionality of the link conversion
function ile_get_on_click_checkbox_state() {
	return get_option(genius_ile_urls_on_click) === 'yes';
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

  <script src="//cdn.georiot.com/snippet.min.js" defer></script>
  <script type="text/javascript">
    jQuery(document).ready(function( $ ) {
		var ile_on_click_checkbox_is_checked="<?php echo ile_get_on_click_checkbox_state();?>";

		if(ile_on_click_checkbox_is_checked) {
			Georiot.itunes.addOnClickRedirect(<?php echo $gr_use_tsid ?>, <?php print($preserve_tracking) ?><?php print($gr_use_domain) ?>);
		}
		else {
			Georiot.itunes.convertToGeoRiotLinks(<?php echo $gr_use_tsid ?>, <?php print($preserve_tracking) ?><?php print($gr_use_domain) ?>);
		};
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
  add_action('wp_head', 'genius_ile', 9999);
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