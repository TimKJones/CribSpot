<?php echo $this->Html->css('photomanager.css?v=2', null, array('inline' => false)); ?>
<?php 
	echo $this->Html->script('fileupload/load-image.min.js', array('inline' => false));
	echo $this->Html->script('fileupload/jquery.iframe-transport', array('inline' => false));
	echo $this->Html->script('fileupload/jquery.fileupload.js', array('inline' => false));
	echo $this->Html->script('fileupload/jquery.fileupload-process', array('inline' => false));
	echo $this->Html->script('fileupload/jquery.fileupload-image', array('inline' => false));
	echo $this->Html->script('fileupload/jquery.fileupload-validate', array('inline' => false));
	
	if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
		echo $this->Html->script('src/PhotoPicker', array('inline' => false));
	}
?>


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
				<form action="/images/add" id="ImageAddForm" enctype="multipart/form-data" method="post" accept-charset="utf-8"><div style="display:none;"><input type="hidden" name="_method" value="POST"></div>
				  <fieldset>
					<input id = 'real-file-input' class="fileInput" id="1" type="file" data-url="/images/add" data-sequential-uploads="true" data-form-data="{&quot;script&quot;: &quot;true&quot;}">
				  </fieldset>
				</form>

			</div>
			<div id="bottomSection">
				<div id ='largeViewContainer'>
					<div id="imageContent0">
						<div class = 'img-place-holder'></div>
					</div>
					 <div id="caption">
						<input type="text" id="captionInput" placeholder="Enter a Caption" maxlength="25"></input>
						<span id="charactersLeft">25</span>
					</div>
				</div>
			   
			</div>
		</div>
		<div id="imagesWrapper" class = 'span6'>
			<div class="image-row row-fluid">
			</div>
		</div>
	</div>
</div>
