<?php echo $this->Facebook->html(); ?>
<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '488039367944782', // App ID from the App Dashboard
      channelUrl : 'http://www.cribspot.com/channel.html', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });

    // Additional initialization code such as adding Event Listeners goes here

  };

  // Load the SDK's source Asynchronously
  (function(d, debug){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
     ref.parentNode.insertBefore(js, ref);
   }(document, /*debug*/ false));
</script>
<?php echo $this->Html->script('src/loginTest'); ?>
        <?php echo $this->Facebook->init(); ?>
        <button id="facebookLogin" onclick="A2Cribs.loginTest.FacebookLogin()">Login</button>
        <button id="facebookLogin" onclick="A2Cribs.loginTest.FacebookLogout()">Logout</button>
</html>

