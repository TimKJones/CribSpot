<div id="photo_content" class="tab-pane active">
	<div class="large_image_container">
		<?php
		$primary_url = 'img/full_page/no_photo.jpg';
		$has_primary_photo = false;
		if (array_key_exists('primary_image', $listing) && array_key_exists('Image', $listing)) {
			$primary_url = $listing["Image"][$listing["primary_image"]]["image_path"];
			$has_primary_photo = true;
		}
		?>
		<div id="main_photo" class="<?= ($has_primary_photo) ? '' : 'no_photo' ; ?>" style="background-image:url(/<?= $primary_url ?>)"></div>
	</div>
	<div class="image_footer">
		<div class="page_left"><i class="icon-chevron-left icon-large"></i></div>
		<div class="image_preview_container">
			<?php
			$length = count($listing["Image"]);
			for ($i=0; $i < $length; $i++) { 
				if ($listing["primary_image"] == $i)
					echo '<div class="image_preview active" style="background-image:url(/' . $listing["Image"][$i]["image_path"] . ')"></div>';
				else
					echo '<div class="image_preview" style="background-image:url(/' . $listing["Image"][$i]["image_path"] . ')"></div>';
			}
			?>
		</div>
		<div class="page_right"><i class="icon-chevron-right icon-large"></i></div>
	</div>
</div>