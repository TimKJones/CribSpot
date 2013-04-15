<!--<div> BLAH</div>
<a class="ajax" href="/sublets/ajax_add" id="gotoscreen2">Go back </a> -->
<?php echo $this->Html->css('jquery-ui'); ?>
<?php //echo $this->Html->script('src/SubletAdd'); ?>
<?php echo $this->Html->css('UploadImage3'); ?>
<?php echo $this->Html->css('ajax_add4'); ?>
<?php echo $this->Html->script('jquery.fileupload'); ?>
<?php echo $this->Html->script('jquery.iframe-transport'); ?>

	<div id="imagesWrapper">
		<div class="imageContainer">
			<div class="imageContent imageThumb topRow" id="imageContent1" onclick="A2Cribs.PhotoManager.EditImage(this)">No Photo Selected</div>
			<button class="delete hide" id="delete1" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Remove</button>
			<button class="edit hide" id="edit1" onclick="A2Cribs.PhotoManager.EditImage(this)">Edit</button>
			<button class="primary hide" id="primary1" onclick="A2Cribs.PhotoManager.MakePrimary(this)">*</button>
		</div>
		<div class="imageContainer">
			<div class="imageContent secondary imageThumb topRow" id="imageContent2" onclick="A2Cribs.PhotoManager.EditImage(this)">No Photo Selected</div>
			<button class="delete hide" id="delete2" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Remove</button>
			<button class="edit hide" id="edit2" onclick="A2Cribs.PhotoManager.EditImage(this)">Edit</button>
			<button class="primary hide" id="primary2" onclick="A2Cribs.PhotoManager.MakePrimary(this)">*</button>
		</div>
		<div class="imageContainer">
			<div class="imageContent secondary imageThumb" id="imageContent3" onclick="A2Cribs.PhotoManager.EditImage(this)">No Photo Selected</div>
			<button class="delete hide" id="delete3" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Remove</button>
			<button class="edit hide" id="edit3" onclick="A2Cribs.PhotoManager.EditImage(this)">Edit</button>
			<button class="primary hide" id="primary3" onclick="A2Cribs.PhotoManager.MakePrimary(this)">*</button>
		</div>
		<div class="imageContainer">
			<div class="imageContent secondary imageThumb" id="imageContent4" onclick="A2Cribs.PhotoManager.EditImage(this)">No Photo Selected</div>
			<button class="delete hide" id="delete4" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Remove</button>
			<button class="edit hide" id="edit4" onclick="A2Cribs.PhotoManager.EditImage(this)">Edit</button>
			<button class="primary hide" id="primary4" onclick="A2Cribs.PhotoManager.MakePrimary(this)">*</button>
		</div>
		<div class="imageContainer">
			<div class="imageContent secondary imageThumb" id="imageContent5" onclick="A2Cribs.PhotoManager.EditImage(this)">No Photo Selected</div>
			<button class="delete hide" id="delete5" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Remove</button>
			<button class="edit hide" id="edit5" onclick="A2Cribs.PhotoManager.EditImage(this)">Edit</button>
			<button class="primary hide" id="primary5" onclick="A2Cribs.PhotoManager.MakePrimary(this)">*</button>
		</div>
		<div class="imageContainer">
			<div class="imageContent secondary imageThumb" id="imageContent6" onclick="A2Cribs.PhotoManager.EditImage(this)">No Photo Selected</div>
			<button class="delete hide" id="delete6" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Remove</button>
			<button class="edit hide" id="edit6" onclick="A2Cribs.PhotoManager.EditImage(this)">Edit</button>
			<button class="primary hide" id="primary6" onclick="A2Cribs.PhotoManager.MakePrimary(this)">*</button>
		</div>
		</div>
	</div>
	<div id="leftColumn">
		<div id="topSection">
			<div id="topSectionTop">
				<?php echo $this->Form->create("Image", array("type" => "file", "action" => "add", "enctype" => "multipart/form-data"));?>
				  <legend><?php __("Add Image"); ?></legend>
				  <fieldset>
				  	<input class="fileInput" id="1" type="file" data-url="/Images/add" onchange="A2Cribs.PhotoManager.PreviewImage(this)" data-sequential-uploads="false" data-form-data='{"script": "true"}'>
				  </fieldset>
				</form>
			</div>
		</div>
		<div id="bottomSection">
			<div id="imageContent0">
				
			</div>
			<div id="caption">
				Name: <input type="text" id="captionInput" placeholder="Edit picture name" onkeyup="A2Cribs.PhotoManager.CaptionKeyUp()" maxlength="25">
				<span id="charactersLeft">25</span>
				<button id="captionSubmit" onclick="A2Cribs.PhotoManager.SubmitCaption()">GO</button>
			</div>
		</div>
	</div>
	<a href="#" id="backToStep3">Back</a>
	<a href="#" id="goToStep5" style="float:right">Next</a>
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
});

$("#ImageAddForm").submit(function() {
	return false;
});

</script>