<?php
	/* Less files for style */
	/* Eventually switch to css */
	echo $this->Html->css('/less/header.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->css('/less/popover.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->css('/less/slider.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->css('/css/jquery-dialog2/jquery.dialog2.css', array('inline'=>false));
	echo $this->Html->css('/less/checkbox.less?','stylesheet/less', array('inline' => false));
	
	//echo('<link rel="stylesheet" type="text/css" href="/css/jquery-dialog2/jquery.dialog2.css">');
	/* Datepicker and slider css */
	echo $this->Html->css('datepicker');

	/* Datepicker and slider javascript */
	echo $this->Html->script('bootstrap-datepicker');
	echo $this->Html->script('bootstrap-slider');
	echo $this->Html->script('src/PageHeader');

?>


<div class="top-bar">
	<!-- <a id="sublet-post" href="#" class="post-button inline pull-left">POST A SUBLET</a> -->
	<!-- <a id="sublet-post" class="post-button inline pull-left open-dialog" href="/users/verifyUniversity"> POST A SUBLET</a> -->

	<?php if ($this->Session->read('Auth.User.id')==0)
			{
				echo '<a class="post-button inline pull-left" href="#myModal" data-toggle="modal">POST A SUBLET</a>';
			}
			else
				echo '<a id="subletAddSteps" class="post-button inline pull-left" href="#" >POST A SUBLET</a>';
	?>
	<ul id="left-options" class="inline unstyled pull-left">
		<li class="active"><a href="#">Sublets</a></li>
		<li class="disabled"><a href="#" onclick="alertify.alert('Full-Year Leases are coming soon!');">Full-Year Leases</a></li>
		<li class="disabled"><a href="#" onclick="alertify.alert('Parking is coming soon!');">Parking</a></li>
		<!--<li><a href="" onclick="A2Cribs.FacebookManager.ShareListingOnFacebook('test', 'test', 2)">Share</a></li>
		<li>
			<div id="twitterDiv">
				<a href="https://twitter.com/share" class="twitter-share-button" data-url="&quot;tweetUrl&quot;" data-text="&quot;tweet text&quot;" data-via="TheCribspot" data-size="small">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
		</li>-->
	</ul>
	<ul id="right-options" class="inline unstyled pull-right">
		<li><a href="#about-page" data-toggle="modal">About</a></li>
		<li><a href="#contact">Contact</a></li>
		<li><a href="#help">Help</a></li>
	</ul>
</div>
<div id="header" class="container">
	<a href="/"><div class="main-logo pull-left"></div></a>
	<a id="search-button" class="btn middle-btn popover-btn tooltip-btn" title="<strong>Search by Street Address</strong>" href="#" data-content="search-form" data-placement="bottom" data-html="true"><i class="icon-search icon-large"></i></a>
	<div id="compact-filter" class="btn-group">
		<a class="btn middle-btn" href="#"><i class="icon-calendar icon-large"></i></a>
		<a class="btn middle-btn" href="#"><strong>$</strong></a>
		<a class="btn middle-btn" href="#"><i class="icon-inbox icon-large"></i></a>
		<a class="btn middle-btn" href="#"><i class="icon-home icon-large"></i></a>
		<a class="btn middle-btn" href="#"><i class="icon-group icon-large"></i></a>
		<a class="btn middle-btn" href="#"><i class="icon-plus icon-large"></i></a>
	</div>

	<div id="expanded-filter" class="btn-group">
		<div class="btn middle-btn filter-btn">
			<form>
				<table>
					<tr>
						<td><b>Filter:</b>&nbsp;&nbsp;&nbsp;From:&nbsp;
							<input id="startDate" class="date-picker tooltip-btn" title="<strong>Start Date</strong>" data-html="true" data-placement="bottom" type="text" placeholder="Start Date" readonly>
							&nbsp;to&nbsp;
							<input id="endDate" class="date-picker tooltip-btn" title="<strong>End Date</strong>" data-html="true" data-placement="bottom" type="text" placeholder="End Date" readonly></td>
						<td>&nbsp;&nbsp;Price: 
							<div id="slider-div"><input id="price-filter" class="popover-btn tooltip-btn" data-placement="bottom" type="text" value="$0 - $2000+" data-content="slider-content" data-html="true" readonly></div></td>
						<td>
							&nbsp;&nbsp;Beds: <select id="bedsSelect" onchange="A2Cribs.FilterManager.ApplyFilter(this)">
								<option>1</option>
								<option>2+</option>
							</select>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<a class="btn apttype-icon popover-btn tooltip-btn" href="#" title="<strong>Building Type</strong>" data-placement="bottom" data-html="true" data-content="apttype-popover"><i class="icon-home icon-large"></i></a>
		<a class="btn housemates-icon popover-btn tooltip-btn" href="#" title="<strong>Housemate Preferences</strong>" data-placement="bottom" data-html="true" data-content="housemates-popover"><i class="icon-group icon-large"></i></a>
		<a class="btn more-icon popover-btn tooltip-btn" href="#" title="<strong>More Filters</strong>" data-placement="bottom" data-html="true" data-content="more-popover"><i class="icon-plus icon-large"></i></a>
	</div>
	<div id="personal-buttons" class="pull-right">
		<div class="btn-group">
			<a class="btn btn-link hide" data-toggle="dropdown" href="#">
				<img src="img/jason.jpg" class="img-polaroid">
				<strong>Jason</strong>
				<span class="caret"></span>
			</a>
			
			<?php if ($this->Session->read('Auth.User.id') == 0)
			{
				echo '<a class="btn btn-link" href="#signupModal" data-toggle="modal">SIGN UP</a>';
				echo '<a class="btn btn-link" href="#myModal" data-toggle="modal">LOGIN</a>';
			}
			else
				echo '<a class="btn btn-link" href="/users/logout">LOGOUT</a>'
			?>

			<ul class="dropdown-menu">
				<li><a href="#">Action 1</a></li>
				<li><a href="#">Action 2</a></li>
				<li class="divider"></li>
				<li><a href="/dashboard"><i class="icon-cogs"></i> Account Settings</a></li>
			</ul>
		</div>
		<?php if ($this->Session->read('Auth.User.id') != 0)
		{
			echo '<a href="/messages" class="personal-links"><i class="icon-comments icon-large"></i></a>
		<div id = "unread-conversation-notification"></div>
		<a href="#" class="personal-links"><i id="FavoritesHeaderIcon" class="icon-heart-empty icon-large" onclick="A2Cribs.FavoritesManager.ToggleFavoritesVisibility()"></i></a>';
		}
		else
		{
			echo '<a href="#myModal" data-toggle="modal" class="personal-links"><i class="icon-comments icon-large"></i></a>
		<div id = "unread-conversation-notification"></div>
		<a href="#myModal" data-toggle="modal" class="personal-links"><i id="FavoritesHeaderIcon" class="icon-heart-empty icon-large"></i></a>';
		}
			
		?>
		
	</div>
</div>

<div style="display:none;">
	<form id='search-form'><input type='text' placeholder='Local Street Address'></form>
	<div id='apttype-popover'>
		<table>
			<tr>
				<td><input type="checkbox" id="aptCheck" checked="yes" onchange="A2Cribs.FilterManager.ApplyFilter(this)"><label for="aptCheck">Apartment</label></td>
				<td><input type="checkbox" id="houseCheck" checked="yes" onchange="A2Cribs.FilterManager.ApplyFilter(this)"><label for="houseCheck">House</label></td>
				<td><input type="checkbox" id="otherCheck" checked="yes" onchange="A2Cribs.FilterManager.ApplyFilter(this)"><label for="otherCheck">Other</label></td>
			</tr>
		</table>
	</div>
	<div id='housemates-popover'>
		<table>
			<tr>
				<td>Housemates:</td>
				<td><input type="checkbox" checked="yes" id="maleCheck" onchange="A2Cribs.FilterManager.ApplyFilter(this)"><label for="maleCheck">Male</label></td>
				<td><input type="checkbox" checked="yes" id="femaleCheck" onchange="A2Cribs.FilterManager.ApplyFilter(this)"><label for="femaleCheck">Female</label></td>
			</tr>
		</table>
		<table>
			<tr>
				<td><input type="checkbox" id="studentsOnlyCheck" onchange="A2Cribs.FilterManager.ApplyFilter(this)"><label for="studentsOnlyCheck">Students Only</label></td>
				<td><input type="checkbox" checked="yes" id="gradCheck" onchange="A2Cribs.FilterManager.ApplyFilter(this)"><label for="gradCheck">Grad</label></td>
				<td><input type="checkbox" checked="yes" id="undergradCheck" onchange="A2Cribs.FilterManager.ApplyFilter(this)"><label for="undergradCheck">Undergrad</label></td>
			</tr>
		</table>
	</div>
	<div id='more-popover'>
		<div class="top-row">
			<label>Bathroom</label>
			<select id="bathSelect" onchange="A2Cribs.FilterManager.ApplyFilter(this)">
				<option>No Preference</option>
				<option>Private</option>
				<option>Shared</option>
			</select>
			<input type="checkbox" id="acCheck" onchange="A2Cribs.FilterManager.ApplyFilter(this)"><label for="acCheck">A/C</label>
			<input type="checkbox" id="parkingCheck" onchange="A2Cribs.FilterManager.ApplyFilter(this)"><label for="parkingCheck">Parking</label>
		</div>
		<input type="checkbox" id="utilitiesCheck" onchange="A2Cribs.FilterManager.ApplyFilter(this)"><label for="utilitiesCheck">Utilities Included</label>
		<input type="checkbox" id="noSecurityDepositCheck" onchange="A2Cribs.FilterManager.ApplyFilter(this)"><label for="noSecurityDepositCheck">No Security Deposit</label>
	</div>
	<?= $this->element('slider'); ?>
</div>

<?php
	$this->Js->buffer('
		$(".tooltip-btn").tooltip();	
		$(".popover-btn").popover();
		$(".date-picker").datepicker().on("changeDate", function(ev) {
    		A2Cribs.FilterManager.ApplyFilter(ev);
    	});
		A2Cribs.PageHeader.renderUnreadConversationsCount();
	');
?>

<script type="text/javascript">
    $(function() {
        $('#subletAddSteps').click(function(event) {
            $('<div/>').dialog2({
                title: "Post a sublet", 
                content: "Sublets/ajax_add", 
                id: "server-notice"
            });

            event.preventDefault();
        });
    });
/*
    $("#endDate").datepicker().on('changeDate', function(ev) {
    	alert('changed');
    }*/

  //  $("#price-filter").slider().on('slideStop', A2Cribs.FilterManager.ApplyFilter(ev));
</script>

