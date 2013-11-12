<div class="row-fluid">
		<div class="span12 info_label">
			Included:
		</div>
	</div>
<?php if (array_key_exists('Rental', $listing)) { ?>
	<div class="row-fluid">
		<div class="span12">
			<ul class="large_included_list">
				<?php
					$included = array('electric', 'gas', 'water', 'cable', 'internet', 'trash');
					$descriptions = array('Electricity', 'Gas', 'Water', 'Cable', 'Internet', 'Trash');

					$length = count($included);

					for ($i=0; $i < $length; $i++) { 
						if (intval($listing["Rental"][$included[$i]]) > 0)
							echo '<li><img src="/img/full_page/large_' . $included[$i] . '.png">' . $descriptions[$i] . '</li>';
						//else if (strcmp($listing["Rental"][$included[$i]], "Yes") == 0)
						//	echo '<li><img src="/img/listings/large_' . $included[$i] . '.png">' . $descriptions[$i] . '</li>';
						else
							echo '<li class="disabled"><img src="/img/full_page/large_' . $included[$i] . '_not_included.png">' . $descriptions[$i] . '</li>';
					}
				?>
			</ul>
		</div>
	</div>
<?php } ?>