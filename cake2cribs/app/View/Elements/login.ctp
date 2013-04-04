
<?php
	echo $this->Html->css('/less/login.less?','stylesheet/less', array('inline' => false));
	echo $this->Html->script('src/Login');
?>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-body">
		<div id="modal-top">
			<h2 id="modal-logo" class="text-center">Cribspot</h2>
		</div>
		<div id="modal-center">
			<!--<div class="facebook-login-button">Login with Facebook</div>-->
			<div id = "loginStatus"> Please login.</div>
			<form id="loginForm">
				<input type="email" id="inputEmail" name="email" placeholder="Email">
				<input type="password" id="inputPassword" name="password" placeholder="Password">
				<button type="submit" id="submitButton" class="btn">Sign in</button>
			</form>
			<table>
				<tr>
					<td>
						<?= $this->Html->link('Forgot Your Password?' , array('controller' => 'users', 'action' => 'resetpassword')); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?= $this->Html->link('Don\'t Have An Account? Sign Up!' , array('controller' => 'users', 'action' => 'add')); ?>
					</td>
				</tr>
			</table>
		</div>
		<div id="modal-bottom">
			<div id="modal-slogan"><a href="https://twitter.com/intent/tweet?button_hashtag=SubletProbs&text=Turn%20your%20sublet%20problems%20into%20sublet%20solutions%20at%20%40TheCribspot!%20%20" class="twitter-hashtag-button" data-related="TheCribspot">Tweet #SubletProbs</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
		</div>
	</div>
</div>

<script>

var a = A2Cribs.Login;

a.setupUI();
</script>