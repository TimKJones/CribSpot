<?= $this->Html->css('/less/photo_picker.less?','stylesheet/less', array('inline' => false)); ?>

<div id="photo_picker" class="row-fluid">
	<!-- PHOTO VIEWER -->
	<div class="span8">
		<div class="main_photo">
			<i class="icon-plus-sign"></i>
			<div class="photo_editor"></div>
		</div>
	</div>

	<!-- PHOTO LIST -->
	<div class="span4 photo_list">
		<div class="photo_preview">
			<div class="mini_photo pull-left"></div>
			<span class="photo_description">Hello image</span>
			<span class="is_primary"><i class="icon-star"></i></span>
		</div>
		<div class="photo_preview">
			<div class="mini_photo pull-left"></div>
			<span class="photo_description">Hello image</span>
			<span class="is_primary"><i class="icon-star"></i></span>
		</div>
		<div class="photo_preview">
			<div class="mini_photo pull-left"></div>
			<span class="photo_description">Hello image</span>
			<span class="is_primary"><i class="icon-star"></i></span>
		</div>
		<div class="photo_preview">
			<div class="mini_photo pull-left"></div>
			<span class="photo_description">Hello image</span>
			<span class="is_primary"><i class="icon-star"></i></span>
		</div>
	</div>
</div>