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
		echo $this->Html->script('google_analytics');

		echo '<title>' . $title_for_layout . '</title>';

		/* Meta Data */
		echo $this->Html->meta('description', 'A2 Cribs is a free service tailored towards college students searching for rentals and sublets. We make it simple and quick to filter thousands of listings by price, beds, fall/spring leases, etc.');
		echo $this->Html->meta('favicon.icon', '/favicon.ico?v=2', array('type' => 'icon'));

		/* Fetch data */
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

		echo $this->Html->scriptBlock('var jsVars = '.$this->Js->object($jsVars).';');

		/* Write buffer for JS in various element views */
		echo $this->Js->writeBuffer();

	echo '</head>';

	echo '<body>';

	echo '<div id="layoutsContainer">';
		echo '<div id="content">';
			echo $this->Session->flash();
			echo $this->fetch('content');
			
		echo '</div>';
	echo '</div>';
	echo '</body>';
	echo '</html>';


?>
