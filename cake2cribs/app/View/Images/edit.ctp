<?php echo $this->Html->css('UploadImage'); ?>
<body>
  <div id="container">
    <center><h3>Primary Image</h3><center>
    <div id="primaryImagesContainer" id="imageContainer1">
      <div class="imageContent" id="imageContent1"></div><br/>
      <?php echo $this->Form->create("Image", array("id" => "EditPrimaryForm", "type" => "file", "action" => "edit/" . $listing_id));?>
        <legend><?php __("Edit Image"); ?></legend>
        <fieldset>
          <?php echo $this->Form->input("primary", array("id" => "add1", "type" => "file", "onchange" => "A2Cribs.PhotoManager.PreviewImage(this)")); ?>
          <button type="button" class="deleteButton" id="1" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Delete</button>
          <button type="button" class="confirmButton" id="1confirm" onclick="A2Cribs.PhotoManager.ConfirmAddImage(this)">Confirm</button>
        </fieldset>
      </div>
    <center><h3>Secondary Images</h3><center>
    <div id="secondaryImagesContainer">
      <div class="imageContainer" id="imageContainer2">
        <div class="imageContent secondary" id="imageContent2">No Photo Selected</div>
        <?php echo $this->Form->create("Image", array("id" => "EditSecondary1Form", "type" => "file", "action" => "edit/" . $listing_id));?>
          <legend><?php __("Edit Image"); ?></legend>
          <fieldset>
            <?php echo $this->Form->input("secondary_1", array("id" => "add2", "type" => "file", "onchange" => "A2Cribs.PhotoManager.PreviewImage(this)")); ?>
            <button type="button" class="deleteButton" id="2" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Delete</button>
            <button type="button" class="confirmButton" id="2confirm" onclick="A2Cribs.PhotoManager.ConfirmAddImage(this)">Confirm</button>
          </fieldset>
      </div>
      <div class="imageContainer" id="imageContainer3">
        <div class="imageContent secondary" id="imageContent3">No Photo Selected</div>
        <?php echo $this->Form->create("Image", array("id" => "EditSecondary2Form", "type" => "file", "action" => "edit/" . $listing_id));?>
          <legend><?php __("Edit Image"); ?></legend>
          <fieldset>
            <?php echo $this->Form->input("secondary_2", array("id" => "add3", "type" => "file", "onchange" => "A2Cribs.PhotoManager.PreviewImage(this)")); ?>
            <button type="button" class="deleteButton" id="3" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Delete</button>
            <button type="button" class="confirmButton" id="3confirm" onclick="A2Cribs.PhotoManager.ConfirmAddImage(this)">Confirm</button>
          </fieldset>
      </div>
      <div class="imageContainer" id="imageContainer4">
        <div class="imageContent secondary" id="imageContent4">No Photo Selected</div>
        <?php echo $this->Form->create("Image", array("id" => "EditSecondary3Form", "type" => "file", "action" => "edit/" . $listing_id));?>
          <legend><?php __("Edit Image"); ?></legend>
          <fieldset>
            <?php echo $this->Form->input("secondary_3", array("id" => "add4", "type" => "file", "onchange" => "A2Cribs.PhotoManager.PreviewImage(this)")); ?>
            <button type="button" class="deleteButton" id="4" onclick="A2Cribs.PhotoManager.DeleteImage(this)">Delete</button>
            <button type="button" class="confirmButton" id="4confirm" onclick="A2Cribs.PhotoManager.ConfirmAddImage(this)">Confirm</button>
          </fieldset>
      </div>
    </div>
  </div>
  </div>
</body>

<?php
$this->Js->buffer(  
    'A2Cribs.PhotoManager.LoadImages();
    var options = {
      beforeSubmit:  A2Cribs.PhotoManager.ShowRequest,
      success:       A2Cribs.PhotoManager.ShowResponse,
      url:       "Images/Edit/" + jsVars.edit_listing_id,  // your upload script
      dataType:  "json"
    };
    $("#EditPrimaryForm").submit(function() {
      $(this).ajaxSubmit(options);
      return false;
    });
    $("#EditSecondary1Form").submit(function() {
      $(this).ajaxSubmit(options);
      return false;
    });
    $("#EditSecondary2Form").submit(function() {
      $(this).ajaxSubmit(options);
      return false;
    });
    $("#EditSecondary3Form").submit(function() {
      $(this).ajaxSubmit(options);
      return false;
    });'
  );

?>