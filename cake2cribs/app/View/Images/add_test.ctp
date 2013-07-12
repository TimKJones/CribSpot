<?php echo $this->Html->css('photomanager', null, array('inline' => false)); ?>
<?php echo $this->Html->script('jquery.fileupload'); ?>
<?php echo $this->Html->script('jquery.iframe-transport'); ?>
<?php echo $this->Html->script('src/SubletSave'); ?>
<?php echo $this->Html->script('src/PhotoManager'); ?>

<body>
<div class = 'photo-manager'>
    <div class = 'row-fluid'>       
        <div id="leftColumn" class = 'span6'>
            <div id="topSection">
                <div class ='upload-field'>
                    <button id='upload_image' class = 'btn btn-primary' data-loading-text="<i class='icon-spinner icon-spin icon-large'></i> Uploading..."><i class = 'icon-plus-sign'></i> Upload Photos</button>
                </div>
                <div id ='explanation-text'>
                    <strong>Don't have any photos at the moment?</strong>
                    <br>
                    <small>You can always edit your listing by visiting the dashboard</small>
                </div>
                <form action="/images/AddImage" id="ImageAddForm" enctype="multipart/form-data" method="post" accept-charset="utf-8"><div style="display:none;"><input type="hidden" name="_method" value="POST"></div>
                  <fieldset>
                    <input style='width:186px;height:30px' id = 'real-file-input' class="fileInput" id="1" type="file" data-url="/images/AddImage" data-sequential-uploads="true" data-form-data="{&quot;script&quot;: &quot;true&quot;}">
                    <input type="hidden" name="row_id" value="0">
                    <input type="hidden" name="num_images" value="1">
                  </fieldset>
                </form>

            </div>
            <div id="bottomSection">
                <div id ='largeViewContainer'>
                    <div id="imageContent0">
                        <div class = 'img-place-holder'></div>
                    </div>
                     <div id="caption">
                    <div class="input-append">
                        <input type="text" id="captionInput" placeholder="Enter a Caption" maxlength="25"></input>
                        <span class="add-on"><i id='saveCaption' class="icon-save"></i></span>
                    </div>
                    <span id="charactersLeft">25</span>
                </div>
                </div>
               
            </div>
        </div>
        <div id="imagesWrapper" class = 'span6'>
            <div class="image-row row-fluid">
                <div class="imageContainer span5 offset1">
                    <div class="imageContent imageThumb" id="imageContent1"><div class ='img-place-holder'></div></div>
                    <div class = 'image-actions-container'>
                        <i class="delete icon-trash " id="delete1"></i>
                        <i class="edit icon-edit" id="edit1"></i>
                        <i class="primary icon-asterisk" id="primary1"></i>
                    </div>
                </div>
                <div class="imageContainer span5">
                    <div class="imageContent secondary imageThumb" id="imageContent2"><div class ='img-place-holder'></div></div>
                    <div class = 'image-actions-container'>
                        <i class="delete icon-trash " id="delete2"></i>
                        <i class="edit icon-edit" id="edit2"></i>
                        <i class="primary icon-asterisk" id="primary2"></i>
                    </div>
                </div>              
            </div>
            <div class="image-row row-fluid">
                <div class="imageContainer span5 offset1">
                    <div class="imageContent imageThumb" id="imageContent3"><div class ='img-place-holder'></div></div>
                    <div class = 'image-actions-container'>
                        <span>
                        <i class="delete icon-trash " id="delete3"></i>
                        <i class="edit icon-edit" id="edit3"></i>
                        <i class="primary icon-asterisk" id="primary3"></i>
                        </span>
                    </div>
                </div>
                <div class="imageContainer span5">
                    <div class="imageContent secondary imageThumb" id="imageContent4"><div class ='img-place-holder'></div></div>
                    <div class = 'image-actions-container'>
                        <i class="delete icon-trash " id="delete4"></i>
                        <i class="edit icon-edit" id="edit4"></i>
                        <i class="primary icon-asterisk" id="primary4"></i>
                    </div>
                </div>              
            </div>
            <div class="image-row row-fluid">
                <div class="imageContainer span5 offset1">
                    <div class="imageContent imageThumb" id="imageContent5"><div class ='img-place-holder'></div></div>
                    <div class = 'image-actions-container'>
                        <i class="delete icon-trash " id="delete5"></i>
                        <i class="edit icon-edit" id="edit5"></i>
                        <i class="primary icon-asterisk" id="primary5"></i>
                    </div>
                </div>
                <div class="imageContainer span5">
                    <div class="imageContent secondary imageThumb" id="imageContent6"><div class ='img-place-holder'></div></div>
                    <div class = 'image-actions-container'>
                        <i class="delete icon-trash " id="delete6"></i>
                        <i class="edit icon-edit" id="edit6"></i>
                        <i class="primary icon-asterisk" id="primary6"></i>
                    </div>
                </div>              
            </div>
        </div>
    </div>
</div>
<script>
$('#ImageAddForm').fileupload({
        url: '/images/AddImage',
        dataType: 'json',
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        singleFileUploads: true,
        maxFileSize: 5000000,
        loadImageMaxFileSize: 15000000,
        disableImageResize: false,
        previewMaxWidth: 100,
        previewMaxHeight: 100,
        previewCrop: true
      });
</script>
</body>