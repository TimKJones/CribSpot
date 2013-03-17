<?php
	/* Less files for style */
	/* Eventually switch to css */
	echo $this->Html->css('/less/header.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->css('/less/popover.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->css('/less/slider.less?','stylesheet/less', array('inline' => false));

	/* Datepicker and slider css */
	echo $this->Html->css('datepicker');

	/* Datepicker and slider javascript */
	echo $this->Html->script('bootstrap-datepicker');
	echo $this->Html->script('bootstrap-slider');

	echo $this->Html->script('src/PageHeader');

?>


<div class="top-bar">
	<a id="sublet-post" href="#" class="post-button inline pull-left">POST A SUBLET</a>
	<ul id="left-options" class="inline unstyled pull-left">
		<li class="active"><a href="#">Sublets</a></li>
		<li><a href="#">Full-Year Leases</a></li>
		<li><a href="#">Parking</a></li>
	</ul>
	<ul id="right-options" class="inline unstyled pull-right">
		<li><a href="#about">About</a></li>
		<li><a href="#partners">Partners</a></li>
		<li><a href="#contact">Contact</a></li>
		<li><a href="#help">Help</a></li>
		<li><a href="#more">More</a></li>
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
		<div class="btn middle-btn">
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
							&nbsp;&nbsp;Beds: <select>
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
			
			<?php if ($this->Session->read('Auth.User.id')==0)
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
				<li><a href="#"><i class="icon-cogs"></i> Account Settings</a></li>
			</ul>
		</div>
		<?php if ($this->Session->read('Auth.User.id')!=0)
		{
			echo '<a href="/dashboard" class="personal-links"><i class="icon-comments icon-large"></i></a>
		<div id = "unread-conversation-notification"></div>
		<a href="#" class="personal-links"><i class="icon-heart-empty icon-large"></i></a>';
		}
		else
		{
			echo '<a href="#myModal" data-toggle="modal" class="personal-links"><i class="icon-comments icon-large"></i></a>
		<div id = "unread-conversation-notification"></div>
		<a href="#myModal" data-toggle="modal" class="personal-links"><i class="icon-heart-empty icon-large"></i></a>';
		}
			
		?>
		
	</div>
</div>

<div style="display:none;">
	<form id='search-form'><input type='text' placeholder='Local Street Address'></form>
	<div id='apttype-popover'>
		<table>
			<tr>
				<td><label class="checkbox"><input type="checkbox">Apartment</label></td>
				<td><label class="checkbox"><input type="checkbox">House</label></td>
				<td><label class="checkbox"><input type="checkbox">Other</label></td>
			</tr>
		</table>
	</div>
	<div id='housemates-popover'>
		<table>
			<tr>
				<td>Housemates:</td>
				<td><label class="checkbox"><input type="checkbox">Male</label></td>
				<td><label class="checkbox"><input type="checkbox">Female</label></td>
			</tr>
		</table>
		<table>
			<tr>
				<td><label class="checkbox"><input type="checkbox">Students Only</label></td>
				<td><label class="checkbox"><input type="checkbox">Grad</label></td>
				<td><label class="checkbox"><input type="checkbox">Undergrad</label></td>
			</tr>
		</table>
	</div>
	<div id='more-popover'>
		<div class="top-row">
			<label>Bathroom</label>
			<select>
				<option>Private</option>
				<option>Public</option>
			</select>
			<label class="checkbox"><input type="checkbox">A/C</label>
			<label class="checkbox"><input type="checkbox">Parking</label>
		</div>
		<label class="checkbox"><input type="checkbox">Utilities Included</label>
		<label class="checkbox"><input type="checkbox">No Security Deposit</label>
	</div>
	<?= $this->element('slider'); ?>
</div>

<?php
	$this->Js->buffer('
		$(".tooltip-btn").tooltip();	
		$(".popover-btn").popover();
		$(".date-picker").datepicker();
		A2Cribs.PageHeader.renderUnreadConversationsCount();
	');
?>

