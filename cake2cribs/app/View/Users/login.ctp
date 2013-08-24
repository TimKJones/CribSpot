<?php echo $this->Html->css('/less/header.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('/less/User/login.less?','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->script('src/Login.js', array('inline' => false)); ?>
<?php echo $this->element('popups'); ?>
<?php $this->set('title_for_layout', 'Cribspot Login'); ?>

<?php echo $this->element('header', array('show_filter' => false, 'show_user' => false)); ?>

<div id="login_signup" class="fluid-container">
    <div class="row-fluid">
        <div class="span5 login_row <?= ($show_signup) ? "hide" : "" ; ?>">
            <div class="row-fluid info_box">
                <div class="span12 info_box_container">
                    <div class="row-fluid">
                         <div class="welcome_message span12">
                            Welcome Back!
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12 fb_login_container">
                            <a href="#" onclick="A2Cribs.FacebookManager.FacebookLogin()"><img src="/img/user/btn-facebook-login.png"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid info_box">
                <form id="login_content" class="span12">
                    <div class="row-fluid">
                        <div class="span12 email_login_message">
                            or sign in with your email below:
                        </div>
                    </div>

                    <div class="control-group row-fluid">
                        <label class="control-label span4 text-center" for="inputEmail">Email</label>
                        <input class="span7" type="email" id="inputEmail" placeholder="Email">
                    </div>
                    <div class="control-group row-fluid">
                        <label class="control-label span4 text-center" for="inputPassword">Password</label>
                        <input class="span7" type="password" id="inputPassword" placeholder="Password">
                    </div>
                    <div class="row-fluid">
                        <a href="/users/resetpassword" id="forgot_password" class="pull-left">Forgot Your Password?</a>
                        <input id="sign_in" type="submit" class="btn pull-right" value="SIGN IN">
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="create_account_container">
                                Don't have an account? <a href="#" class="show_signup">Join the Party!</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="pull-right span5 signup_row <?= ($show_signup) ? "" : "hide" ; ?>">
            <div class="row-fluid info_box">
                <div class="span12 info_box_container">
                    <div class="row-fluid">
                         <div class="welcome_message span12">
                            Let Us Get to Know You!
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6 show_student">
                            <div class="student_icon active"></div>
                            <div class="user_desc text-center show_student">College Student/Renter</div>
                        </div>
                        <div class="span6 show_pm">
                            <div class="pm_icon"></div>
                            <div class="user_desc text-center">Rental Owner/Manager</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid info_box fb_box">
                <div class="span12 fb_login_container">
                    <a href="" onclick="A2Cribs.FacebookManager.FacebookLogin()"><img src="/img/user/btn-facebook-login.png"></a>
                </div>
            </div>
            <div class="row-fluid info_box student_signup">
                <form id="student_signup" class="span12">
                    <div class="row-fluid">
                        <div class="span12 email_login_message">
                            or you can use your email address:
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12 signup_input_container">
                            <div class="row-fluid">
                                <input id="student_first_name" class="span6" type="text" placeholder="First Name">
                                <input id="student_last_name" class="span6 right_input" type="text" placeholder="Last Name">
                            </div>
                            <div class="row-fluid">
                                <input id="student_email" class="span12" type="email" placeholder="Email">
                            </div>
                            <div class="row-fluid">
                                <input id="student_password" class="span6" type="password" placeholder="Password">
                                <input id="student_confirm_password" class="span6 right_input" type="password" placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <input id="sign_in" class="btn pull-right" type="submit" value="SIGN UP" >
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="create_account_container">
                                Wait a second... <a href="#" class="show_login">I have an account.</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row-fluid info_box pm_signup hide">
                <div class="span12">
                    <div class="row-fluid">
                        <form id="pm_signup" class="span12 signup_input_container">
                            <div class="row-fluid">
                                <input id="pm_company_name" class="span12" type="text" placeholder="Company or Main Contact Name" required>
                            </div>
                            <div class="row-fluid">
                                <input id="pm_email" class="span7" type="email" placeholder="Email" required>
                                <input id="pm_phone" class="span5 right_input" type="text" placeholder="Phone" required>
                            </div>
                            <div class="row-fluid">
                                <input id="pm_password" class="span6" type="password" placeholder="Password" required>
                                <input id="pm_confirm_password" class="span6 right_input" type="password" placeholder="Confirm Password" required>
                            </div>
                            <div class="row-fluid">
                                <input id="pm_website" class="span12" type="text" placeholder="Website (Optional)">
                            </div>
                            <div class="row-fluid">
                                <input id="pm_street_address" class="span12" type="text" placeholder="Leasing Office Address" required>
                            </div>
                            <div class="row-fluid">
                                <input id="pm_city" class="span4" type="text" placeholder="City" required>
                                <select id="pm_state" class="span3" id="pm_state">
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
                                <input id="sign_in" class="btn pull-right" type="submit" value="SIGN UP" >
                            </div>
                            
                        </form>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="create_account_container">
                                Wait a second... <a href="#" class="show_login">I have an account.</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!--<div id="signup_content" class="pull-right span7">
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
        </div> -->
    </div>
</div>

<?php 
    $this->Js->buffer('
        A2Cribs.Login.setupUI();
    ');
?>
