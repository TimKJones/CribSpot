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

<script>
// polyfill for IE 9 and 10 custom events
(function () {
  function CustomEvent ( event, params ) {
    params = params || { bubbles: false, cancelable: false, detail: undefined };
    var evt = document.createEvent( 'CustomEvent' );
    evt.initCustomEvent( event, params.bubbles, params.cancelable, params.detail );
    return evt;
   };

  CustomEvent.prototype = window.CustomEvent.prototype;

  window.CustomEvent = CustomEvent;
})();
</script>

		<script type="text/javascript">var myBaseUrl = '<?php echo $this->Html->url('/'); ?>';</script> 
		<script type="text/javascript">var flash_message = <?php echo $flash_message; ?></script>
<?php
		echo $this->Html->charset();
		 echo $this->Html->script('https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyChGyO2wCFqmDe8FNh_6GxITy7dDLQ0ZpE&libraries=places&sensor=false', false);

		echo $this->Html->script('jquery');
		echo $this->Html->script('restrict_browsers');
		echo $this->Html->script('jquery-ui');
		echo $this->Html->script('custom-bootstrap');
		echo $this->Html->script('typeahead');
		echo $this->Html->script('markerclusterer_packed.js');
		echo $this->Html->script('google_plus1');
		echo $this->Html->script('google_analytics');
		echo $this->Html->script('infobubble.js');

if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
		echo $this->Html->script('src/A2Cribs');	
		echo $this->Html->script('src/Object');
		echo $this->Html->script('src/Geocoder');
		echo $this->Html->script('src/User');
		echo $this->Html->script('src/Listing');
		echo $this->Html->script('src/Marker');
		echo $this->Html->script('src/MapActivity');
		echo $this->Html->script('src/FavoritesManager');
		echo $this->Html->script('src/FacebookManager');
		echo $this->Html->script('src/ShareManager');
		echo $this->Html->script('src/SmallBubble');
		echo $this->Html->script('src/LargeBubble');
		echo $this->Html->script('src/UIManager');
		echo $this->Html->script('src/Image');
		echo $this->Html->script('src/Sublet');
}
	echo $this->Html->script('knockout.js');
	echo $this->Html->script('alertify.min.js');
	echo $this->Html->script('underscore');
	echo $this->Html->script('jquery-ui.multidatespicker'); 


		/* CSS Data */
		echo('<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">');
    echo('<link href="/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">');
		echo('<link rel="stylesheet" type="text/css" href="/css/alertify.core.css">');
		echo('<link rel="stylesheet" type="text/css" href="/css/alertify.default.css">');
		echo('<link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">');
		echo $this->Html->css('/js/slickgrid/css/smoothness/jquery-ui-1.8.16.custom.css');
		echo $this->Html->css('multi-date-picker');
		echo $this->Html->css('basic');
		echo $this->Html->css('/font/stylesheet.css?v=1');


		echo '<title>' . $title_for_layout . '</title>';

		echo $this->Html->meta('favicon.icon', '/favicon.ico?v=44', array('type' => 'icon'));

		/* Fetch data */
		echo $this->fetch('meta');
		echo $this->fetch('css');


		if (!isset($canonical_url))
			$canonical_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if (!isset($meta_image))
			$meta_image = 'https://cribspot.com/img/upright_logo.png';
		if (!isset($meta_description))
			$meta_description = "Cribspot takes the pain out of finding off-campus housing on college campuses.  We display thousands of listings on a map so you can stop stressing and get back to ...studying.";

		echo $this->Html->script('less');

		echo $this->Html->scriptBlock('var jsVars = '.$this->Js->object($jsVars).';');

		echo $this->element('SEO/facebook_meta_tag', array('title' => $title_for_layout, 'url' => $canonical_url, 'image_path' => $meta_image, 'description' => $meta_description));


    /* Meta tag for responsive goodness */
    echo '<meta name="viewport" content="initial-scale=1">';

	echo '</head>';

	echo '<body>';
?>

<!-- begin olark code -->
<script data-cfasync="false" type='text/javascript'>/*<![CDATA[*/window.olark||(function(c){var f=window,d=document,l=f.location.protocol=="https:"?"https:":"http:",z=c.name,r="load";var nt=function(){
f[z]=function(){
(a.s=a.s||[]).push(arguments)};var a=f[z]._={
},q=c.methods.length;while(q--){(function(n){f[z][n]=function(){
f[z]("call",n,arguments)}})(c.methods[q])}a.l=c.loader;a.i=nt;a.p={
0:+new Date};a.P=function(u){
a.p[u]=new Date-a.p[0]};function s(){
a.P(r);f[z](r)}f.addEventListener?f.addEventListener(r,s,false):f.attachEvent("on"+r,s);var ld=function(){function p(hd){
hd="head";return["<",hd,"></",hd,"><",i,' onl' + 'oad="var d=',g,";d.getElementsByTagName('head')[0].",j,"(d.",h,"('script')).",k,"='",l,"//",a.l,"'",'"',"></",i,">"].join("")}var i="body",m=d[i];if(!m){
return setTimeout(ld,100)}a.P(1);var j="appendChild",h="createElement",k="src",n=d[h]("div"),v=n[j](d[h](z)),b=d[h]("iframe"),g="document",e="domain",o;n.style.display="none";m.insertBefore(n,m.firstChild).id=z;b.frameBorder="0";b.id=z+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){
b.src="javascript:false"}b.allowTransparency="true";v[j](b);try{
b.contentWindow[g].open()}catch(w){
c[e]=d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{
var t=b.contentWindow[g];t.write(p());t.close()}catch(x){
b[k]=o+'d.write("'+p().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}a.P(2)};ld()};nt()})({
loader: "static.olark.com/jsclient/loader0.js",name:"olark",methods:["configure","extend","declare","identify"]});
/* custom configuration goes here (www.olark.com/documentation) */
olark.identify('9961-903-10-7290');/*]]>*/</script><noscript><a href="https://www.olark.com/site/9961-903-10-7290/contact" title="Contact us" target="_blank">Questions? Feedback?</a> powered by <a href="http://www.olark.com?welcome" title="Olark live chat software">Olark live chat software</a></noscript>
<!-- end olark code -->

<?php
	/* FB API for Like Button */
	echo '<div id="fb-root"></div>';

	echo '<div id="layoutsContainer">';
		echo $this->Session->flash();
		echo $this->fetch('content');
	echo '</div>';
?>
<script>
  window.fbInit = new $.Deferred();
  window.fbInit.promise();
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '<?php echo Configure::read('FB_APP_ID');?>', // App ID from the App Dashboard
      channelUrl : 'http://www.cribspot.com:8888/channel.html', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true,  // parse XFBML tags on this page?
      oauth		 : true,
      frictionlessRequests : true
    });

		FB.Event.subscribe('auth.statusChange', function() {
    	window.fbInit.resolve();
		});

  };

  // Load the SDK asynchronously
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/all.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<?php
	echo $this->fetch('script');

	/* Write buffer for JS in various element views */
	echo $this->Js->writeBuffer();

	if (Configure::read("CURRENT_ENVIRONMENT") === "ENVIRONMENT_PRODUCTION"){
		echo $this->Html->script('src/program.js?v=135');
	}

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
