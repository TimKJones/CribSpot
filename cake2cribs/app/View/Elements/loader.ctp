<?php
	echo $this->Html->css('/less/loader.less?','stylesheet/less', array('inline' => false));
?>

<div id="loader">
	<img src="/img/popup/loader.gif">
</div>

<?php
	$this->Js->buffer(
		'$(document).ajaxStart(function(){ $("#loader").show(); }).ajaxStop(function(){ $("#loader").hide(); });
		$("#loader").hide();'
	);
?>
