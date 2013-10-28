<div class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Invite my friends by Email</h3>
	</div>
	<div class="modal-body" class="fluid-container">
		<div>
		<?php
		for ($i=0; $i < 3; $i++) { 
			echo '
			<div class="row-fluid">
				<input class="span6" type="text" placeholder="Name">
				<input class="span6" type="email" placeholder="Email">
			</div>
			';
		}
		?>
		</div>
		<div class="row-fluid">
			<a id="add_invitee_email" class="pull-right" href="#"><i class="icon-plus-sign icon-large"></i> Add another invitee</a>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-primary">Invite</a>
	</div>
</div>
