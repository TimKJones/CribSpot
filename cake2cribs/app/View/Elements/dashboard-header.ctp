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
		<li><a href="#">Full-Year Leases</a></li>
		<li><a href="#">Parking</a></li>
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
		<li><a href="#partners">Partners</a></li>
		<li><a href="#contact">Contact</a></li>
		<li><a href="#help">Help</a></li>
		<li><a href="#more">More</a></li>
	</ul>
</div>
<div id="header" class="container">
	<a href="/"><div class="main-logo pull-left"></div></a>
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

