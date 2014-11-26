<?php
    $gr_image_path = plugins_url().'/itunes-link-engine/img/';
?>

<style>
  #gr-tsid-spinner {
    display: none;
    opacity: .5;
  }
  #gr-tsid-loaded {
    display: none;
  }
  .gr-status-loading-tsid #gr-tsid-spinner {
    display: block;
  }
  .gr-status-loaded-tsid #gr-tsid-loaded {
    display: block;
  }
  #gr-tsid-loaded, .gr-success {
    font-weight: bold;
    color: #79b638;
    margin-top: 5px;
  }
  .gr-my-tsid {
    font-weight: normal;
    color: #6f6f6f;
  }
  .gr-tiny {
    font-size: 10px;
  }




  #gr-tsid-error {
    display: none;
    color: #880000;
    margin-top: 5px;
  }
  .gr-status-error-tsid #gr-tsid-error {
    display: block;
  }
  #gr-affiliates-spinner {
    display: none;
    opacity: .5;
  }
  .gr-status-loading-affiliates #gr-affiliates-spinner {
    display: block;
  }
  .gr-status-loading-affiliates #gr-affiliates-loaded {
    display: none;
  }
  #gr-affiliates-loaded {
    margin-top: 5px;
    display: none;
  }
  .gr-status-loaded-affiliates #gr-affiliates-loaded {
    display: block;
  }
  #gr-affiliates-error {
    display: none;
    color: #880000;
    margin-top: 5px;
  }
  .gr-status-error-affiliates #gr-affiliates-error {
    display: block;
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
    border: 1px solid rgba(0,0,0,.1);
    padding: 10px 20px 10px 20px;
    min-height: 48px;
    margin-bottom: 3px;
  }
  .gr-step-area strong {
   font-size: 14px;
  }

  .gr-step-area a:link, .gr-step-area a:visited {
    text-decoration: none;
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
    margin-top: 20px;
  }

  .gr-georiot-logo {
    vertical-align: -5%;
    border: none;
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

  h3 {
    font-size: 22px;
    color: #999;
    margin-top: 30px;
    font-weight: normal;
  }

  .gr-intro {
    max-width: 500px;
  }
</style>

<script>
  jQuery(document).ready(function($) {

    //Update the affiliates section on page load, if the API keys are filled
    if ( $('#georiot_api_key').val().length == 32 && $('#georiot_api_secret').val().length == 32 ) {
      getGeoriotAffiliates();
    }


    //Auto highlight the API fields on focus
    $('#georiot_api_key').click( function() {
      $(this).select();
    });
    $('#georiot_api_secret').click( function() {
      $(this).select();
    });

    //Clear API fields and TSID
    $('#gr-disconnect-api').click( function() {
      $('#georiot_api_key').val('');
      $('#georiot_api_secret').val('');
      $('#georiot_tsid').val('');
      $('#gr-step-2').removeClass('gr-step-complete');
      $('#connect-gr-api-form').removeClass('gr-status-loaded-tsid');
      $('#gr-step-3').removeClass('gr-step-complete');
      $('#connect-gr-api-form').removeClass('gr-status-loaded-affiliates');

      alert('Your API values have been cleared. To finish, remember to click "Save Changes".');

    });

    //Detect paste into the api key or secret fields.
    $('#georiot_api_key, #georiot_api_secret').on('paste', function () {
      var element = this;
      setTimeout(function () {
        getGeoRiotTSID();
      }, 500);
    });

    // Re-submit button can also trigger api connect
    $('.gr-resubmit').click( function(e) {
      getGeoRiotTSID();
      e.preventDefault();
    });

    // Refrsh button for the affiliates section
    $('.gr-refresh-affiliates').click( function(e) {
      getGeoriotAffiliates();
      e.preventDefault();
    });


    function getGeoRiotTSID() {
      // Validate fields and then send request
      // If both api fields are correct, check the API
      if ( $('#georiot_api_key').val().length == 32 && $('#georiot_api_secret').val().length == 32 ) {
        connectGeoriotApi();
      } else if( $('#georiot_api_key').val().length > 0 && $('#georiot_api_secret').val().length > 0 ) {
        //if both fields have values, but are not the right length, tell the user
        if($('#georiot_api_key').val().length != 32) alert('The API Key field appears to be invalid. Please copy and paste it again');
        if($('#georiot_api_secret').val().length != 32) alert('The API Secret field appears to be invalid. Please copy and paste it again');
      }
    }

    function connectGeoriotApi() {
      // Show loading indicators and disable submit button while we wait for a response
      $('#connect-gr-api-form').addClass('gr-status-loading-tsid');
      $('#connect-gr-api-form').removeClass('gr-status-loaded-tsid');
      $('#connect-gr-api-form').removeClass('gr-status-error-tsid');
      $('.button-primary').prop("disabled",true);

      var georiotApiKey = $('#georiot_api_key').val();
      var georiotApiSecret = $('#georiot_api_secret').val();
      var georiotApiUrlGroups = "https://api.georiot.com/v1/groups/get-all-with-details?apiKey="+georiotApiKey+"&apiSecret="+georiotApiSecret;

      var requestGeoRiotGroups = $.ajax({
        url : georiotApiUrlGroups,
        dataType : "json",
        timeout : 10000
      })
        .done(function( data ) {
            grGroups = data.Groups;
            grNumGroups = grGroups.length;

            // We want to get the group ID with the lowest value and store it
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

            //Show completion in UI
            $('#connect-gr-api-form').addClass('gr-status-loaded-tsid');
            $('#gr-step-2').addClass('gr-step-complete');
        })
        .fail(function() {
          $('#connect-gr-api-form').addClass('gr-status-error-tsid');
          $('#gr-step-2, #gr-step-3').removeClass('gr-step-complete');
        })
        .always(function() {
          $('#connect-gr-api-form').removeClass('gr-status-loading-tsid');
          $('.button-primary').prop("disabled",false);
        })
      ;

      getGeoriotAffiliates('suppressError');
      // We don't want to inundate the user with errors, so suppress the affiliate one in this case.

    }

    function getGeoriotAffiliates(suppressError) {
      //Loading effects
      $('#connect-gr-api-form').addClass('gr-status-loading-affiliates');
      $('#connect-gr-api-form').removeClass('gr-status-loaded-affiliates');
      $('#connect-gr-api-form').removeClass('gr-status-error-affiliates');


      var georiotApiKey = $('#georiot_api_key').val();
      var georiotApiSecret = $('#georiot_api_secret').val();
      var georiotApiUrlAffiliates = "https://api.georiot.com/v1/affiliate/stats?apiKey="+georiotApiKey+"&apiSecret="+georiotApiSecret;


      var requestGeoRiotAffiliates = $.ajax({
          url : georiotApiUrlAffiliates,
          dataType : "json",
          timeout : 10000
        })
          .done(function( data ) {
            var griTunesEnrolled =  0;
            var griTunesAvailable =  0;

            //Iterate over the enrolled programs and add up how many iTunes programs there are.
            // There is only one iTunes program now, but we'll use the same approach as with the Amazon Link Engine.
            $.each(data.ProgramsEnrolled, function( key, value ) {
              if(value.indexOf("Performance Horizon Group") > -1) { griTunesEnrolled++; }
            });

            //Iterate over the available programs and add up how many iTunes programs there are.
            // Not needed since there is only one iTunes program
            /*
            $.each(data.AvailablePrograms, function( key, value ) {
              if(value.indexOf("Performance Horizon Group") > -1) { griTunesAvailable++; }
            });
            */

            //Create Success mesage
            //$('#gr-aff-enrolled').html(griTunesEnrolled)  //Not used since there is only one program
            //$('#gr-aff-available').html(griTunesAvailable) //Not used since there is only one program

            if (griTunesEnrolled >= 1) {
              $('#gr-step-3').addClass('gr-step-complete');
            }
            //Show completion in UI
            $('#connect-gr-api-form').addClass('gr-status-loaded-affiliates');
          })
          .fail(function() {
            if(suppressError != 'suppressError') {
              $('#connect-gr-api-form').addClass('gr-status-error-affiliates');
            }
          })
          .always(function() {
            $('#connect-gr-api-form').removeClass('gr-status-loading-affiliates');
          })
        ;
    }

  });

</script>


<div class="wrap">
  <h2>iTunes Link Engine <span class="gr-bygr">by </span>
    <a href="http://georiot.com" target="_blank"><img class='gr-georiot-logo' src="<?php print $gr_image_path ?>georiot_logo.png" width="66" height="12" /></a></h2>
  <p class="gr-intro">This plugin has added Javascript that converts all iTunes product
    URLs on your site to global-friendly GeoRiot links. <a href="#faq-whatisgeoriot">Learn more...</a>
  </p>

  <h3>Get the most from this plugin</h3>
  <form method="post" action="options.php" id="connect-gr-api-form" class="<?php if (get_option('georiot_tsid') != '') print 'gr-status-loaded-tsid'; ?>">
    <?php settings_fields('itunes-link-engine'); ?>

    <div id="gr-step-1" class="gr-step-area gr-step-complete">
      <div class="gr-step-number">
        <span class="gr-checkmark"></span>
        1
      </div>
      <div class="gr-step-info">
        <strong>Improve sales and user experience:</strong> Your readers will now get to the right stores and products for their regions.
      </div>
    </div>

    <div id="gr-step-2" class="gr-step-area <?php if (get_option('georiot_tsid') != '') print 'gr-step-complete'; ?>">
      <div class="gr-step-number">
        <span class="gr-checkmark"></span>
        2
      </div>
      <div class="gr-step-info">
          <strong>Gain Insight with traffic reports.</strong> <a href="http://www.georiot.com/wordpress-plugin/sign-up">Create a free GeoRiot account</a> and enter your API keys here.
          <a href="#faq-apikeys">Learn how...</a>

          <br><br>
        API Key: <br>
        <input maxlength="32" size="33" type="text" placeholder="Paste your api key" id="georiot_api_key" name="georiot_api_key" value="<?php echo get_option('georiot_api_key'); ?>" /></td>

        <br><br>
        API Secret:<br>
        <input maxlength="32" size="33" type="text" placeholder="Paste your api secret" id="georiot_api_secret" name="georiot_api_secret" value="<?php echo get_option('georiot_api_secret'); ?>" />

        <div id="gr-tsid-spinner">
          <div class="css-only-spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
          </div>
          Connecting...
        </div>
        <div id="gr-tsid-loaded">Connected! &nbsp;
            <span class="gr-my-tsid gr-tiny">
              (Using Group #<span id="gr-my-tsid-value"><?php print get_option('georiot_tsid') ?></span>)
              &nbsp; <a href="#" id="gr-disconnect-api">Disconnect</a>
            </span>
        </div>
        <div id="gr-tsid-error"><strong>Oops.</strong> Please double-check your API key and secret.
          <button class="gr-resubmit">Re-submit</button>
        </div>
      </div>
    </div>

    <div id="gr-step-3" class="gr-step-area">
      <div class="gr-step-number">
        <span class="gr-checkmark"></span>
        3
      </div>
      <div class="gr-step-info">
        <strong>Monetize your traffic:</strong> Earn commissions for every sale by <a href="http://manage.georiot.com/Affiliate">connecting affiliate programs</a>.
        <br>
        <div id="gr-affiliates-spinner">
          <div class="css-only-spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
          </div>
          Updating
        </div>

        <span id="gr-affiliates-loaded">
           <span class="gr-success">iTunes program connected.</span> <a class="gr-refresh-affiliates gr-tiny" href="#">Refresh</a>
        </span>
        <div id="gr-affiliates-error"><strong>Sorry,</strong> there was a problem connecting to the GeoRiot API.
        </div>
      </div>
    </div>

    <br>
    &nbsp;<input type="checkbox" name="georiot_api_remind" value="yes" <?php if (get_option('georiot_api_remind') == 'yes') print "checked" ?> />
    Show Wordpress alert on dashboard if commissions are not enabled

    <br><br>
    <input size="10" type="hidden" name="georiot_tsid" id="georiot_tsid" value="<?php echo get_option('georiot_tsid'); ?>" />
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />

    <style>
      .faq {
        border-top: 1px solid #cccccc;
        margin-top: 80px;
        padding-top: 0px;
        max-width: 500px;
        margin-bottom: 400px;
      }

      .faq h4 {
        margin: 30px 5px 0 0;
        font-size: 16px;
      }
    </style>

    <div class="faq">
      <h3>Frequently asked questions</h3>

      <h4 id="faq-whatisgeoriot">What is GeoRiot</h4>
      <p>GeoRiot is a link management platform that maximizes your potential iTunes Associates revenue
        by globalizing and affiliating your iTunes Links.  GeoRiot hopes to change the way online
        commerce is conducted by providing its customers with intelligent links that automatically
        route customers to the correct product within their own local storefront.
      </p>

      <h4 id="faq-whatisgeoriot">Do I need a GeoRiot Account to use this plugin?</h4>
      <p><strong>No,</strong> you do NOT need a GeoRiot account to use the iTunes Link Engine plugin.
        As soon as you download the free plugin, all of your links will be automatically
        localized, and your customers will be routed to the product in their local storefront.
        However, if you want to add your affiliate parameters, you will need a GeoRiot account.
      </p>

      <h4 id="faq-apikeys">How do I get my API keys?</h4>
      <p>To get your GeoRiot API Keys, follow these simple steps:
      </p>
      <ol>
        <li>If you do not have a GeoRiot account, <a href="http://www.georiot.com/wordpress-plugin/sign-up">create a free account</a>.</li>

        <li>Log into your GeoRiot Dashboard, and navigate to the to the Account Tab.</li>

        <li>Click the “plus” sign to get your API keys.</li>

        <li>Next, simply copy and paste the “Key” and “Secret” codes into the “Enable Reporting and Commissions” area of the plugin.<br>
          <strong>Please note:</strong> It may take up to 3 minutes for new keys to become available for use after adding them to your dashboard.</li>
        <li>Once pasted, your GeoRiot account will be automatically connected.</li>

      </ol>


      <h4 id="faq-international">How do I earn International Commissions?</h4>
      <p>
        First, connect the plugin to your GeoRiot account (see “How do I get my API keys?”).  Then, follow the steps below:</p>
      <ol>
        <li>Add your iTunes Affiliate parameters to your GeoRiot dashboard.  Instructions on how to do this can be found <a href="http://support.georiot.com/support/solutions/articles/71867-add-existing-affiliate">here</a>.
        <br><strong>Note:</strong> If you’ve already done this within your existing GeoRiot account, you do not need to add your parameters again.
        </li>
        <li> You’re all set!  You’ll start earning international commissions from anything purchased in iTunes’s international storefronts.</li>
      </ol>


      <h4 id="faq-pay">Do I have to pay for GeoRiot?</h4>
      <p><strong>No,</strong>  you never need to give us a credit card or make payments to
        use the GeoRiot Service. The service is always free.
      </p>
      <p>At GeoRiot, clicks are our currency, and we earn money the way you do –
        through international affiliate programs. With our “Click Share” payment model,
        we simply take a maximum of 15% of your total clicks from the additional international
        clicks that we help you monetize.
      </p>
      <p><strong>Please note: By default, GeoRiot's affiliate parameters will be used until
          you have added your own via the GeoRiot dashboard.</strong>
        Please <a href="mailto:contact@georiot.com">contact GeoRiot</a> if you have any questions.
      </p>

    </div>

  </form>
</div>