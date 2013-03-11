<?php 
	include 'facebook.php';
	include 'init.php';
	include 'utility.php';
	include 'slider.php';
	include 'tabs.php';
	include 'animations.php';
	include 'favorites.php';
	include 'groupsManager.php';
	include 'createGroup.php';
?>

<html>
	<head>
	<LINK href="mapsCss.html" title="compact" rel="stylesheet" type="text/css"> 	
	<LINK href="groups.css" title="compact" rel="stylesheet" type="text/css"> 	
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
 <script type="text/javascript">
    document.write("\<script src='http://code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
</script>  
	<script src="../jquery.tmpl.js" type="text/javascript"></script>
		</head>
	<body>
		<div id="fb-root"></div>
    <script>
      // Load the SDK Asynchronously
      (function(d){
         var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement('script'); js.id = id; js.async = true;
         js.src = "//connect.facebook.net/en_US/all.js";
         ref.parentNode.insertBefore(js, ref);
       }(document));

      // Init the SDK upon load
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '303685486372971', // App ID
          channelUrl : '//'+window.location.hostname+'/channel', // Path to your Channel File
          status     : true, // check login status
          cookie     : true, // enable cookies to allow the server to access the session
          xfbml      : true  // parse XFBML
        });

				// listen for and handle auth.statusChange events
        FB.Event.subscribe('auth.statusChange', function(response) {
          if (response.authResponse) {
            // user has auth'd your app and is logged into Facebook
						
						accessToken = response.authResponse.accessToken;
 
						FB.api('/me', function(me){ 
						/* This function retrieves the user account information */
						/*var prof_pic = document.getElementById('prof_pic');
         		prof_pic.src = 'http://graph.facebook.com/' + me.id + '/picture';
						prof_pic.style.display="inline";
						
						document.getElementById('welcome_msg').innerHTML = me.name;
	
						var welcome_space = document.getElementById('welcome_space');
						welcome_space.innerHTML = " ";
*/
						uid = me.id;		
						LoadFriendsList();
            })
          } 
					else 
					{
            // user has not auth'd your app, or is not logged into Facebook
 						/*document.getElementById('welcome_space').innerHTML = "";
						document.getElementById('welcome_msg').innerHTML = "";
						
						document.getElementById('prof_pic').style.display = 'none';
					*/	
						document.getElementById('fb_login').style.visibility="visible";
          }
        });

        // respond to clicks on the login and logout links
        document.getElementById('fb_login').addEventListener('click', function(){
					FacebookLogin();
          FB.login();
        });
        document.getElementById('auth-logoutlink').addEventListener('click', function(){
					FacebookLogout();
          FB.logout();
			//		location.reload();
        }); 
      }
    
</script>
	
		<div id="grayBackground"></div>
		<div id="groupMenu">
			<div id="createGroupDiv">	
				<button id="selectFriendsBtn" style="visibility:hidden" onclick="SelectFriends()">Select Friends</button>
				<h3><b>Join Existing Group</b></h3><br/>	
				Name: <input type="text" id="groupNameJoin"></input>
				Password: <input type="password" id="groupPasswordJoin"></input>
				<button id="joinGroup" onclick="JoinGroup()">Join Group</button><br/>
				<h3><b>Create Group</b></h3>
				Name: <input type="text" id="groupName"></input>
				Password: <input type="password" id="groupPassword"></input>
				<button id="groupSubmitButton" onclick="GroupSubmit()">Create Group</button>	
			</div>
		</div>
		<div id="container">
		<div id="topBar">	
			<div class="fb-login-button fb_login" scope="email,user_checkins" id="fb_login"></div>	
			<div id="logout_link" class="fb-login-button fb_login"><a href="#" id="auth-logoutlink">logout</a></div>
		</div>
		<button id="createGroupBtn" onclick="ShowGroupMenu()">Create Group</button>
		<div id="friendsList"></div>
		<!-- Friend Template -->
			<script id="friendTemplate" type="text/html">
				<div class="friendDiv" id="friendDiv${Friendid}">
					<table class="friendDivContent">
						<tr class="friendRow">
							<td class="friendPic">
								<img src= "${Url}"/>	
							</td>
							<td class="friendName">	
								{{= Name}} 
							</td>
						</tr>	
					</table>
				</div>
			</script>
		</div>
	</body>

<script type="text/javascript">
	document.onmousedown = FriendsMouseDown;
</script>
</html>
