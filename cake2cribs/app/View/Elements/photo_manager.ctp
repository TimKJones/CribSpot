
<?php echo $this->Html->css('jquery-ui'); ?>
<?php echo $this->Html->css('photomanager'); ?>
<?php echo $this->Html->script('jquery.fileupload'); ?>
<?php echo $this->Html->script('jquery.iframe-transport'); ?>


<div class = 'photo-manager'>
    <div class = 'row-fluid'>       
        <div id="leftColumn" class = 'span6'>
            <div id="topSection">
                <div class ='upload-field'>
                    <button id='upload_image' class = 'btn btn-primary'><i class = 'icon-plus-sign'></i> Upload Photos</button>
                </div>
                <div id ='explanation-text'>
                    <strong>Don't have any photos at the moment?</strong>
                    <br>
                    <small>You can always edit your listing by visiting the dashboard</small>
                </div>
                <form action="/images/add" id="ImageAddForm" enctype="multipart/form-data" method="post" accept-charset="utf-8"><div style="display:none;"><input type="hidden" name="_method" value="POST"></div>
                  <fieldset>
                    <input id = 'real-file-input' class="fileInput" id="1" type="file" data-url="/images/add" onchange="A2Cribs.PhotoManager.PreviewImage(this)" data-sequential-uploads="true" data-form-data="{&quot;script&quot;: &quot;true&quot;}">
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
                        <input type="text" id="captionInput" placeholder="Enter a Caption" onkeyup="A2Cribs.PhotoManager.CaptionKeyUp()" maxlength="25"></input>
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
                    <div class="imageContent imageThumb" id="imageContent1" onclick="A2Cribs.PhotoManager.EditImage(this)"><div class ='img-place-holder'></div></div>
                    <div class = 'image-actions-container'>
                        <i class="delete icon-trash " id="delete1" onclick="A2Cribs.PhotoManager.DeleteImage(this)"></i>
                        <i class="edit icon-edit" id="edit1" onclick="A2Cribs.PhotoManager.EditImage(this)"></i>
                        <i class="primary icon-asterisk" id="primary1" onclick="A2Cribs.PhotoManager.MakePrimary(this)"></i>
                    </div>
                </div>
                <div class="imageContainer span5">
                    <div class="imageContent secondary imageThumb" id="imageContent2" onclick="A2Cribs.PhotoManager.EditImage(this)"><div class ='img-place-holder'></div></div>
                    <div class = 'image-actions-container'>
                        <i class="delete icon-trash " id="delete2" onclick="A2Cribs.PhotoManager.DeleteImage(this)"></i>
                        <i class="edit icon-edit" id="edit2" onclick="A2Cribs.PhotoManager.EditImage(this)"></i>
                        <i class="primary icon-asterisk" id="primary2" onclick="A2Cribs.PhotoManager.MakePrimary(this)"></i>
                    </div>
                </div>              
            </div>
            <div class="image-row row-fluid">
                <div class="imageContainer span5 offset1">
                    <div class="imageContent imageThumb" id="imageContent3" onclick="A2Cribs.PhotoManager.EditImage(this)"><div class ='img-place-holder'></div></div>
                    <div class = 'image-actions-container'>
                        <span>
                        <i class="delete icon-trash " id="delete3" onclick="A2Cribs.PhotoManager.DeleteImage(this)"></i>
                        <i class="edit icon-edit" id="edit3" onclick="A2Cribs.PhotoManager.EditImage(this)"></i>
                        <i class="primary icon-asterisk" id="primary3" onclick="A2Cribs.PhotoManager.MakePrimary(this)"></i>
                        </span>
                    </div>
                </div>
                <div class="imageContainer span5">
                    <div class="imageContent secondary imageThumb" id="imageContent4" onclick="A2Cribs.PhotoManager.EditImage(this)"><div class ='img-place-holder'></div></div>
                    <div class = 'image-actions-container'>
                        <i class="delete icon-trash " id="delete4" onclick="A2Cribs.PhotoManager.DeleteImage(this)"></i>
                        <i class="edit icon-edit" id="edit4" onclick="A2Cribs.PhotoManager.EditImage(this)"></i>
                        <i class="primary icon-asterisk" id="primary4" onclick="A2Cribs.PhotoManager.MakePrimary(this)"></i>
                    </div>
                </div>              
            </div>
            <div class="image-row row-fluid">
                <div class="imageContainer span5 offset1">
                    <div class="imageContent imageThumb" id="imageContent5" onclick="A2Cribs.PhotoManager.EditImage(this)"><div class ='img-place-holder'></div></div>
                    <div class = 'image-actions-container'>
                        <i class="delete icon-trash " id="delete5" onclick="A2Cribs.PhotoManager.DeleteImage(this)"></i>
                        <i class="edit icon-edit" id="edit5" onclick="A2Cribs.PhotoManager.EditImage(this)"></i>
                        <i class="primary icon-asterisk" id="primary5" onclick="A2Cribs.PhotoManager.MakePrimary(this)"></i>
                    </div>
                </div>
                <div class="imageContainer span5">
                    <div class="imageContent secondary imageThumb" id="imageContent6" onclick="A2Cribs.PhotoManager.EditImage(this)"><div class ='img-place-holder'></div></div>
                    <div class = 'image-actions-container'>
                        <i class="delete icon-trash " id="delete6" onclick="A2Cribs.PhotoManager.DeleteImage(this)"></i>
                        <i class="edit icon-edit" id="edit6" onclick="A2Cribs.PhotoManager.EditImage(this)"></i>
                        <i class="primary icon-asterisk" id="primary6" onclick="A2Cribs.PhotoManager.MakePrimary(this)"></i>
                    </div>
                </div>              
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        A2Cribs.PhotoManager.SetupUI();
    });
    </script>