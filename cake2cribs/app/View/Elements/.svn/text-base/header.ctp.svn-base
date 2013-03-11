<div id="header">
	<a href="/"><div id="logo"></div></a>
	<div class="fb-like" data-href="http://www.facebook.com/A2Cribs" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true"></div>
	<!-- Place this tag where you want the +1 button to render. -->
	<div id="gplusContainer" style="position: absolute; top: 17px; left: 335px;">
		<div class="g-plusone" data-size="medium"></div>
	</div>

	<!-- Place this tag after the last +1 button tag. -->
	<script type="text/javascript">
	  (function() {
	    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
	    po.src = 'https://apis.google.com/js/plusone.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	  })();
	</script>
	<div id="header_right_bg"></div>
	<!--<?php if ($this->Session->read('user')) { ?>
		<a id="auth-loggedin" onclick="A2Cribs.FacebookManager.Logout()"><div class="topbar" id="logout_button"></div></a>
	<?php } else { ?>
		<a id="auth-loggedout" href=<?php echo $loginUrl; ?> onclick="A2Cribs.FacebookManager.Login()"><div class="topbar" id="login_button"></div></a>
	<?php } ?> -->
	<?php if ($this->Session->read('Auth.User')) { ?>
		<a id="auth-loggedin" href="/users/logout"><div class="topbar" id="logout_button"></div></a>
		<?php } else { ?>
		<a id="auth-loggedout" href="/users/login"><div class="topbar" id="login_button"></div></a>
	<?php } ?> 
	<div class="topbar" id="about_button"></div>
	<div class="topbar" id="contact_button"></div>
	<div class="topbar" id="sublease_button"></div>
	<div class="topbar" id="donate_button"></div>
</div>

<?php
	$this->Js->get('#grayBackground');
	$this->Js->event('click', 
		'$("#grayBackground").hide("fade", { direction: "left" }, 200);
		$(".popupContent").hide("fade", { direction: "left" }, 200);'
	);
	$this->Js->get('.close_button');
	$this->Js->event('click', 
		'$("#grayBackground").hide("fade", { direction: "left" }, 200);
		$(".popupContent").hide("fade", { direction: "left" }, 200);'
	);
	$this->Js->get('#about_button');
	$this->Js->event('click', 
		'$("#grayBackground").show("fade", { direction: "left" }, 200);
		$("#aboutPopup").show("fade", { direction: "left" }, 200);'
	);
	$this->Js->get('#contact_button');
	$this->Js->event('click', 
		'$("#grayBackground").show("fade", { direction: "left" }, 200);
		$("#contactPopup").show("fade", { direction: "left" }, 200);'
	);
	$this->Js->get('#sublease_button');
	$this->Js->event('click', 
		'$("#grayBackground").show("fade", { direction: "left" }, 200);
		$("#subleasePopup").show("fade", { direction: "left" }, 200);'
	);
	$this->Js->get('#donate_button');
	$this->Js->event('click', 
		'$("#grayBackground").show("fade", { direction: "left" }, 200);
		$("#donatePopup").show("fade", { direction: "left" }, 200);'
	);
	$this->Js->get('#termsDiv');
	$this->Js->event('click', 
		'$("#grayBackground").show("fade", { direction: "left" }, 200);
		$("#termsPopup").show("fade", { direction: "left" }, 200);'
	);
?>
