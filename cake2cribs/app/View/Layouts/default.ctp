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
		echo $this->Html->script('jquery.form');
		echo $this->Html->script('jquery-ui');
		echo $this->Html->script('custom-bootstrap');
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
		echo $this->Html->script('knockout.js');

		/* CSS Data */
		echo('<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">');
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
