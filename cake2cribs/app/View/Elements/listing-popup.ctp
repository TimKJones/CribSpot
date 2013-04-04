<?= $this->Html->css('/less/listing-popup.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('listing-popup-verifications'); ?>

<div class="listing-popup modal hide fade">
	<div id="sublet-id" class="hide"></div>
	<div class="modal-header">
		<i class="sublet-name title">Willow Tree Apartments</i>
		<div class="btn-group">
			<button type="button" id="overview-btn" class="btn btn-primary active btn-active">Overview</button>
			<button type="button" id="details-btn" class="btn">Details</button>
			<button type="button" id="contact-btn" class="btn">Contact</button>
			<button type="button" id="favorite-btn" class="btn"><i class="icon-heart icon-large favorite-clickable"></i></button>
		</div>
		<div class="share-buttons">
			<a href="#" class="facebook-share"><div></div></a>
			<a href="#" target="_blank" class="twitter-share"><div></div></a>
		</div>
		<div id="modal-close-button" class="close" data-dismiss="modal"></div>
	</div>
	<div class="photo-region">
		<div id="main-photo" style="background-image:url(/img/landing/house1.jpg);"></div>
		<div id="photo-description">No Photos Available</div>
		<div class="photos">
			<a href="#" class="preview-thumbnail" style="background-image:url(/img/landing/house2.jpg);"></a>
		</div>
	</div>
	<div id="overview" class="side-pane">
		<div class="large-line-height">
			<i class="medium-text">Asking Price:</i>
			<br>
			<table>
				<tr>
					<td>
						<i class="large-text green-text">$</i><i class="bed-price large-text green-text">1,050</i><i class="medium-text">/m</i>
					</td>
					<td>
						<i class="large-text">1</i> <i class="medium-text">Bed</i>
					</td>
				</tr>
			</table>
		</div>
		<div class="medium-line-height">
			<i class="small-text">Available From:</i>
			<br>
			<i class="medium-text full-date">March 13, 2013 to August 25, 2013</i>
			<br>
			<i class="small-text">*Dates are Flexible</i>
		</div>
		<div class="medium-line-height margin-above">
			<i class="small-text">Building Type:</i>
			<br>
			<i class="med-small-text building-type">Apartment</i>
		</div>
		<div class="medium-line-height margin-above">
			<a href="" class="thumbnail">
				<img src="/img/head_small.jpg" alt="No picture" id="tooltipUserPicture">
			</a>
			<div class="post-info ellipsis">
				<i class="small-text">Posted By:</i>
				<br>
				<i class="medium-text first-name">Michael</i>
				<br>
				<i class="med-small-text school-name">University of Michigan</i>
				<br>
				<i class="small-text">Posted: </i><i class="small-text">2 Days Ago</i>
			</div>			
		</div>
		<table class="margin-above">
			<tr>
				<td>
					<div class="small-line-height inline float-left">
						<i class="med-small-text">Facebook</i>
						<br>
						<i class="small-text facebookFriendLabel">Mutual Friends:</i>
					</div>
					<i class="med-large-text large-line-height numFacebookFriends">--</i>
				</td>
				<td>
					<div class="small-line-height inline float-left">
						<i class="med-small-text">Twitter</i>
						<br>
						<i class="small-text" >Followers:</i>
					</div>
					<i class="med-large-text large-line-height numTwitterFollowers">--</i>					
				</td>
			</tr>
		</table>


		<div class="short-description">
			<h1>Description:</h1>
			<p>This is my house and I think it's awesome. There is a pool in the back and lots of showers. I really love showering and trees.</p>
		</div>
		<i class="icon-map-marker icon-large"></i><i class="full-address"> 1001 Vaughn Street, Ann Arbor, MI 48104</i>
	</div>
	<div id="details" class="side-pane">
		<h1>Additional Details:</h1>
		<table class="medium-line-height">
			<tr>
				<td><div><i class="small-text">Bathroom:</i><br><i class="medium-text bath-type">Private</i></div></td>
				<td><div><i class="small-text">Parking:</i><br><i class="medium-text parking-avail">Available</i></div></td>
				<td><div><i class="small-text">A/C:</i><br><i class="medium-text ac-avail">Yes</i></div></td>
			</tr>
			<tr>
				<td><div><i class="small-text">Furnished:</i><br><i class="medium-text furnish-avail">Fully</i></div></td>
			</tr>
		</table>
		<h1>Anticipated Housemates:</h1>
		<table class="medium-line-height">
			<tr>
				<td><div><i class="small-text">Estimated #:</i><br><i class="medium-text housemate-count">4</i></div></td>
				<td><div><i class="small-text">Students:</i><br><i class="medium-text housemate-enrolled">Yes</i></div></td>
				<td><div><i class="small-text">Type:</i><br><i class="medium-text housemate-type">Undergraduate</i></div></td>
			</tr>
			<tr>
				<td><div><i class="small-text">Gender:</i><br><i class="medium-text housemate-gender">Male</i></div></td>
				<td><div><i class="small-text">Year:</i><br><i class="medium-text housemate-year">Seniors</i></div></td>
			</tr>
		</table>
		<div class="medium-line-height"><i class="small-text">Majors:</i><br><i class="medium-text housemate-major">YOLO</i></div>
		<h1>Sublet Costs:</h1>
		<table class="med-large-line-height">
			<tr>
				<td><i class="med-small-text">Price:</i></td>
				<td><i class="medium-text">$</i><i class="medium-text bed-price">1,050</i></td>
			</tr>
			<tr>
				<td><i class="med-small-text">Utilities:</i></td>
				<td><i class="medium-text utilities-cost">Included</i></td>
			</tr>
			<tr>
				<td><i class="med-small-text">Security Deposit:</i></td>
				<td><i class="medium-text deposit-cost">$1,050</i></td>
			</tr>
			<tr>
				<td><i class="med-small-text">Other Fees:</i></td>
				<td><i class="medium-text additional-fee">$1,050</i></td>
			</tr>
		</table>
	</div>
	<div id="contact" class="side-pane">
		<div class="medium-line-height margin-above user-info">
			<a href="" class="thumbnail float-left">
				<img src="/img/head_large.jpg" alt="Jason">
			</a>
			<div class="post-info inline float-left ellipsis">
				<i class="small-text">Posted By:</i>
				<br>
				<i class="medium-text first-name">Michael</i>
				<br>
				<i class="med-small-text school-name">University of Michigan</i>
				<br>
			</div>		
		</div>
		<table class="margin-above">
			<tr>
				<td>
					<div class="small-line-height inline float-left">
						<i class="med-small-text">Facebook</i>
						<br>
						<i class="small-text facebookFriendLabel">Mutual Friends:</i>
					</div>
					<i class="med-large-text large-line-height numFacebookFriends">--</i>
				</td>
				<td>
					<div class="small-line-height inline float-left">
						<i class="med-small-text">Twitter</i>
						<br>
						<i class="small-text">Followers:</i>
					</div>
					<i class="med-large-text large-line-height numTwitterFollowers">--</i>					
				</td>
			</tr>
		</table>
		<table id="verify-table" class="table table-bordered margin-above">
			<tr>
				<td><i class="verify-text">Email Address</i></td>
				<td><i id="emailVerified" class="unverified icon-ok-sign"></i></td>
			</tr>
			<tr>
				<td><i class="verify-text">University Email</i></td>
				<td><i id="universityVerified" class="unverified icon-ok-sign"></i></td>
			</tr>
			<tr>
				<td><i class="verify-text">Facebook</i></td>
				<td><i id="fbVerified" class="unverified icon-ok-sign"></i></td>
			</tr>
			<tr>
				<td><i class="verify-text">Twitter</i></td>
				<td><i id="twitterVerified" class="unverified icon-ok-sign"></i></td>
			</tr>
		</table>
		<!--<i class="med-small-text">You must sign up or logged in to message!</i>-->
		<div class="message-box">
			<textarea id="message-area" placeholder="I can has diz?"></textarea>
			<button id="message-button" class="btn btn-info btn-block" type="button">MESSAGE</button>
			<table id="message-submit-buttons">
				<tr>
					<td>
						<button id="cancel-message" class="btn btn-block" type="button">Cancel</button>
					</td>
					<td>
						<button id="submit-message" class="btn btn-primary btn-block" type="button" data-loading-text="Sending...">Send Message</button>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>


<?php 
	$this->Js->buffer('
		$("#overview-btn").click(function() {
			$(".side-pane").hide();
			$("#overview").show();
			$(".btn-active").removeClass("active btn-active btn-primary");
			$(this).addClass("active btn-active btn-primary");
		});
		$("#details-btn").click(function() {
			$(".side-pane").hide();
			$("#details").show();
			$(".btn-active").removeClass("active btn-active btn-primary");
			$(this).addClass("active btn-active btn-primary");
		});
		$("#contact-btn").click(function() {
			$(".side-pane").hide();
			$("#contact").show();
			$(".btn-active").removeClass("active btn-active btn-primary");
			$(this).addClass("active btn-active btn-primary");
		});
		$(".preview-thumbnail").click(function() {
			var image = $(this).css("background-image");
			$("#main-photo").css("background-image", image);
			return false;
		});
		$("#message-button").click(function() {
			$(this).hide();
			$("#verify-table").hide();
			$("#message-area").show();
			$("#message-submit-buttons").show();
			return false;
		});
		$("#cancel-message").click(function() {
			$("#message-submit-buttons").hide();
			$("#message-area").hide();
			$("#verify-table").show();
			$("#message-button").show();
			return false;
		});
	');
	if ($this->Session->read('Auth.User.id')==0)
		$this->Js->buffer('$("#submit-message").click(function() { A2Cribs.UIManager.Alert("Please Log In!"); });');
	else
		$this->Js->buffer('$("#submit-message").click(function() {
			$("#submit-message").button("loading");
			$.ajax({
				url: myBaseUrl + "Messages/messageSublet",
				type: "POST",
				context: this,
				data: { sublet_id: $("#sublet-id").text(), message_body: $("#message-area").val() },
				success: function(data) {
					data = JSON.parse(data);
					if (data.success)
					{
						A2Cribs.UIManager.Alert("Message Sent!");
					}
					else
					{
						A2Cribs.UIManager.Alert("Message Failed! Please Try Again.");
					}
					$("#submit-message").button("reset");
				}
				});
		});');
?>
