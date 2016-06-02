<?php
    $gr_image_path = plugins_url().'/itunes-link-engine/img/';
?>

<style>
  .gr-icon-alert {
    display: inline-block;
    background-color: #880000;
    height: 18px;
    width: 18px;
    border-radius: 9px;
    color: #fff;
    text-align: center;
    font-size: 14px;
  }

  .gr-tsid-spinner {
    display: none;
    opacity: .5;
  }
  .gr-tsid-loaded {
    display: none;
  }
  .gr-status-loading-tsid .gr-tsid-spinner {
    display: block;
  }
  .gr-status-loaded-tsid .gr-tsid-loaded {
    display: block;
  }
  .gr-tsid-loaded {
    margin-top: 5px;
  }

  .gr-status-loading-tsid {
    opacity: .5;
  }

  .gr-connected-success {
    font-weight: bold;
    color: #00b9ee;
  }

  .gr-program-connected{
    display: none;
  }
  .gr-step-complete .gr-program-connected{
    display: inline;
  }
  .gr-step-complete .gr-no-program{
    display: none
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

  #gr-tsid-mismatch-error, #gr-domain-mismatch-error  {
    display: none;
    color: #880000;
    margin-top: 5px;
    width: 447px;
    padding: 20px;
    border: 1px solid #880000;
    border-radius: 6px;
  }

#gr-tsid-mismatch-error strong, #gr-domain-mismatch-error strong {
  font-size: 140%;
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
    opacity: .6;
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


  /* CSS css-only-spinner  */
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
    /* Prevent first frame from flickering when animation starts  */
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
  /* End CSS css-only-spinner.  */


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
    border: 2px solid #00b9ee;
    color: #00b9ee;
    background-color: #ffffff;
  }

  .gr-step-info {
    margin: 5px 0 10px 65px;
  }

  #connect-gr-api-form {
    margin-top: 20px;
  }

  .gr-georiot-logo {
    vertical-align: -13%;
    border: none;
  }

  .gr-bygr {
    font-size: 55%;
  }

  .gr-checkmark {
    height: 20px;
    width: 20px;
    background: #00b9ee url('<?php print $gr_image_path ?>check.png') center center no-repeat;
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

  #gr-advanced-options {
    position: relative;
    min-height: 0;
  }

  .gr-advanced-options-fields {
    overflow: hidden;
    transition: height .3s;
    height: 0;
  }

  .expanded .gr-advanced-options-fields {
    height: 120px;
    transition: height .3s;
  }

  .gr-expand, .gr-collapse {
    font-size: 18px;
    text-align: right;
    display: inline-block;
    width: 20px;
    font-style: normal;
    font-weight: bold;
    color: #444444;
  }

  .gr-expand, .gr-collapse, h5 {
    cursor: pointer;
  }

  .gr-collapse {
    display: none;
  }

  .expanded .gr-collapse {
    display: inline-block;
  }
  .expanded .gr-expand, .expanded .hidden-expanded {
    display: none;
  }


  #gr-advanced-options h5{
    font-size: 14px;
    margin: 0;
  }


  #georiot_tsid_select {
    min-width: 125px;
  }
  #georiot_domain_select {
    min-width: 150px;
  }
  #georiot_api_key, #georiot_api_secret {
    font-family: "Courier New", monospace;
  }

</style>

<script>
  jQuery(document).ready(function($) {

    /* Update the affiliates section and load groups on page load, if the API keys are filled */
    if ( $('#georiot_api_key').val().length == 32 && $('#georiot_api_secret').val().length == 32 ) {
      getGeoriotAffiliates();
      connectGeoriotApi(true);
    }

    $('.gr-expand, .gr-collapse, #gr-advanced-options h5').click( function() {
      $('#gr-advanced-options').toggleClass('expanded');;
    });

    /* Auto highlight the API fields on focus */
    $('#georiot_api_key').click( function() {
      $(this).select();
    });
    $('#georiot_api_secret').click( function() {
      $(this).select();
    });

    /* Clear API fields and TSID if user clicks disconnect button */
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

    /* Detect paste into the api key or secret fields. */
    $('#georiot_api_key, #georiot_api_secret').on('paste', function () {
      var element = this;
      setTimeout(function () {
        submitApiKeys();
      }, 500);
    });

    /*  Re-submit button can also trigger api connect */
    $('.gr-resubmit').click( function(e) {
      submitApiKeys();
      e.preventDefault();
    });

    /*  Refresh button for the affiliates section */
    $('.gr-refresh-affiliates').click( function(e) {
      getGeoriotAffiliates();
      e.preventDefault();
    });


    function submitApiKeys() {
      /*Trim any whitespace in keys*/
      key = $('#georiot_api_key').val().trim();
      secret = $('#georiot_api_secret').val().trim();

      $('#georiot_api_key').val(key);
      $('#georiot_api_secret').val(secret);

      /*  Validate fields and then send request */
      /*  If both api fields are correct, check the API */
      if ( $('#georiot_api_key').val().length == 32 && $('#georiot_api_secret').val().length == 32 ) {
        connectGeoriotApi();
      } else if( $('#georiot_api_key').val().length > 0 && $('#georiot_api_secret').val().length > 0 ) {
        /* if both fields have values, but are not the right length, tell the user */
        if($('#georiot_api_key').val().length != 32) alert('The API Key field appears to be invalid. Please copy and paste it again');
        if($('#georiot_api_secret').val().length != 32) alert('The API Secret field appears to be invalid. Please copy and paste it again');
      }
    }

    function connectGeoriotApi(pageLoadup) {

      /*  We run some different checks if this function is run on initial page load. */
      if (typeof(pageLoadup)==='undefined') pageLoadup = false;


      /*  Show loading indicators and disable submit button while we wait for a response */
      $('#connect-gr-api-form').addClass('gr-status-loading-tsid');
      $('#connect-gr-api-form').removeClass('gr-status-loaded-tsid');
      $('#connect-gr-api-form').removeClass('gr-status-error-tsid');
      $('.button-primary').prop("disabled",true);

      getGeoriotGroups(pageLoadup);
      getGeoriotDomains(pageLoadup);

      getGeoriotAffiliates('suppressError');
      /* We don't want to inundate the user with errors, so suppress the affiliate one in this case. */

    }

    function getGeoriotGroups(pageLoadup) {

      var georiotApiKey = $('#georiot_api_key').val();
      var georiotApiSecret = $('#georiot_api_secret').val();
      var georiotApiUrlGroups = "https://api.geni.us/v1/groups/get-all-with-details?apiKey="+georiotApiKey+"&apiSecret="+georiotApiSecret;

      var requestGeniuslinkGroups = $.ajax({
            url : georiotApiUrlGroups,
            dataType : "json",
            timeout : 10000
          })
          .done(function( data ) {
            grGroups = data.Groups;
            grNumGroups = grGroups.length;
            existingTsid = $('#georiot_tsid').val(); /* This is the previous selected tsid in the <select> */
            sameAccount = false;

            /*  We want to know the group ID with the lowest value use it, by default */
            /* Initial default value: */
            var gr_low_tsid = 999999999;

            /* Iterate over each group to find the "default" (lowest ID), and populate the select option
             First, clear out the select field first in case it already has options
             */
            $('#georiot_tsid_select').html('');

            $.each(grGroups, function( key, value ) {
              /* Append this group to the select field */
              $('#georiot_tsid_select').append('<option value="'+value.Id+'">'+value.Name+'</option>');


              if(value.Id == existingTsid) {
                sameAccount = true;
                /*  If the list contains a group with the same TSID as was loaded initially, we know we are looking at the same Account info */
                /*  and we will not auto select the default group (lowest tsid) for the user ( because we only do that the first time API creds are entered) */
                /* console.log('list contains a group with the same TSID'); */
              }

              /*  Look at the TSID for each one. If it is lower than the last, save it for later. */
              /* console.log(value.Name +' '+ value.Id);  */
              if(value.Id < gr_low_tsid) {
                gr_low_tsid = value.Id;
              }
            });


            /* Select default group */
            /* Mark the oldest/lowest group tsid value as selected, only if they don't already have a valid group chosen */
            if ( !sameAccount ) {
              /* User entered keys for a different account, so let's auto select the default group for them */
              /* Mark group as selected in the select field */
              $("#georiot_tsid_select option[value="+gr_low_tsid+"]").attr('selected', 'selected');
              /* Set the group to be used by the plugin */
              $('#georiot_tsid').val( gr_low_tsid );

              if(pageLoadup) {
                /* User just loaded or refreshed the plugin page, and the TSID stored in WP is not included in their Genius account */
                /* Let's show an alert to describe this problem. This could be  asign that the DB table is not writable. */
                $('#gr-tsid-mismatch-error').show();
              }

            } else {
              /* Preserve the previously selected group */
              existingTsid = $('#georiot_tsid').val();
              $("#georiot_tsid_select option[value="+existingTsid+"]").attr('selected', 'selected');
            }

            /* Show completion in UI */
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
    }


    function getGeoriotDomains(pageLoadup) {
      var georiotApiKey = $('#georiot_api_key').val();
      var georiotApiSecret = $('#georiot_api_secret').val();
      var georiotApiUrlDomains = "https://api.geni.us/v1/custom-domains/domains?apiKey="+georiotApiKey+"&apiSecret="+georiotApiSecret;

      var requestGeniuslinkDomains = $.ajax({
            url : georiotApiUrlDomains,
            dataType : "json",
            timeout : 10000
          })
          .done(function( data ) {
            grDomains = data.Domains;
            grNumDomains = grDomains.length;
            var gr_default_domain = 'geni.us';
            existingDomain = $('#georiot_domain').val(); /* This is the previous selected domain in the <select> */
            if (existingDomain === '') {
              existingDomain = gr_default_domain;
            }
            sameAccount = false;
            /*  Sort the domains list data by name, ascending */
            prop = 'Name'; /* Sort by this key in Groups  */
            grDomains = grDomains.sort(function(a, b) {
              return (a[prop] > b[prop]) ? 1 : ((a[prop] < b[prop]) ? -1 : 0);
              /* Descending: return (b[prop] > a[prop]) ? 1 : ((b[prop] < a[prop]) ? -1 : 0); */
            });
            /*  First, clear out the select field first in case it already has options */
            $('#georiot_domain_select').html('');
            $.each(grDomains, function( key, value ) {
              /* Append this group to the select field */
              $('#georiot_domain_select').append('<option value="'+value.Name+'">'+value.Name+'</option>');
              if(value.Name == existingDomain) {
                sameAccount = true;
                /*  If the list contains a domain with the same name as was loaded initially, we know we are looking at the same Account info */
                /*  and we will not auto select the default domain for the user ( because we only do that the first time API creds are entered) */
              }
            });
            /* Mark the default domain as selected, only if they don't already have a valid group chosen */
            if ( !sameAccount ) {
              /*  User entered keys for a different account, so let's auto select the default domain for them */
              $("#georiot_domain_select option[value='"+gr_default_domain+"']").attr('selected', 'selected');
              /* Set the group to be used by the plugin */
              $('#georiot_domain').val( gr_default_domain );
              if(pageLoadup) {
                /*  User just loaded or refreshed the plugin page, and the domain stored in WP is not included in their Genius account */
                /*  Let's show an alert to describe this problem. This could be asign that the WP DB table is not writable. */
                $('#gr-domain-mismatch-error').show();
              }
            } else {
              /* Preserve the previously selected domain */
              existingDomain = $('#georiot_domain').val();
              $("#georiot_domain_select option[value='"+existingDomain+"']").attr('selected', 'selected');
            }
            /* Show completion in UI */
            $('#connect-gr-api-form').addClass('gr-status-loaded-domain');
            $('#gr-step-2').addClass('gr-step-complete');
          })
          .fail(function() {
            $('#connect-gr-api-form').addClass('gr-status-error-domain');
            $('#gr-step-2, #gr-step-3').removeClass('gr-step-complete');
          })
          .always(function() {
            $('#connect-gr-api-form').removeClass('gr-status-loading-domain');
            $('.button-primary').prop("disabled",false);
          })
          ;
    }


    function getGeoriotAffiliates(suppressError) {
      /* Loading effects */
      $('#connect-gr-api-form').addClass('gr-status-loading-affiliates');
      $('#connect-gr-api-form').removeClass('gr-status-loaded-affiliates');
      $('#connect-gr-api-form').removeClass('gr-status-error-affiliates');


      var georiotApiKey = $('#georiot_api_key').val();
      var georiotApiSecret = $('#georiot_api_secret').val();
      var georiotApiUrlAffiliates = "https://api.geni.us/v1/affiliate/stats?apiKey="+georiotApiKey+"&apiSecret="+georiotApiSecret;


      var requestGeniuslinkAffiliates = $.ajax({
          url : georiotApiUrlAffiliates,
          dataType : "json",
          timeout : 10000
        })
          .done(function( data ) {
            var griTunesEnrolled =  0;
            var griTunesAvailable =  0;

            /* Iterate over the enrolled programs and add up how many iTunes programs there are. */
            /*  There is only one iTunes program now, but we'll use the same approach as with the Amazon Link Engine. */
            $.each(data.ProgramsEnrolled, function( key, value ) {
              if(value.indexOf("Performance Horizon Group") > -1) { griTunesEnrolled++; }
            });

            /* Iterate over the available programs and add up how many iTunes programs there are. */
            /*  Not needed since there is only one iTunes program */
            /*
            $.each(data.AvailablePrograms, function( key, value ) {
              if(value.indexOf("Performance Horizon Group") > -1) { griTunesAvailable++; }
            });
             */

            if (griTunesEnrolled >= 1) {
              $('#gr-step-3').addClass('gr-step-complete');
            }
            /* Show completion in UI */
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


    /* Group Selection */
    $( "#georiot_tsid_select" ).change(function() {
      newgroup = $(this).val();
      $('#georiot_tsid').val(newgroup);
    });

  });

</script>


<div class="wrap">
  <h2>iTunes Link Engine <span class="gr-bygr">by </span>
    <a href="http://geni.us" target="_blank"><img class='gr-georiot-logo' src="<?php print $gr_image_path ?>georiot_logo.png" width="66" height="16" /></a></h2>
  <p class="gr-intro">This plugin has added JavaScript that converts all iTunes
    URLs on your site to global-friendly Geni.us links. <a href="#faq-whatisgeoriot">Learn more...</a>
  </p>

  <h3>Get the most from this plugin</h3>

  <div id="gr-tsid-mismatch-error">
    <strong><span class='gr-icon-alert'>!</span> Uh oh!</strong><br>
    <p>
      It looks like we weren't able to save your group selection correctly.
      This can happen if our plugin can't connect to your WordPress backend.
      For a quick troubleshooting step, please try selecting the group and clicking "Save" again.
    </p>
    <p>
      If that doesn't work, shoot us an email at help@geni.us and we will get you squared away.
    </p>
  </div>
  <div id="gr-domain-mismatch-error">
    <strong><span class='gr-icon-alert'>!</span> Uh oh!</strong><br>
    <p>
      It looks like we weren't able to save your domain selection correctly.
      This can happen if our plugin can't connect to your WordPress backend.
      For a quick troubleshooting step, please try selecting the domain and clicking "Save" again.
    </p>
    <p>
      If that doesn't work, shoot us an email at help@geni.us and we will get you squared away.
    </p>
  </div>

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
        <strong>Optional: Connect to your Geniuslink Account</strong> <br>
        <a target="_blank" href="http://social.geni.us/ALEGenius">Create a Geniuslink account</a> and enter your API
        keys here to enjoy click reporting and custom domains.
        <a href="#faq-apikeys">Learn how...</a>

          <br><br>
        API Key: <br>
        <input maxlength="34" size="34" type="text" placeholder="Paste your api key" id="georiot_api_key" name="georiot_api_key" value="<?php echo get_option('georiot_api_key'); ?>" /></td>

        <br><br>
        API Secret:<br>
        <input maxlength="34" size="34" type="text" placeholder="Paste your api secret" id="georiot_api_secret" name="georiot_api_secret" value="<?php echo get_option('georiot_api_secret'); ?>" />

        <div class="gr-tsid-spinner">
          <div class="css-only-spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
          </div>
          Connecting...
        </div>
        <div class="gr-tsid-loaded"><span class="gr-connected-success">Connected!</span> &nbsp;
          <span class="gr-my-tsid gr-tiny">
            &nbsp; <a href="#" id="gr-disconnect-api">Disconnect</a>
          </span>
          <br><br>
          Using Link Group:<br>
          <select name="georiot_tsid_select" id="georiot_tsid_select"><option>--Error getting groups--</option></select>
          <br><br>
          Use Domain:<br>
          <select name="georiot_domain_select" id="georiot_domain_select"><option>--Error getting domains--</option></select>
          <br><br>
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
        <strong>Optional: Monetize your traffic:</strong> Earn commissions for every sale by <a target="_blank" href="http://my.geni.us/Affiliate">connecting your PHG affiliate program</a>.
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
          <span class="gr-program-connected">iTunes program connected.</span>
          <span class="gr-no-program">No programs connected.</span>
          <a class="gr-refresh-affiliates gr-tiny" href="#">Refresh</a>
        </span>
        <div id="gr-affiliates-error"><strong>Sorry,</strong> there was a problem connecting to the Geniuslink API.
        </div>
      </div>
    </div>

    <br>
    &nbsp;<input type="checkbox" name="georiot_api_remind" value="yes" <?php if (get_option('georiot_api_remind') == 'yes') print "checked" ?> />
    Show Wordpress alert on dashboard if commissions are not enabled

    <br><br>
    <input size="10" type="hidden" name="georiot_tsid" id="georiot_tsid" value="<?php echo get_option('georiot_tsid'); ?>" />
    <input size="100" type="hidden" name="georiot_domain" id="georiot_domain" value="<?php echo get_option('georiot_domain'); ?>" />
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
  </form>

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

    <h4 id="faq-whatisgeoriot">What is Geniuslink</h4>
    <p>Geniuslink is the secret sauce behind the iTunes Link Engine that allows you to build the world’s most intelligent links. Improve user experience, and maximize your affiliate earnings and marketing efforts through Geniuslink’s service. For marketers promoting content within the iTunes ecosystem, Geniuslink allows you to build intelligent links that automatically route customers to the correct product within their own local storefront. Geniuslink also allows you to enter your PHG Affiliate Token to earn commissions from all of your clicks.
    </p>

    <h4 id="faq-account-optional">Do I need a Geniuslink Account to use this plugin?</h4>
    <p><strong>No,</strong> you do NOT need a Geniuslink account to use the iTunes Link Engine plugin.  As soon as you install and activate the free plugin, all of your links will be automatically localized, and your customers will be routed to the correct product in their local storefront.  However, if you want to add your affiliate token, you will need a Geniuslink account. By default, Geniuslink’s affiliate token will be used until you have connected your account, and added your own via the Geniuslink Dashboard.
    </p>

    <h4 id="faq-apikeys">How do I get my API keys?</h4>
    <p>To get your Geniuslink API Keys, follow these simple steps:
    </p>
    <ol>
      <li>If you do not have a Geniuslink account, <a target="_blank" href="http://social.geni.us/iLESignup">create an account</a>.</li>

      <li>Log into your Geniuslink Dashboard, and navigate to the to the Account Tab.</li>

      <li>Click the “plus” sign to get your API keys.</li>

      <li>Navigate to the iTunes Link Engine settings under “Settings” in your WordPress dashboard.</li>

      <li>Next, simply copy and paste the “Key” and “Secret” codes into the “Enable Reporting and Commissions” area of the plugin.<br>
        <strong>Please note:</strong> It may take up to 3 minutes for new keys to become available for use after adding them to your dashboard.</li>
      <li>Once pasted, your Geniuslink account will be automatically connected.</li>

    </ol>


    <h4 id="faq-international">How do I affiliate my links?</h4>
    <p>
      First, connect the plugin to your Geniuslink account (see “How do I get my API keys?”).  Then, follow the steps below:</p>
    <ol>
      <li>Add your PHG affiliate parameter to your Geniuslink dashboard.  Instructions on how to do this can be found
        <a target="_blank" href="http://help.geni.us/support/solutions/articles/3000034945">here</a>.
      <br><strong>Note:</strong> If you’ve already done this within your existing Geniuslink account, you do not need to add your parameter again.
      </li>
      <li> You’re all set! You’ll start earning commissions from anything purchased from iTunes.</li>
      <p>
        <strong>Please Note:</strong> If you are already using affiliated links on your website,
        they will not remain affiliated if you use the iTunes Link Engine Plugin.
        In order to affiliate your links, you must
        <a target="_blank" href="http://social.geni.us/iLESignup">sign up for a Geniuslink account</a> and add your affiliate parameters to the dashboard.  Once you have done this, and connected your account using the API keys, each of your links will be automatically affiliated.
      </p>
    </ol>


    <h4 id="faq-groups">How do I change the default group?</h4>
    <p>In order to change the group that the plugin syncs with, you must first connect your
      Geniuslink account using your API keys. Once you have done this, you can simply select
      the group you would like to use from the drop down menu under “Using Link Group” in the
      iTunes Link Engine Settings.
    </p>

    <h4 id="faq-pay">Do I have to pay for Geniuslink?</h4>
    <p>If you’re only interested in giving your international audience a better experience by redirecting them to their local storefront, the iTunes Link Engine is completely free.
    </p>
    <p>However, if you would like access to advanced reporting features and be able to affiliate all of your links, you will need to
      <a target="_blank" href="http://social.geni.us/ALEGenius">sign up for a Geniuslink account</a>. Learn more about our [fair and transparent pricing](http://social.geni.us/iLEPricing).
    </p>
    <p><strong>Please note: By default, Geniuslink's affiliate parameters will be used until
        you have added your own via the Geniuslink dashboard.</strong>
      Please <a href="mailto:hi@geni.us">contact Geniuslink</a> if you have any questions.
    </p>
  </div>
</div>