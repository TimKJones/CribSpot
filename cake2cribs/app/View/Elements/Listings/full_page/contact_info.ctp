<div class="row-fluid contact_info">
	<div class="span12">
		<div class="row-fluid">
			<div class="span12 owner_info">
				<?
				$pic_url = "/img/head_large.jpg";
				if (array_key_exists('profile_img', $listing['User']) && !empty($listing['User']['profile_img']))
				{
					$pic_url = "/" . $listing['User']['profile_img'];
				}
				else if(array_key_exists('facebook_userid', $listing['User']) && !empty($listing['User']['facebook_userid']))
				{
					$pic_url = "https://graph.facebook.com/".$listing['User']['facebook_userid']."/picture?width=80&height=80";
				}
				?>
				<img src="<?= $pic_url ?>" class="pull-left">
<?php if (array_key_exists('Rental', $listing)) { ?>
				<div class="owner"><?= $listing["User"]["company_name"] ?></div>
<?php } else if (array_key_exists('Sublet', $listing)) { ?>
				<div class="owner"><?= $listing["User"]["first_name"] ?></div>
<?php } ?>
				<?= ($listing["User"]["verified"]) ? '<div class="verified">VERIFIED</div>' : '' ; ?>
			</div>
		</div>
		<div class="row-fluid hide" id="contact_message">	
			<?php
			if (array_key_exists('Rental', $listing) && 
				array_key_exists('contact_phone', $listing['Rental']) && $listing["Rental"]["contact_phone"] != null
				&& $messaging_enabled)
			{ ?>
				<div class="row-fluid phone">
					Phone Number: <?= $listing["Rental"]["contact_phone"] ?>
				</div>
			<?php } else if (array_key_exists('Rental', $listing) && $messaging_enabled) {?>
				<div class="row-fluid phone">
					Phone Number: Not Available
				</div>
			<? } ?>
			<?php if ($email_exists) { ?>
			<div class="row-fluid">
				<textarea id="message_area" class="span12" rows="4">Hello, I found your listing on Cribspot and would like to find out more about this property. Please let me know when you are available for a viewing. Thank you.</textarea>
			</div>
			<div class="row-fluid">
				<button id="message_cancel" class="btn span5">Cancel</button>
				<button id="message_send" class="btn span7" type="button" data-loading-text="Sending...">Send Message</button>
			</div>
			<div class="row-fluid">
				<div class="info_message">Responses will be sent to you current email, <strong class="user_email"></strong>. If this is incorrect, click <?php echo $this->Html->link('here', array('controller' => 'users', 'action' => 'accountinfo')); ?> to set your email</div>
			</div>
			<?php } else if (array_key_exists('contact_phone', $listing['Rental']) && $listing["Rental"]["contact_phone"] != null) {?>
				<b>This rental owner can only be contacted by phone.</b>
			<?php } ?>
			<?php if (!$messaging_enabled) { ?>
				<b>No contact information is available for this property.</b>
			<?php } ?>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<button class="btn" id="contact_owner" emailExists='<?php echo $email_exists; ?>'>CONTACT RENTAL OWNER</button>
			</div>
		</div>
		<?php if ($listing['Listing']['available'] === true){ ?>
		<div class="row-fluid">
			<div class="span12">
				<button class="btn show_scheduling" >REQUEST TOUR NOW</button>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
