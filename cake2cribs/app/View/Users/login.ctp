<?php echo $this->Html->css('/less/header.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('/less/User/login.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->script('src/Login.js', array('inline' => false)); ?>
<?php echo $this->element('popups'); ?>
<div class="top-bar">
    <ul id="right-options" class="inline unstyled pull-right">
        <li><a href="#about-page" data-toggle="modal">About</a></li>
        <li><a href="#contact-page" data-toggle="modal">Contact</a></li>
        <li><a href="#help-page" data-toggle="modal">Help</a></li>
    </ul>
</div>

<div id="header" class="container">
    <a href="/"><div class="main-logo pull-left"></div></a>
</div>

<div id="login_signup" class="fluid-container">
    <div id="login_row" class="row-fluid">
        <form id="login_content" class="span4">
            <div class="row-fluid">
                <h2>Welcome Back!</h2>
                <a href="#"><img src="/img/user/btn-facebook-login.png"></a>
                <div class="divider">
                    <span>
                    &nbsp;OR&nbsp;
                    </span>
                </div>
                <br>
                <h5>Email</h5>
                <input class="span12" type="email" id="inputEmail" name="email">
                <h5>Password</h5>
                <input class="span12" type="password" id="inputPassword" name="password">
                <a href="#" class="pull-right">Forgot your password?</a>
                <button id="login_button" type="submit" class="btn btn-primary">Sign in</button>
                <br>
                <a href="#" class="show_signup pull-right">Don't have an account? Join the party!</a>
            </div>
        </form>
        <div id="signup_content" class="pull-right span7 hide">
            <div class="row-fluid">
                <div class="row-fluid">
                    <h2>Let us get to know you.</h2>
                    <h4 class="pull-left">Are you a </h4>
                    <ul id="user_type_select" class="pull-left nav nav-pills">
                        <li id="student" class="active user_types"><a href="#">Student</a></li>
                        <li id="pm" class="user_types"><a href="#">Property Manager</a></li>
                    </ul>
                </div>
                <form id="student_signup" class="signup">
                    <a href="#" id="fb-signup"><img src="/img/user/btn-facebook-signup.png"></a>
                    <div class="row-fluid">
                        <div class="span6">
                            <h6>Email</h6>
                            <input type="email" id="student_email" name="email" placeholder="you@thatonesite.com">
                        </div>
                        <div class="span6">
                            <h6>Password</h6>
                            <input type="password" id="student_password" name="password" placeholder="CR1b5p0T">
                            <h6>Confirm Password</h6>
                            <input type="password" id="student_confirm_password" name="password">
                        </div>
                    </div>
                    <div class="row-fluid personal_data">
                        <div class="span6">
                            <h6>First Name</h6>
                            <input type="text" id="student_first_name" placeholder="Tim">
                        </div>
                        <div class="span6">
                            <h6>Last Name</h6>
                            <input type="text" id="student_last_name" placeholder="Jones">
                        </div>
                    </div>
                    <button id="student_submit" class="btn btn-primary signup-btn">Sign Up</button>
                    <a class="show_login pull-right" href="#">Wait a minute...I have an account.</a>
                </form>
                <form id="pm_signup" class="hide signup">
                    <div class="row-fluid">
                        <div class="span6">
                            <h6>Email</h6>
                            <input type="email" id="pm_email" name="email" placeholder="you@thatonesite.com">
                        </div>
                        <div class="span6">
                            <h6>Password</h6>
                            <input type="password" id="pm_password" name="password" placeholder="CR1b5p0T">
                            <h6>Confirm Password</h6>
                            <input type="password" id="pm_confirm_password" name="password">
                        </div>
                    </div>
                    <div class="row-fluid personal_data">
                        <div class="span6">
                            <h6>Company Name</h6>
                            <input type="text" id="pm_company_name">
                            <h6>Leasing Office Address</h6>
                            <input type="text" id="pm_street_address">
                            <div class="row-fluid">
                                <div class="span8">
                                    <h6>City</h6>
                                    <input class="span12" type="text" id="pm_city">
                                </div>
                                <div class="span4">
                                    <h6>State</h6>
                                    <select class="span12" id="pm_state">
                                        <option></option>
                                        <option value="AL">AL</option>
                                        <option value="AK">AK</option>
                                        <option value="AZ">AZ</option>
                                        <option value="AR">AR</option>
                                        <option value="CA">CA</option>
                                        <option value="CO">CO</option>
                                        <option value="CT">CT</option>
                                        <option value="DE">DE</option>
                                        <option value="DC">DC</option>
                                        <option value="FL">FL</option>
                                        <option value="GA">GA</option>
                                        <option value="HI">HI</option>
                                        <option value="ID">ID</option>
                                        <option value="IL">IL</option>
                                        <option value="IN">IN</option>
                                        <option value="IA">IA</option>
                                        <option value="KS">KS</option>
                                        <option value="KY">KY</option>
                                        <option value="LA">LA</option>
                                        <option value="ME">ME</option>
                                        <option value="MD">MD</option>
                                        <option value="MA">MA</option>
                                        <option value="MI">MI</option>
                                        <option value="MN">MN</option>
                                        <option value="MS">MS</option>
                                        <option value="MO">MO</option>
                                        <option value="MT">MT</option>
                                        <option value="NE">NE</option>
                                        <option value="NV">NV</option>
                                        <option value="NH">NH</option>
                                        <option value="NJ">NJ</option>
                                        <option value="NM">NM</option>
                                        <option value="NY">NY</option>
                                        <option value="NC">NC</option>
                                        <option value="ND">ND</option>
                                        <option value="OH">OH</option>
                                        <option value="OK">OK</option>
                                        <option value="OR">OR</option>
                                        <option value="PA">PA</option>
                                        <option value="RI">RI</option>
                                        <option value="SC">SC</option>
                                        <option value="SD">SD</option>
                                        <option value="TN">TN</option>
                                        <option value="TX">TX</option>
                                        <option value="UT">UT</option>
                                        <option value="VT">VT</option>
                                        <option value="VA">VA</option>
                                        <option value="WA">WA</option>
                                        <option value="WV">WV</option>
                                        <option value="WI">WI</option>
                                        <option value="WY">WY</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <h6>Website</h6>
                            <input type="text" id="pm_website">
                            <h6>Phone</h6>
                            <input type="text" id="pm_phone">
                        </div>
                    </div>
                    <button id="pm_submit" class="btn btn-primary signup-btn">Sign Up</button>
                    <a class="show_login pull-right" href="#">Wait a minute...I have an account.</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
    $this->Js->buffer('
        A2Cribs.Login.setupUI();
    ');
?>

<script>
$('body').noisy({
    'intensity' : 1, 
    'size' : 200, 
    'opacity' : 0.08, 
    'fallback' : '', 
    'monochrome' : true
}).css('background-color', '#eeecec');


if (document.URL.indexOf("password_reset_redirect") != -1)
    A2Cribs.UIManager.Alert("An email has been sent to the email address on file with a link to reset your password.");
else if (document.URL.indexOf("password_changed") != -1)
    A2Cribs.UIManager.Alert("Your password has been successfully changed. Please enter your new login credentials.");
</script>