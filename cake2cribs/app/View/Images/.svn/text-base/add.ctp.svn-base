<?php echo $this->Html->css('UploadImage'); ?>
<body>
	<div id="container">
  <?php if ($errors) print_r($errors); ?>
  <?php echo $this->Form->create("Image", array("type" => "file", "action" => "add"));?>
  <legend><?php __("Add Image"); ?></legend>
  <fieldset>
    <center><h3>Primary Image</h3><center>
  	<div class="imageContent" id="imageContent1">No Photo Selected</div><br/>
    <?php echo $this->Form->input("primary", array("id" => "add1", "type" => "hidden", "onchange" => "A2Cribs.PhotoManager.PreviewImage(this)")); ?>
    <center><h3>Secondary Images</h3><center>
    <div id="secondaryImagesContainer">
      <div class="imageContainer">
        <div class="imageContent secondary" id="imageContent2">No Photo Selected</div>
        <?php echo $this->Form->input("secondary_1", array("id" => "add2", "type" => "file", "onchange" => "A2Cribs.PhotoManager.PreviewImage(this)")); ?>
      </div>
      <div class="imageContainer">
        <div class="imageContent secondary" id="imageContent3">No Photo Selected</div>
        <?php echo $this->Form->input("secondary_2", array("id" => "add3", "type" => "file", "onchange" => "A2Cribs.PhotoManager.PreviewImage(this)")); ?>
      </div>
      <div class="imageContainer">
        <div class="imageContent secondary" id="imageContent4">No Photo Selected</div>
        <?php echo $this->Form->input("secondary_3", array("id" => "add4", "type" => "file", "onchange" => "A2Cribs.PhotoManager.PreviewImage(this)")); ?>
      </div>
    </div>
  </fieldset>
  <center><button id="submitButton" onclick="A2Cribs.PhotoManager.SubmitPhoto();">Submit</button></center>
	</div>
</body>