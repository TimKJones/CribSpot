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
	echo $this->element('post-sublet');
	echo $this->element('register');
	echo $this->element('popups');
?>

<?php echo $this->element('header', array('show_filter' => false, 'show_user' => true)); ?>

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
				echo '<a id="post-btn" class="subletAddSteps btn add-on" href="#post-sublet-modal" data-toggle="modal" onclick="A2Cribs.SubletSave.StartNewSublet()" >POST</a>';
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

