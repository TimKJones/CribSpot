<?php echo $this->Html->css('verify'); ?>
<?php echo $this->Html->script('src/VerifyManager'); ?>
<?php //App::import('Vendor', 'twitter');?>

<script type="text/javascript" src="http://platform.linkedin.com/in.js">
  api_key: 2rrh6emzm3mi
  onLoad: onLinkedInLoad
</script>
<div id="fb-root"></div>
<div id="facebook-title" class="title">Facebook</div>
<?php
if ($fb_userId)
  echo '<div class="facebook unverified">Not Verified</div>';
else
  echo '<div class="facebook verified">Verified</div>';
?>
<button onclick="A2Cribs.FacebookManager.JSLogin()">Connect with Facebook</button><br/></br/>
Find Mutual Friends <br/>
<input id="userid_input" placeholder="Enter friends user id"></input>
<button id="mutualFriendsSubmit" onclick="A2Cribs.FacebookManager.FindMutualFriends()">Submit</button>
<div id="numMutualFriendsDiv"><span id="numMutualFriendsVal">0</span>&nbspMutual Friends</div>
<div id="twitter-title" class="title">Twitter</div>
<?php
if (array_key_exists('twitter_confirmed', $_GET) && $success)
{
  echo '<div class="twitter verified">Verified</div>';
  echo '<div id="twitterData">' . $fullName . "(" . $userName . ") has " . $followerCount . " followers. </div>";
}
else
{
  echo '<div class="twitter unverified">Not Verified</div>';
}
?>
<a id="twitterLoginLink" href=<?php echo $twitterLoginUrl;?>>Login with Twitter</a><br/><br/>
<!--
<script type="in/Login">
</script>
<div class="linkedin unverified">Not Verified</div>
-->
Email verification (This email address will be the email for the person posting the sublet and will be pulled from the database - using an input box is for testing purposes)<br/>
<input id="emailInput" placeholder="Enter your email"></input>
<button id="emailSubmit" onclick="A2Cribs.FacebookManager.SubmitEmail()">Submit</button>
<div id="emailEduVerified" class="unverified">User does not have an edu email</div>


<?php /*
    App::Import("Vendor", "linkedin/linkedin");
    $config['base_url']             =   'http://localhost/verify';
    $config['callback_url']         =   'http://localhost/verify';
    $config['linkedin_access']      =   '2rrh6emzm3mi';
    $config['linkedin_secret']      =   'WMKg5Mkw2Vnfxh6C';

    # First step is to initialize with your consumer key and secret. We'll use an out-of-band oauth_callback
    $linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );

    # Now we retrieve a request token. It will be set as $linkedin->request_token
    $linkedin->getRequestToken();
    $_SESSION['requestToken'] = serialize($linkedin->request_token);

    # With a request token in hand, we can generate an authorization URL, which we'll direct the user to
    echo '<a href = ' . $linkedin->generateAuthorizeUrl() . '>Connect with Linkedin</a>';
    //header("Location: " . $linkedin->generateAuthorizeUrl());

    if (isset($_REQUEST['oauth_verifier'])){
        $_SESSION['oauth_verifier'] = $_REQUEST['oauth_verifier'];
        $linkedin->request_token    =   unserialize($_SESSION['requestToken']);
        $linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
        $linkedin->getAccessToken($_REQUEST['oauth_verifier']);

        $_SESSION['oauth_access_token'] = serialize($linkedin->access_token);
        header("Location: " . $config['callback_url']);
        exit;
   }
   else
   {
        $linkedin->request_token    =   unserialize($_SESSION['requestToken']);
        $linkedin->oauth_verifier   =   $_SESSION['oauth_verifier'];
        $linkedin->access_token     =   unserialize($_SESSION['oauth_access_token']);
   }
    
   # You now have a $linkedin->access_token and can make calls on behalf of the current member
    $xml_response = $linkedin->getProfile("~:(id, first-name,last-name,headline,picture-url)");
    echo $xml_response;

    # Now we retrieve a request token. It will be set as $linkedin->request_token
    $linkedin->getRequestToken();
    $_SESSION['requestToken'] = serialize($linkedin->request_token);*/
?>



<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '450938858319396', // App ID from the App Dashboard
      channelUrl : 'http://www.cribspot.com/channel.html', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });

    // Additional initialization code such as adding Event Listeners goes here

  };

  // Load the SDK's source Asynchronously
  // Note that the debug version is being actively developed and might 
  // contain some type checks that are overly strict. 
  // Please report such bugs using the bugs tool.
  (function(d, debug){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
     ref.parentNode.insertBefore(js, ref);
   }(document, /*debug*/ false));

  // function onLinkedInLoad() {
  //   IN.Event.on(IN, "auth", onLinkedInAuth);
  // }

  // function onLinkedInAuth() {
  //    IN.API.Profile("me").result(A2Cribs.FacebookManager.UpdateLinkedinLogin);
  // }
</script>