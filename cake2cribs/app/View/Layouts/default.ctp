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
<!-- start Mixpanel --><script type="text/javascript">(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"===e.location.protocol?"https:":"http:")+'//cdn.mxpnl.com/libs/mixpanel-2.2.min.js';f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f);b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==
typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.track_charge people.clear_charges people.delete_user".split(" ");for(g=0;g<i.length;g++)f(c,i[g]);
b._i.push([a,e,d])};b.__SV=1.2}})(document,window.mixpanel||[]);
mixpanel.init("<?php echo Configure::read('MIXPANEL_TOKEN'); ?>");</script>

<?php 
if ($this->Session->read('Auth.User.id') != 0) {
	$this->Js->buffer('
		mixpanel.identify(' . $this->Session->read('Auth.User.id') . ');
		mixpanel.register({
			user_id:' . $this->Session->read('Auth.User.id') . ', 
			user_type:' . $this->Session->read('Auth.User.user_type') .
		'})'
	);
}
?>
<!-- end Mixpanel -->

		<script type="text/javascript">var myBaseUrl = '<?php echo $this->Html->url('/'); ?>';</script> 
		<script type="text/javascript">var flash_message = <?php echo $flash_message; ?></script>
<?php
		echo $this->Html->charset();
		 echo $this->Html->script('https://maps.googleapis.com/maps/api/js?key=AIzaSyChGyO2wCFqmDe8FNh_6GxITy7dDLQ0ZpE&libraries=places&sensor=false', false);

		echo $this->Html->script('jquery');
		echo $this->Html->script('https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js');
		echo $this->Html->script('jquery.controls');
	
		echo $this->Html->script('jquery.dialog2');
		echo $this->Html->script('jquery.dialog2.helpers');
		echo $this->Html->script('restrict_browsers');
		echo $this->Html->script('jquery.form');
		echo $this->Html->script('jquery-ui');
		echo $this->Html->script('custom-bootstrap');
		echo $this->Html->script('markerclusterer_packed.js');
		echo $this->Html->script('google_plus1');
		echo $this->Html->script('google_analytics');
		echo $this->Html->script('infobubble.js');

if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
		echo $this->Html->script('src/A2Cribs');	
		echo $this->Html->script('src/Object');
		echo $this->Html->script('src/MixPanel');
		echo $this->Html->script('src/User');
		echo $this->Html->script('src/MarkerTooltip');
		echo $this->Html->script('src/Favorite');
		echo $this->Html->script('src/Listing');
		echo $this->Html->script('src/Realtor');
		echo $this->Html->script('src/Marker');
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
		echo $this->Html->script('src/HoverData');
		echo $this->Html->script('src/SubletOwner');
		echo $this->Html->script('src/ListingPopup');
		echo $this->Html->script('src/UIManager');
		echo $this->Html->script('src/Image');
		echo $this->Html->script('src/SubletAdd');
		echo $this->Html->script('src/SubletEdit');
		echo $this->Html->script('src/SubletInProgress');
}
	echo $this->Html->script('knockout.js');
	echo $this->Html->script('alertify.min.js');
	echo $this->Html->script('underscore');

		/* CSS Data */
		echo('<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">');
		echo('<link rel="stylesheet" type="text/css" href="/css/alertify.core.css">');
		echo('<link rel="stylesheet" type="text/css" href="/css/alertify.default.css">');
		echo('<link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">');
		echo $this->Html->css('basic');
		echo $this->Html->css('/font/stylesheet.css?v=1');





		echo '<title>' . $title_for_layout . '</title>';

		/* Meta Data */
/*		echo $this->Html->meta('keywords',
			'ann arbor housing, ann arbor apartments, student housing, university of michigan housing, umich housing, ann arbor rentals, ann arbor subleases, UM housing, cribspot, a2cribs, off-campus housing'
		);
*/

		echo $this->Html->meta('favicon.icon', '/favicon.ico?v=44', array('type' => 'icon'));

		/* Fetch data */
		echo $this->fetch('meta');
		echo $this->fetch('css');

if (Configure::read("CURRENT_ENVIRONMENT") === "ENVIRONMENT_PRODUCTION"){
	echo $this->Html->script('src/program.js?v=5');
}

		echo $this->fetch('script');
		echo $this->Html->script('less');

		echo $this->Html->scriptBlock('var jsVars = '.$this->Js->object($jsVars).';');

		/* Write buffer for JS in various element views */
		echo $this->Js->writeBuffer();

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
	echo '<div id="fb-root"></div><script>(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="//connect.facebook.net/en_US/all.js#xfbml=1";fjs.parentNode.insertBefore(js,fjs);}(document,"script","facebook-jssdk"));</script>';

	echo '<div id="layoutsContainer">';
		echo $this->Session->flash();
		echo $this->fetch('content');
	echo '</div>';
?>
<script>
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

    // Additional initialization code such as adding Event Listeners goes here

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