<?php
	echo $this->Html->css('/less/landing.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->script('src/Landing');
	$this->set('title_for_layout', 'Cribspot - Simple and Secure Subletting.');
?>


<?php 
	$this->Js->buffer('
		A2Cribs.Landing.Init(' . json_encode($locations) . ');
		$("#school-form").submit(function() { A2Cribs.Landing.Submit(); return false; });
		$("#search-text").focus();
	');
?>

<?php
	echo $this->element('login');
	echo $this->element('register');
	echo $this->element('popups');
?>

<div class="top-bar">
	<ul id="left-options" class="inline unstyled pull-left">

	<?php 
	if ($this->Session->read('Auth.User.id') == 0)
		echo '<li><a href="#myModal" data-toggle="modal">Login</a></li>';
	else
		echo '<li><a href="/dashboard">My Dashboard</a></li>';
	?>
	</ul>
	<ul id="right-options" class="inline unstyled pull-right">
		<li><a href="#about-page" data-toggle="modal">About</a></li>
		<li><a href="#contact-page" data-toggle="modal">Contact</a></li>
		<li><a href="#help-page" data-toggle="modal">Help</a></li>
	</ul>
</div>

<div class="float" id="search-div">
	<img src="/img/landing/logo.png" height="300px" width="300px">
	<h1>Student Sublets.</h1>
	<div class="blue-background input-append">
		<form id="school-form">
			<?php
			if ($this->Session->read('Auth.User.id') == 0)
			{
				echo '<a id="post-btn" onclick="A2Cribs.Register.InitRegister(' . "'/post')" . '" class="btn add-on" data-toggle="modal">POST</a>';
			}
			else
				echo '<a id="post-btn" class="subletAddSteps btn add-on" href="#" >POST</a>';
			?>
			<input id="search-text" class="typeahead" placeholder="Search By University or City" type="text" autocomplete="off">
			<button type="submit" id="search-btn" class="btn add-on"><i class="icon-search icon-2x"></i></button>
		</form>
		<?php
		echo $this->Html->link(
			'',
			array('controller' => 'map', 'action' => 'sublet'),
			array('class' => 'hide', 'id' => 'sublet-redirect')
		);
		?>

	</div>
	<div>
		<table>
			<tr>
				<td><a href="http://facebook.com/cribspot"><i class="icon-facebook icon-large"></i> facebook.com/cribspot</a></td>
				<td><a href="http://twitter.com/TheCribSpot"><i class="icon-twitter icon-large"></i> @TheCribSpot</a></td>
			</tr>
		</table>
	</div>
</div>
<div id="subprobs"><a id="subprobs" href="https://twitter.com/intent/tweet?button_hashtag=SUBLETPROBS&via=TheCribSpot">Tweet #SUBLETPROBS</a></div>

<?php
	$this->Js->buffer('
		$(".subletAddSteps").click(function(event) {
			A2Cribs.Cache.SubletEditInProgress = new A2Cribs.SubletInProgress();
			$("<div/>").dialog2({
				title: "Post a sublet", 
				content: "/Sublets/ajax_add", 
				id: "server-notice"
			});

			event.preventDefault();
		});
	');
?>

