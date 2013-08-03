<?php echo $this->Facebook->html(); ?>

<?php echo $this->Html->script('src/loginTest'); ?>
        <?php echo $this->Facebook->init(); ?>
        <button id="facebookLogin" onclick="A2Cribs.loginTest.FacebookLogin()">Login</button>
        <button id="facebookLogin" onclick="A2Cribs.loginTest.FacebookLogout()">Logout</button>
</html>

