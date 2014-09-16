<?php
    $gr_image_path = plugins_url().'/amazon-link-engine/img/';
?>

<style>
  #gr-api-loading {
    display: none;
  }
  #gr-api-loaded {
    display: none;
  }
  .loading-tsid #gr-api-loading {
    display: block;
  }
  .loaded-tsid #gr-api-loaded {
    display: block;
  }
  #gr-api-loaded {
    font-weight: bold;
    color: #79b638;
  }
  .gr-my-tsid {
    font-weight: normal;
    color: #6f6f6f;
    font-size: 10px;
  }

  /* CSS css-only-spinner */
  .css-only-spinner {
    margin: 5px 5px 0 0;
    text-align: left;
    display: inline-block;
  }

  .css-only-spinner > div {
    width: 10px;
    height: 10px;
    background-color: #333;

    border-radius: 100%;
    display: inline-block;
    -webkit-animation: bouncedelay 1.4s infinite ease-in-out;
    animation: bouncedelay 1.4s infinite ease-in-out;
    /* Prevent first frame from flickering when animation starts */
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
  }

  .css-only-spinner .bounce1 {
    -webkit-animation-delay: -0.32s;
    animation-delay: -0.32s;
  }

  .css-only-spinner .bounce2 {
    -webkit-animation-delay: -0.16s;
    animation-delay: -0.16s;
  }

  @-webkit-keyframes bouncedelay {
    0%, 80%, 100% { -webkit-transform: scale(0.0) }
    40% { -webkit-transform: scale(1.0) }
  }

  @keyframes bouncedelay {
    0%, 80%, 100% {
      transform: scale(0.0);
      -webkit-transform: scale(0.0);
    } 40% {
        transform: scale(1.0);
        -webkit-transform: scale(1.0);
      }
  }
  /* End CSS css-only-spinner */


  .gr-step-area {
    width: 445px;
    border-radius: 3px;
    background: rgba(255,255,255,.5);
    border: 1px solid rgba(0,0,0,.05);
    padding: 10px 20px 10px 20px;
    min-height: 48px;
    margin-bottom: 3px;
  }

  .gr-step-number {
    float: left;
    width: 40px;
    height: 40px;
    border-radius: 21px;
    border: 2px dashed #999999;
    color: #7a7a7a;
    line-height: 42px;
    text-align: center;
    font-size: 21px;
    font-weight: bold;
    position: relative;
  }

  .gr-step-complete .gr-step-number {
    border: 2px solid #79b638;
    color: #79b638;
    background-color: #ffffff;www;
  }

  .gr-step-info {
    margin: 5px 0 10px 65px;
  }

  #connect-gr-api-form {
    margin-top: 40px;
  }

  .gr-affiliate-status {
    opacity: .6;
  }

  .gr-georiot-logo {
    vertical-align: -5%;
  }

  .gr-bygr {
    font-size: 55%;
  }

  .gr-checkmark {
    height: 20px;
    width: 20px;
    background: #79b638 url('<?php print $gr_image_path ?>check.png') center center no-repeat;
    border-radius: 10px;
    position: absolute;
    left: 28px;
    top: 24px;
    display: none;
  }

  .gr-step-complete .gr-checkmark {
    display: block;
  }


</style>

<script>
  jQuery(document).ready(function($) {
    $('#georiot_api_key, #georiot_api_secret').on('paste', function () {
      var element = this;
      setTimeout(function () {
        var text = $(element).val();
        //alert('test');
        $('#connect-gr-api-form').addClass('loading-tsid');
        getGeoriotTSID();
      }, 500);
    });


    function getGeoriotTSID() {
      //GeoRiot API Test
      var georiotApiKey = $('#georiot_api_key').val();
      var georiotApiSecret = $('#georiot_api_secret').val();
      var georiotApiUrl = "http://api.georiot.com/v1/groups/get-all-with-details?apiKey="+georiotApiKey+"&apiSecret="+georiotApiSecret+"&callback=?";
      $.getJSON( georiotApiUrl, function( data ) {
        //alert(data.Groups[0].Name);
        grGroups = data.Groups;
        grNumGroups = grGroups.length;

        /* We want to get the group ID with the lowest value and store it */
        var gr_low_tsid = 999999999;

        //Iterate over each group
        $.each(data.Groups, function( key, value ) {
          // and look at the TSID for each one. If it is lower than
          // the last one we saw, save it.
          if(value.Id < gr_low_tsid) {
            gr_low_tsid = value.Id;
          }
        });
        $('#gr-my-tsid-value').html( gr_low_tsid );
        $('#georiot_tsid').val( gr_low_tsid );


      }).done(function() {
        //alert("Hello");
        $('#connect-gr-api-form').removeClass('loading-tsid');
        $('#connect-gr-api-form').addClass('loaded-tsid');
      })
      ;
    }


    /* Not working
    $.ajaxSetup({
      beforeSend: function (jqXHR, settings) {
          jqXHR.setRequestHeader("X-Api-Key", "226c9cd82603471d9b6a16476ffa90fd");
          jqXHR.setRequestHeader("X-Api-Secret", "199b6b5d26ab48a48f64b69fd5e190ef");
      }
    });

    $.ajax({
      dataType: "json",
      url: "http://api.georiot.com/v1/links/list?groupid=2510&numberoflinks=2&callback=?"
    });
    */


  });

</script>


<div class="wrap">
  <h2>Amazon Link Conversion Engine <span class="gr-bygr">by </span>
    <img class='gr-georiot-logo' src="<?php print $gr_image_path ?>georiot_logo.png" width="66" height="12" /></h2>
  <p>This plugin has added Javascript that converts all iTunes and Amazon product URL’s on your site to global-friendly GeoRiot links. <a href="#">Learn more...</a></p>


  <form method="post" action="options.php" id="connect-gr-api-form" >
    <?php settings_fields('amazon-link-engine'); ?>

    <div class="gr-step-area gr-step-complete">
      <div class="gr-step-number">
        <span class="gr-checkmark"></span>
        1
      </div>
      <div class="gr-step-info">
        <strong>Improve sales and user experience:</strong> Your readers will now get to the right stores and products for their regions.
      </div>
    </div>

    <div class="gr-step-area <?php if (get_option('georiot_tsid') != '') print 'loaded-tsid gr-step-complete'; ?>">
      <div class="gr-step-number">
        <span class="gr-checkmark"></span>
        2
      </div>
      <div class="gr-step-info">
          <strong>Gain Insight with traffic reports.</strong> Create a free GeoRiot account and enter your API keys here.
          <a href="#">Learn how...</a>

          <br><br>
        <strong>Your GeoRiot API Key:</strong> <br>
        <input size="33" type="text" placeholder="" id="georiot_api_key" name="georiot_api_key" value="<?php echo get_option('georiot_api_key'); ?>" /></td>

        <br><br>
        <strong>Your GeoRiot API Secret:</strong> <br>
        <input size="33" type="text" placeholder="" id="georiot_api_secret" name="georiot_api_secret" value="<?php echo get_option('georiot_api_secret'); ?>" />

        <div id="gr-api-loading">
          <div class="css-only-spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
          </div>
          Connecting...
        </div>
        <div id="gr-api-loaded">Connected! &nbsp;
            <span class="gr-my-tsid">
              (Using Group #<span id="gr-my-tsid-value"><?php print get_option('georiot_tsid') ?></span>)
            </span>
        </div>
      </div>
    </div>

    <div class="gr-step-area">
      <div class="gr-step-number">
        <span class="gr-checkmark"></span>
        3
      </div>
      <div class="gr-step-info">
        <strong>Monetize your traffic:</strong> Earn commissions for every sale by connecting  affiliate programs.
        <br>
        <span class="gr-affiliate-status">4 of 8 affiliate programs connected. Add more...</span>
      </div>
    </div>

    <br>
    &nbsp;<input type="checkbox" name="georiot_api_remind" value="yes" <?php if (get_option('georiot_api_remind') == yes) print "checked" ?> />
    Show Wordpress alert on dashboard if commissions are not enabled

    <br><br>
    <input size="10" type="hidden" name="georiot_tsid" id="georiot_tsid" value="<?php echo get_option('georiot_tsid'); ?>" />
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />


    <div id="testdump"></div>

  </form>
</div>
