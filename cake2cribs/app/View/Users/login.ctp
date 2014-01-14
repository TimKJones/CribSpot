<?php echo $this->Html->css('/less/header.less?v=76','stylesheet/less', array('inline' => false)); ?>
<?php echo $this->Html->css('/less/User/login.less?v=2','stylesheet/less', array('inline' => false)); ?>

<?php
if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION'){
    echo $this->Html->script('src/Login.js', array('inline' => false));
}
?>

<?php $this->set('title_for_layout', 'Login | Register - Cribspot');
        
    $this->Html->meta('keywords', 
        "cribspot login, cribspot signup, cribspot add user, cribspot new account, cribspot log in, cribspot register, off campus housing, student housing, college rental, college sublet, college parking, college sublease", array('inline' => false)
    );

    $this->Html->meta('description', "Hello for the first time or welcome back to Cribspot! We are excited to have you join the community with thousands of listings all at your fingertips. College life is difficult...Cribspot makes it easier. Sign up or log in and find your ideal housing today!", array('inline' => false));
?>

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
                            <a href="#" class="fb_login_btn"><img src="/img/user/btn-facebook-login.png"></a>
                            <p>** Facebook login is available for students only!</p>
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
                            <div class="student_icon  <?= ($show_pm) ? "" : "active"; ?>"></div>
                            <div class="user_desc text-center show_student">College Student/Renter</div>
                        </div>
                        <div class="span6 show_pm">
                            <div class="pm_icon <?= ($show_pm) ? "active" : ""; ?>"></div>
                            <div class="user_desc text-center">Rental Owner/Manager</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid info_box fb_box  <?= ($show_pm) ? "hide" : ""; ?>">
                <div class="span12 fb_login_container">
                    <a href="#" class="fb_login_btn"><img src="/img/user/btn-facebook-login.png"></a>
                    <p>** Facebook login is available for students only!</p>
                </div>
            </div>
            <div class="row-fluid info_box student_signup <?= ($show_pm) ? "hide" : ""; ?>">
                <form id="student_signup" class="span12">
                    <div class="row-fluid">
                        <div class="span12 email_login_message">
                            or you can use your email address:
                        </div>
                        <div class="fb-signup-welcome row-fluid hide">
                            <img class="fb-image pull-left" src="https://graph.facebook.com/552918161/picture?width=80&amp;height=80">
                            <div class="fb-complete-signup">
                                Welcome <i class="fb-name">Billy</i>!
                                <br>
                                Please complete the fields.
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12 signup_input_container">
                            <div class="row-fluid">
                                <input id="student_first_name" class="span6" type="text" placeholder="First Name" value="<?= ($this->Session->read('FB.first_name')) ? $this->Session->read('FB.first_name') : "" ; ?>">
                                <input id="student_last_name" class="span6 right_input" type="text" placeholder="Last Name" value="<?= ($this->Session->read('FB.last_name')) ? $this->Session->read('FB.last_name') : "" ; ?>">
                            </div>
                            <div class="row-fluid">
                                <input id="student_email" class="span6" type="email" placeholder="Email">
                                <input id="student_password" class="span6 right_input" type="password" placeholder="Password">
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="create_account_container">
                                Wait a second... <a href="#" class="show_login">I have an account.</a>
                                <input id="sign_in" type="submit" class="btn pull-right" type="submit" value="SIGN UP" >
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row-fluid info_box pm_signup <?= ($show_pm) ? "" : "hide"; ?>">
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


    </div>
</div>
