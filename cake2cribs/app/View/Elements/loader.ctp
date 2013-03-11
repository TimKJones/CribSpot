<div id="loader">
	<img src="/img/popup/loader.gif">
</div>

<?php
	$this->Js->buffer(
		'$(document).ajaxStart(function(){ $("#loader").show(); }).ajaxStop(function(){ $("#loader").hide(); });
		$("#loader").hide();'
	);
?>
