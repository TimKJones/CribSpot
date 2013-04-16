<!--<div> BLAH</div>
<a class="ajax" href="/sublets/ajax_add" id="gotoscreen2">Go back </a> -->
<?php echo $this->Html->css('jquery-ui'); ?>
<?php //echo $this->Html->script('src/SubletAdd'); ?>
<?php echo $this->Html->css('UploadImage3'); ?>
<?php echo $this->Html->css('ajax_add4'); ?>
<?php echo $this->Html->script('jquery.fileupload'); ?>
<?php echo $this->Html->script('jquery.iframe-transport'); ?>
<div class = 'photo-manager container-fluid'>
	<div class = 'row-fluid'>		
		<div id="leftColumn" class = 'span6'>
			<div id="topSection">
				<strong>Upload Images</strong>
				<br>
				<small>Please select an image you would like to upload.</small>
				<?php echo $this->Form->create("Image", array("type" => "file", "action" => "add"));?>
				  <legend><?php __("Add Image"); ?></legend>
				  <fieldset>
				  	<input class="fileInput" id="1" type="file" data-url="/images/add" onchange="A2Cribs.PhotoManager.PreviewImage(this)" data-sequential-uploads="true" data-form-data='{"script": "true"}'>
				  </fieldset>
				</form>
			</div>
			<div id="bottomSection">
				<div id="imageContent0">
					Click on an image to edit it
				</div>
				<div id="caption">
					<textarea type="text" id="captionInput" placeholder="Enter a Caption" onkeyup="A2Cribs.PhotoManager.CaptionKeyUp()" maxlength="25"></textarea>
					<span id="charactersLeft">25</span>
					<button class='btn' id="captionSubmit" onclick="A2Cribs.PhotoManager.SubmitCaption()">Save</button>
				</div>

				<a href="#" id="backToStep3">Back</a>
				<a href="#" id="goToStep5">Next</a>
			</div>
		</div>
		<div id="imagesWrapper" class = 'span6'>
			<div class="image-row row-fluid">
				<div class="imageContainer span6">
					<div class="imageContent imageThumb" id="imageContent1" onclick="A2Cribs.PhotoManager.EditImage(this)">No Image</div>
					<div class = 'image-actions-container'>
						<i class="delete hide icon-trash " id="delete1" onclick="A2Cribs.PhotoManager.DeleteImage(this)"></i>
						<i class="edit hide icon-edit" id="edit1" onclick="A2Cribs.PhotoManager.EditImage(this)"></i>
						<i class="primary hide icon-asterisk" id="primary1" onclick="A2Cribs.PhotoManager.MakePrimary(this)"></i>
					</div>
				</div>
				<div class="imageContainer span6">
					<div class="imageContent secondary imageThumb" id="imageContent2" onclick="A2Cribs.PhotoManager.EditImage(this)">No Image</div>
					<div class = 'image-actions-container'>
						<i class="delete hide icon-trash " id="delete2" onclick="A2Cribs.PhotoManager.DeleteImage(this)"></i>
						<i class="edit hide icon-edit" id="edit2" onclick="A2Cribs.PhotoManager.EditImage(this)"></i>
						<i class="primary hide icon-asterisk" id="primary2" onclick="A2Cribs.PhotoManager.MakePrimary(this)"></i>
					</div>
				</div>				
			</div>
			<div class="image-row row-fluid">
				<div class="imageContainer span6">
					<div class="imageContent imageThumb" id="imageContent3" onclick="A2Cribs.PhotoManager.EditImage(this)">No Image</div>
					<div class = 'image-actions-container'>
						<span>
						<i class="delete hide icon-trash " id="delete3" onclick="A2Cribs.PhotoManager.DeleteImage(this)"></i>
						<i class="edit hide icon-edit" id="edit3" onclick="A2Cribs.PhotoManager.EditImage(this)"></i>
						<i class="primary hide icon-asterisk" id="primary3" onclick="A2Cribs.PhotoManager.MakePrimary(this)"></i>
						</span>
					</div>
				</div>
				<div class="imageContainer span6">
					<div class="imageContent secondary imageThumb" id="imageContent4" onclick="A2Cribs.PhotoManager.EditImage(this)">No Image</div>
					<div class = 'image-actions-container'>
						<i class="delete hide icon-trash " id="delete4" onclick="A2Cribs.PhotoManager.DeleteImage(this)"></i>
						<i class="edit hide icon-edit" id="edit4" onclick="A2Cribs.PhotoManager.EditImage(this)"></i>
						<i class="primary hide icon-asterisk" id="primary4" onclick="A2Cribs.PhotoManager.MakePrimary(this)"></i>
					</div>
				</div>				
			</div>
			<div class="image-row row-fluid">
				<div class="imageContainer span6">
					<div class="imageContent imageThumb" id="imageContent5" onclick="A2Cribs.PhotoManager.EditImage(this)">No Image</div>
					<div class = 'image-actions-container'>
						<i class="delete hide icon-trash " id="delete5" onclick="A2Cribs.PhotoManager.DeleteImage(this)"></i>
						<i class="edit hide icon-edit" id="edit5" onclick="A2Cribs.PhotoManager.EditImage(this)"></i>
						<i class="primary hide icon-asterisk" id="primary5" onclick="A2Cribs.PhotoManager.MakePrimary(this)"></i>
					</div>
				</div>
				<div class="imageContainer span6">
					<div class="imageContent secondary imageThumb" id="imageContent6" onclick="A2Cribs.PhotoManager.EditImage(this)">No Image</div>
					<div class = 'image-actions-container'>
						<i class="delete hide icon-trash " id="delete6" onclick="A2Cribs.PhotoManager.DeleteImage(this)"></i>
						<i class="edit hide icon-edit" id="edit6" onclick="A2Cribs.PhotoManager.EditImage(this)"></i>
						<i class="primary hide icon-asterisk" id="primary6" onclick="A2Cribs.PhotoManager.MakePrimary(this)"></i>
					</div>
				</div>				
			</div>
		</div>
	</div>

	
</div>

<script>
	var a = A2Cribs.SubletAdd;
	a.setupUI();

$(function () {
    $('#ImageAddForm').fileupload({
    	dataType: 'json',
    	add: function(e, data) {
    		A2Cribs.PhotoManager.FindNextFreeDiv();
    		data.formData = {"imageSlot": A2Cribs.PhotoManager.NextImageSlot};
    		if (!data.formData.imageSlot)
    		{
    			A2Cribs.PhotoManager.NextImageSlot = 1;
    			data.formData.imageSlot = A2Cribs.PhotoManager.NextImageSlot;
    		}
    		
    		A2Cribs.PhotoManager.DebugData(data);
    		data.submit();
    		return false;
    	}
    });
});

$(document).ready(function(){

	A2Cribs.PhotoManager.LoadImages();
	A2Cribs.PhotoManager.setupUI();

});

$("#ImageAddForm").submit(function() {
	return false;
});

</script>