<!DOCTYPE HTML>
<?php echo $this->Html->css('UploadImage3'); ?>
<?php echo $this->Html->script('jquery.fileupload'); ?>
<?php echo $this->Html->script('jquery.iframe-transport'); ?>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<div id="progress">
    <div id="progressBar" class="bar" style="width: 0%;"></div>
</div>
	<div id="imagesWrapper">
		<div class="imageContainer">
			<div class="imageContent primary imageThumb topRow" id="imageContent1" onclick="A2Cribs.PhotoManager.EditImage(this)">No Photo Selected</div>
			<button class="delete" id="delete1" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Remove</button>
			<button class="edit" id="edit1" onclick="A2Cribs.PhotoManager.EditImage(this)">Edit</button>
		</div>
		<div class="imageContainer">
			<div class="imageContent secondary imageThumb topRow" id="imageContent2" onclick="A2Cribs.PhotoManager.EditImage(this)">No Photo Selected</div>
			<button class="delete" id="delete2" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Remove</button>
			<button class="edit" id="edit2" onclick="A2Cribs.PhotoManager.EditImage(this)">Edit</button>
		</div>
		<div class="imageContainer">
			<div class="imageContent secondary imageThumb" id="imageContent3" onclick="A2Cribs.PhotoManager.EditImage(this)">No Photo Selected</div>
			<button class="delete" id="delete3" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Remove</button>
			<button class="edit" id="edit3" onclick="A2Cribs.PhotoManager.EditImage(this)">Edit</button>
		</div>
		<div class="imageContainer">
			<div class="imageContent secondary imageThumb" id="imageContent4" onclick="A2Cribs.PhotoManager.EditImage(this)">No Photo Selected</div>
			<button class="delete" id="delete4" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Remove</button>
			<button class="edit" id="edit4" onclick="A2Cribs.PhotoManager.EditImage(this)">Edit</button>
		</div>
		<div class="imageContainer">
			<div class="imageContent secondary imageThumb" id="imageContent5" onclick="A2Cribs.PhotoManager.EditImage(this)">No Photo Selected</div>
			<button class="delete" id="delete5" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Remove</button>
			<button class="edit" id="edit5" onclick="A2Cribs.PhotoManager.EditImage(this)">Edit</button>
		</div>
		<div class="imageContainer">
			<div class="imageContent secondary imageThumb" id="imageContent6" onclick="A2Cribs.PhotoManager.EditImage(this)">No Photo Selected</div>
			<button class="delete" id="delete6" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Remove</button>
			<button class="edit" id="edit6" onclick="A2Cribs.PhotoManager.EditImage(this)">Edit</button>
		</div>
		</div>
	</div>
	<div id="leftColumn">
		<div id="topSection">
			<div id="topSectionTop">
				<?php echo $this->Form->create("Image", array("type" => "file", "action" => "add"));?>
				  <legend><?php __("Add Image"); ?></legend>
				  <fieldset>
				  	<input class="fileInput" id="secondary" type="file" name="files[]" data-url="/images/add" onchange="A2Cribs.PhotoManager.PreviewImage(this)" multiple>
				  </fieldset>
				</form>
			</div>
			<div id="topSectionBottom">
				Don't have photos at the moment? ....
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
<script>
$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });
        },
        progressall: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .bar').css(
            'width',
            progress + '%'
        );
    }
    });
});
</script>
</body> 
</html>