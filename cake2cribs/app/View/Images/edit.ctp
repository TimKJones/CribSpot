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
        <input type="hidden" id="imageSlot">
          <legend><?php __("Add Image"); ?></legend>
          <fieldset>
            <input class="fileInput" id="1" type="file" data-url="/Images/add" onchange="A2Cribs.PhotoManager.PreviewImage(this)" data-sequential-uploads="false" data-form-data='{"script": "true"}'>
            
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

// Initialize file upload plugin
$(function () {
  $('#ImageAddForm').fileupload({
    singleFileUploads: true,
    url: "/images/add"
  });
});

$('#ImageAddForm').bind('fileuploadsubmit', function (e, data) {
  //alert("submit event");
    /*var id = $('.fileInput').attr("id");
    data.formData = {targetPhoto: "69"};
    if (!data.formData.example) {
      input.focus();
      return false;
    }*/
});

$(function () {
    $('#ImageAddForm').fileupload({
        dataType: 'json',
        done: function (e, data) {
        },
        progressall: function (e, data) {
          var progress = parseInt(data.loaded / data.total * 100, 10);
          $('#progress .bar').css(
              'width',
              progress + '%'
          );
      },
      add: function(e, data) {
        A2Cribs.PhotoManager.FindNextFreeDiv();
        data.formData = {"imageSlot": A2Cribs.PhotoManager.NextImageSlot};
      if (!data.formData.imageSlot) 
          return false;

      data.submit();
    },
      submit: function (e, data) {
        
      }
    });
});

$('#ImageAddForm').bind('fileuploadsubmit', function (e, data) {
    
});
</script>
</body> 
</html>

<?php
$this->Js->buffer(  
    'A2Cribs.PhotoManager.LoadImages();
    var options = {
      beforeSubmit:  A2Cribs.PhotoManager.ShowRequest,
      success:       A2Cribs.PhotoManager.ShowResponse,
      url:       "Images/Edit/" + jsVars.edit_listing_id,  // your upload script
      dataType:  "json"
    };
  ');

?>