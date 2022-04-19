<?php
include_once('header.php');
?>
<div class="Polaris-Page__Content">
        <div class="Polaris-Banner">
      <p class="Polaris-Heading">Need any other help?</p>
                <p>We are always here to help you. Please <a class="Polaris-Link" href="mailto:<?php echo SITE_EMAIL; ?>" target="_blank">email us</a></p>
        </div>
  
    <br>
    <div class="Polaris-Banner-hol">
        <div class="Polaris-Banner-head"><span>Banner Settings</span>
        <p class="grey-text">Enable/disable banner in just one click.</p>
        </div>
        <div class="Polaris-Banner">
                    <label>Domain URL:</label>
                    <input class="input-text" type = "text" name="user_doamin" id="user_doamin" readonly value="<?php if(!empty($current_user['user_domain'])){ echo $current_user['user_domain']; }else{ echo $current_user['domain']; } ?>">
                    <label>Email:  *</label>
                    <input class="input-text" type = "email" name="user_email" id="user_email" value="<?php if(!empty($current_user['user_email'])){ echo $current_user['user_email']; }else{  echo $current_user['email']; } ?>">
                    <label>Domain Group ID:</label>
                  <input class="input-text" type = "text" name="data_key" id="user_key" value="<?php echo @$current_user['data_key']; ?>" readonly>
                 <p class="cooloes-text">CONSENT </p>
                 <p class="cooloes-text">
                    By using this plugin, you agree to the <a href='https://seersco.com/terms-and-conditions.html' target='_blank'>terms and condition</a>  and <br> <a href='https://seersco.com/privacy-policy.html' target='_blank'>privacy policy</a>, and also agree Seers to use my email and url to <br> create an account and power the cookie banner.
</p>
<hr style="margin:0 auto 10px; border-bottom:.5px dotted #c1c1c1; width:95%">
                 <p class="cooloes-text">You must enter Domain Url and Email to get a Consent Banner.</p>
                 <?php  if($current_user['toggle_status']==1){ ?>
                <p class="enable-banner"><span class ="banner-tick"></span> Banner is enabled on your store.<br><span style="margin-left:18px;"></span>Please refresh your store home page to see the effect. </p>
                <?php }else{?>
                <p class="enable-banner">Banner is disabled on your store.</p>
                <?php } ?>
                <div class="onoffswitch">
                    <?php  if($current_user['toggle_status']==1){ ?>

                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" checked  tabindex="0">
                    <?php }else{ ?>
                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch"  tabindex="0">
                    <?php } ?>
                    <label class="onoffswitch-label" for="myonoffswitch">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>

   
        </div>
    </div>
    <br>
        
    </div>
   
<?php include_once('footer.php'); ?>
<script>
    var switchStatus = false;
   
    $("#myonoffswitch").on('change', function() {
        if ($(this).is(':checked')) {
            switchStatus = $(this).is(':checked');
             var user_doamin  = $('#user_doamin').val();
             var user_email   = $('#user_email').val();
             var data_key     = $('#user_key').val();
            toggleCheckedVal(switchStatus,user_doamin,user_email,data_key);
        }
        else {
            switchStatus = $(this).is(':checked');
             var user_doamin  = $('#user_doamin').val();
             var user_email   = $('#user_email').val();
             var data_key     = $('#user_key').val();
            toggleCheckedVal(switchStatus,user_doamin,user_email,data_key);
        }
    });
</script>

