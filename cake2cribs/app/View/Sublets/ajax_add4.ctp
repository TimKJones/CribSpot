<!--<div> BLAH</div>
<a class="ajax" href="/sublets/ajax_add" id="gotoscreen2">Go back </a> -->
<?php echo $this->Html->css('SubletAddEditCommon'); ?>
<?php echo $this->Html->css('ajax_add4'); ?>
<?php echo $this->Html->css('jquery-ui'); ?>
<?php //echo $this->Html->script('src/SubletAdd'); ?>
<?php echo $this->Html->css('account'); ?>

<div class="sublet-register">
	<?php 
	 
	//echo $this->Form->input('description', array('value' => $savedDescription));
	?>
	
	<div id="sublet_register_screen1">
		<style> .datepicker { width: 17em; padding: .2em .2em 0; z-index: 9999 !important; } </style>
		<input style="height:0px; top:-1000px; position:absolute" type="text" value="">
		<div id="shareDiv">Share with your friends!</div>

		<div class="share-buttons">
			<a href="#" class="facebook-share"><div></div></a>
			<a href="#" target="_blank" class="twitter-share"><div></div></a>
		</div>

		<a href="#" id="finishShare">Finish</a>
	</div>
</div>

<script>
	var a = A2Cribs.SubletAdd;
	a.setupUI();
	var s = A2Cribs.Cache.SubletEditInProgress;
	var address = s.Marker.street_address.split(" ").join("_");
	var university = s.Sublet.university_name.split(" ").join("_");

	$(".facebook-share").click(function(){
		A2Cribs.ShareManager.ShareListingOnFacebook(university, address, parseInt(A2Cribs.ShareManager.SavedListing), s.Sublet.description);
	})

	$(".twitter-share").attr('href', A2Cribs.ShareManager.GetTwitterShareUrl(university, address, parseInt(A2Cribs.ShareManager.SavedListing)));
</script>