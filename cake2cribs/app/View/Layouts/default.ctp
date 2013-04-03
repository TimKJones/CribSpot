<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

?>
<?php 
	echo $this->Html->docType('html5');
	echo '<head>';
?>
		<script type="text/javascript">var myBaseUrl = '<?php echo $this->Html->url('/'); ?>';</script> 
<?php
		echo $this->Html->charset();
		echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key=AIzaSyChGyO2wCFqmDe8FNh_6GxITy7dDLQ0ZpE&libraries=places&sensor=false', false);
		echo $this->Html->script('jquery');
		echo $this->Html->script('http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js');
		echo $this->Html->script('jquery.controls');
	
		echo $this->Html->script('jquery.dialog2');
		echo $this->Html->script('jquery.dialog2.helpers');

		echo $this->Html->script('jquery.form');
		echo $this->Html->script('jquery-ui');
		echo $this->Html->script('custom-bootstrap');
		echo $this->Html->script('markerclusterer_packed.js');
		echo $this->Html->script('google_plus1');
		echo $this->Html->script('google_analytics');
		echo $this->Html->script('infobubble.js');
		echo $this->Html->script('src/A2Cribs');	
		echo $this->Html->script('src/Map');
		echo $this->Html->script('src/MarkerTooltip');
		echo $this->Html->script('src/Favorite');
		echo $this->Html->script('src/Listing');
		echo $this->Html->script('src/Realtor');
		echo $this->Html->script('src/Marker');
		echo $this->Html->script('src/FilterManager');
		echo $this->Html->script('src/FavoritesManager');
		echo $this->Html->script('src/FacebookManager');
		echo $this->Html->script('src/UtilityFunctions');
		echo $this->Html->script('src/CorrectMarker');
		echo $this->Html->script('src/PhotoManager');
		echo $this->Html->script('src/ShareManager');
		echo $this->Html->script('src/HoverBubble');
		echo $this->Html->script('src/ClickBubble');
		echo $this->Html->script('src/Cache');
		echo $this->Html->script('src/Sublet');
		echo $this->Html->script('src/Housemate');
		echo $this->Html->script('src/University');
		echo $this->Html->script('src/HoverData');
		echo $this->Html->script('src/SubletOwner');
		echo $this->Html->script('src/ListingPopup');
		echo $this->Html->script('src/UIManager');
		echo $this->Html->script('src/Image');
		echo $this->Html->script('src/SubletAdd');
		echo $this->Html->script('src/SubletEdit');
		echo $this->Html->script('src/SubletInProgress');
		echo $this->Html->script('knockout.js');
		echo $this->Html->script('alertify.min.js');
		

		/* CSS Data */
		echo('<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">');
		echo('<link rel="stylesheet" type="text/css" href="/css/alertify.core.css">');
		echo('<link rel="stylesheet" type="text/css" href="/css/alertify.default.css">');
		echo('<link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">');
		





		echo '<title>' . $title_for_layout . '</title>';

		/* Meta Data */
		echo $this->Html->meta('keywords',
			'ann arbor housing, ann arbor apartments, student housing, university of michigan housing, umich housing, ann arbor rentals, ann arbor subleases, UM housing, a2cribs, off-campus housing'
		);
		echo $this->Html->meta('description', 'A2 Cribs is a free service tailored towards college students searching for rentals and sublets. We make it simple and quick to filter thousands of listings by price, beds, fall/spring leases, etc.');
		echo $this->Html->meta('favicon.icon', '/favicon.ico?v=2', array('type' => 'icon'));

		/* Fetch data */
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

		echo $this->Html->script('less');

		echo $this->Html->scriptBlock('var jsVars = '.$this->Js->object($jsVars).';');

		/* Write buffer for JS in various element views */
		echo $this->Js->writeBuffer();

	echo '</head>';

	echo '<body>';

	/* FB API for Like Button */
	echo '<div id="fb-root"></div><script>(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="//connect.facebook.net/en_US/all.js#xfbml=1";fjs.parentNode.insertBefore(js,fjs);}(document,"script","facebook-jssdk"));</script>';

	echo '<div id="layoutsContainer">';
		echo '<div id="content">';
			echo $this->Session->flash();
			echo $this->fetch('content');
			
		echo '</div>';
	echo '</div>';

	/*<center><h3>------------------------------------------------------- Debug Info ------------------------------------------------------------</h3></center>
	<?php echo $this->element('sql_dump'); ?> */
	echo '</body>';
	echo '</html>';







	/*
			echo $this->Html->script('jquery.form');
		//echo $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/noisy/1.1/jquery.noisy.min.js');
		
		echo $this->Html->script('jquery-ui');
		echo $this->Html->script('markerclusterer_packed.js');
		echo $this->Html->script('google_plus1');
		echo $this->Html->script('infobubble.js');
		echo $this->Html->script('src/A2Cribs');	
		echo $this->Html->script('src/Map');
		echo $this->Html->script('src/MarkerTooltip');
		echo $this->Html->script('src/Favorite');
		echo $this->Html->script('src/Listing');
		echo $this->Html->script('src/Realtor');
		echo $this->Html->script('src/Marker');
		echo $this->Html->script('src/FilterManager');
		echo $this->Html->script('src/FavoritesManager');
		echo $this->Html->script('src/FacebookManager');
		echo $this->Html->script('src/UtilityFunctions');
		echo $this->Html->script('src/CorrectMarker');
		echo $this->Html->script('src/PhotoManager');
		echo $this->Html->script('src/Message');
		echo $this->Html->script('knockout.js');
		//echo $this->Html->script('src/Message');

*/
?>

<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '450938858319396', // App ID from the App Dashboard
      channelUrl : 'http://www.cribspot.com:8888/channel.html', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true,  // parse XFBML tags on this page?
      oauth		 : true,
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
</script>
