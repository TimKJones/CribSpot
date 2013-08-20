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