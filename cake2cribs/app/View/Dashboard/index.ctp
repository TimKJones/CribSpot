<?php echo $this->element('header', array('show_filter' => false, 'show_user' => true)); ?>
<?php echo $this->element('Dashboard/marker-modal', $dropdowns);?>
<?php echo $this->element('Dashboard/picture-modal');?>
<?php echo $this->Html->css('messages'); ?>
<?php echo $this->Html->css('account'); ?>
<?php echo $this->Html->css('dashboard'); ?>

<?php
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
	echo $this->Html->script('src/Dashboard');
	echo $this->Html->script('src/Account');
	echo $this->Html->script('src/Message');
	echo $this->Html->script('src/PropertyManagement');
	echo $this->Html->script('src/VerifyManager');
	echo $this->Html->script('src/SubletSave');
	echo $this->Html->script('src/UserCache', array('inline' => false));
}

echo $this->Html->script('underscore');
?>

<?php $this->set('title_for_layout', 'Dashboard'); ?>

<div class='container-fluid' id = 'main_content'>
	<div class = 'row-fluid'>
		<div id = 'left_content' class = 'span2'>
			<?php echo $this->element('Dashboard/navigation-bar'); ?>			
		</div>

		<div id = 'middle_content' class = 'span10'>
			<div class = 'row-fluid account-content hidden'>
				<div class = 'span9'>
					<?php echo $this->element('Account/edit_account_window', $user) ?>
				</div>
				<div class = 'span3'>
					<?php echo $this->element('Account/user_info') ?>
				</div>

			</div>

			<div class = 'row-fluid messages-content hidden'>
				<div class = 'span9 message-display'>
					<?php echo $this->element('Messages/message_window') ?>
				</div>
				<div class = 'span3'>
					<?php echo $this->element('Messages/participant_info') ?>
				</div>
			</div>

			<div class = 'rentals-content hidden'>
				<?php echo $this->element('Dashboard/rentals_window', $user) ?>
			</div>

			<div class = 'row-fluid featuredlisting-content hidden'>
				<?php echo $this->element('FeaturedListings/dashview') ?>
			</div>

		</div>
	</div>
</div>

<script>


	// // A2Cribs.Messages.init();

	// window.fbAsyncInit = function() {
 //    // init the FB JS SDK
 //    FB.init({
 //      appId      : '148187588666959', // App ID from the App Dashboard
 //      channelUrl : 'http://localhost/channel.html', // Channel File for x-domain communication
 //      status     : true, // check the login status upon init?
 //      cookie     : true, // set sessions cookies to allow your server to access the session?
 //      xfbml      : true  // parse XFBML tags on this page?
 //    });

 //    // Additional initialization code such as adding Event Listeners goes here

 //  };

 //  // Load the SDK's source Asynchronously
 //  // Note that the debug version is being actively developed and might 
 //  // contain some type checks that are overly strict. 
 //  // Please report such bugs using the bugs tool.
 //  (function(d, debug){
 //     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
 //     if (d.getElementById(id)) {return;}
 //     js = d.createElement('script'); js.id = id; js.async = true;
 //     js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
 //     ref.parentNode.insertBefore(js, ref);
 //   }(document, /*debug*/ false));

 //  function onLinkedInLoad() {
 //    IN.Event.on(IN, "auth", onLinkedInAuth);
 //  }

 //  function onLinkedInAuth() {
 //     IN.API.Profile("me").result(A2Cribs.FacebookManager.UpdateLinkedinLogin);
 //  }
 	A2Cribs.VerifyManager.init(<?php echo $user_json;?>);
	A2Cribs.Dashboard.SetupUI();
	A2Cribs.Account.setupUI();
	var a = A2Cribs.Messages

	var directive = <?php echo $directive;?>;
	if(directive.classname != null){
		A2Cribs.Dashboard.Direct(directive);
		A2Cribs.Messages.Direct(directive);
		A2Cribs.Account.Direct(directive);
		// A2Cribs.Sublets.Direct(directive);
		// A2Cribs.MyListings.Direct(directive); for the listings section
	}else{

	}

	a.init(<?php echo $user_json;?>);
	a.setupUI();
	

// check for url parameter to init post action
if (document.URL.indexOf("post_redirect") != -1)
    A2Cribs.SubletAdd.InitPostingProcess();
else if (document.URL.indexOf("university_verified") != -1)
    A2Cribs.UIManager.Alert("You have successfully been verified with a university!");


</script>
	
