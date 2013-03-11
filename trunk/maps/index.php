<?php 
	require 'fbaccess.php';
	include 'init.php';
	include 'utility.php';
	include 'slider.php';
	include 'animations.php';
	include 'favorites.php';
	include 'facebook.php';
?>

<html>
	<head>
 		<LINK href="mapsCss.html" title="compact" rel="stylesheet" type="text/css">
 		<LINK href="style/header.css" title="compact" rel="stylesheet" type="text/css">
 		<LINK href="style/favorites.css" title="compact" rel="stylesheet" type="text/css">
 		<LINK href="style/filter.css" title="compact" rel="stylesheet" type="text/css">
		<LINK href="style/tooltip.css" title="compact" rel="stylesheet" type="text/css">
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<script type="text/javascript" src="src/favoritesTab.js"></script>
    <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyChGyO2wCFqmDe8FNh_6GxITy7dDLQ0ZpE&sensor=false">
    </script>
		<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobubble/src/infobubble.js"></script>	  <script type="text/javascript">
    document.write("\<script src='http://code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
</script>  
	<script src="jquery.tmpl.min.js" type="text/javascript"></script>
		</head>
	<body onload="initialize()">
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
						var fb_login = document.getElementById('fb_login');
						fb_login.style.visibility="hidden";
						FacebookLogin();
	
            document.getElementById('logout_link').style.visibility = 'visible';
            })
          } 
					else 
					{
            // user has not auth'd your app, or is not logged into Facebook
 						/*document.getElementById('welcome_space').innerHTML = "";
						document.getElementById('welcome_msg').innerHTML = "";
						
						document.getElementById('prof_pic').style.display = 'none';
					*/	
						document.getElementById('logout_link').style.visibility="hidden";
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
		<div id="container">
			<div id="tooltipWrapper">
				<div id="tooltipTabs">
					<ul>
						<li id="tooltipFirstTab" class="tab tabSelected" onclick="FirstTabClicked()"><a id="firstTabLink" href="#">Crib Details</a></li><!-- these comments between li's solve a problem in IE that prevents spaces appearing between list items that appear on different lines in the source
						--><li id="tooltipSecondTab" class="tab" onclick="SecondTabClicked()"><a id="secondTabLink" href="#">StreetView</a></li>
					</ul>
				</div>
				<div id="streetviewDivMap"></div>
				<div id="streetviewDiv">
				</div>	
				<!--div id="tooltip">
					<div id="addressRow">
						<div id="tooltipAddress" class="tooltipLabel"></div>	
						<div id="addressRowButtons">
							<button id="shareButton">SHARE</button>
							<img id="shareImg" src="images/star.jpg"/>		
						</div>
					</div>
					<div id="houseDataDiv">
						<div id="dataLeftColumn" class="tooltipLabel">Availability:<br>Unit Type:<br>Price:<br>Beds:<br>Baths<br>Furnished<br>Realtor
						</div>
						<div id="dataRightColumn">
							<span class="tooltipData" id="tooltipAvailability"></span><br>	
							<span class="tooltipData" id="tooltipType"></span><br>	
							<span class="tooltipData" id="tooltipPrice"></span><br>	
							<span class="tooltipData" id="tooltipBeds"></span><br>	
							<span class="tooltipData" id="tooltipBaths"></span><br>	
							<span class="tooltipData" id="tooltipFurnished"></span><br>	
							<a href="#"><span class="tooltipData" id="tooltipCompany"></span></a><br>	
						</div>	
					</div>
				</div> -->
			</div>	
		<div id="topBar">	
			<img id="logo" src = "images/logo.png" alt="A2Cribs"/>
      <a id="fb_login" class="fb_login" href="<?php echo $loginUrl; ?>">Login with Facebook</a>
			<div id="logout_link" class="fb-login-button fb_login"><a href="#" id="auth-logoutlink">logout</a></div>
			<div id="topBarButtonsDiv">
				<a href="#"><img src="images/contact.png" alt="CONTACT" class="topBarButton" id="contact"></a>
			</div>
		</div>
		<div id="favoritesBar">
			<!--<div id="groupTabButton"><div id="groupIcon"></div><div id="groupTabTitle">Group</div></div>-->
			<!-- <div id="personalTabButton"><div id="personalIcon"></div><div id="personalTabTitle">Personal</div></div>-->
			<div id="favoriteActionsButton"></div>
			<div id="hideFavoritesDiv" onclick="HideFavorites()"></div>
			<div id="personalFavoritesList">
			</div>
			<!-- Favorite Template -->
			<script id="favoriteTemplate" type="text/html">
				<div id="favoriteDiv${ListingId}" class="favoriteDiv">
					<div class="favoritesAddress" id="address${ListingId}"><b>${Address}</b></div>
					<div class="removeButton" id="removeBtn${ListingId}"></div>	
					<div class="optionGrid">
						<div id="price">$${Rent}</div>
						<div id="beds">${Beds} Beds</div>
						<div id="baths">${Bathrooms} Baths</div>
						<div id="payMonth">${LeaseRange}</div>
						<div id="aptType">${UnitType}</div>
						<div id="parking"><div class="parking_selected smallIcon"></div></div>
						<div id="electric"><div class="electric_unselected smallIcon"></div></div>
						<div id="furnished"><div class="furnished_selected smallIcon"></div></div>
						<div id="ac"><div class="ac_unselected smallIcon"></div></div>
						<div id="contact">${Company}</div>
					</div>
				</div>
			</script>
		</div>
		<div id="favoritesTab" onclick="ShowFavorites()"><div id="favoritesIcon"></div><div id="numFavorites">0</div><span id="favoritesText">Favorites</span></div>
		<div id="map_canvas"></div>
		<div id="filterBoxBackground">
			<div id="filterBox">
				<form id="filterForm" method="get" action="index.php">
					<div id="priceIcon"></div>
					<input type="text" id="rentMin" class="filterRangeInputMin" readonly="readonly"/>
					<div class="sliderDiv">
	             		<div class="bar rent"></div>
	             		<div class="drag box min rent" id = "rentBoxMin"></div>
						<input type="hidden" name="minRent"/>
	             		<div class="drag box max rent" id = "rentBoxMax"></div>
						<input type="hidden" name="maxRent"/>
					</div>
					<input type="text" id="rentMax" class="filterRangeInputMax" readonly="readonly"/>
					<div id="bedIcon"></div>
					<input type="text" id="bedsMin" class="filterRangeInputMin" readonly="readonly"/>
					<div class="sliderDiv">
		             	<div class="bar beds"></div>
		             	<div class="drag box min beds" id = "bedsBoxMin"></div>
					 	<input type="hidden" name="minBeds"/>
		             	<div class="drag box max beds" id = "bedsBoxMax"></div>
					 	<input type="hidden" name="maxBeds"/>
					</div>
					<input type="text" id="bedsMax" class="filterRangeInputMax" readonly="readonly"/>
					<div id="leaseIcon"></div>
					<div class="filterOptions" id="rentTermFilter">
						<input type="checkbox" id="fallCheck" checked="checked" onclick="ApplyFilters()"/>
						<div class="filterOptionsText">Fall-Fall</div>
						<input type="checkbox" id="springCheck" checked="checked" onclick="ApplyFilters()"/>
						<div class="filterOptionsText">Spring-Spring</div>
						<input type="checkbox" id="otherCheck" checked="checked" onclick="ApplyFilters()"/>
						<div class="filterOptionsText">Other </div>
					</div>
					<div id="houseIcon"></div>
					<div class="filterOptions" id="houseTypeFilter">
						<input type="checkbox" id="houseCheck" checked="checked" onclick="ApplyFilters()"/>
						<div class="filterOptionsText">House</div>
						<input type="checkbox" id="aptCheck" checked="checked" onclick="ApplyFilters()"/>
						<div class="filterOptionsText">Apartment</div>
						<input type="checkbox" id="duplexCheck" checked="checked" onclick="ApplyFilters()"/>
						<div class="filterOptionsText">Duplex</div>
					</div>
				</form>
				<!--<form id="filterForm" method="get" action="index.php">
				<table>
					<tr>
						<td>Price: </td>
						<td><input type="text" id="rentMin" class="filterRangeInput" readonly="readonly"/></td>
						<td>
							<div class="sliderDiv">
	             	<hr class="bar rent" size=5px />
	             	<div class="drag box min rent" id = "rentBoxMin"></div>
							 	<input type="hidden" name="minRent"/>
	             	<div class="drag box max rent" id = "rentBoxMax"></div>
							 	<input type="hidden" name="maxRent"/>
							</div>
						</td>
						<td><input type="text" id="rentMax" class="filterRangeInput" readonly="readonly"/></td>
					</tr>	
					<tr>
						<td>Beds: </td>
						<td><input type="text" id="bedsMin" class="filterRangeInput" readonly="readonly"/></td>
						<td style="align:center">
							<div class="sliderDiv">
	             	<hr class="bar beds" size=5px />
	             	<div class="drag box min beds" id = "bedsBoxMin"></div>
							 	<input type="hidden" name="minBeds"/>
	             	<div class="drag box max beds" id = "bedsBoxMax"></div>
							 	<input type="hidden" name="maxBeds"/>
							</div>
						</td>
						<td><input type="text" id="bedsMax" class="filterRangeInput" readonly="readonly"/></td>
					</tr>		
				</table>
				<table>
					<tr>
						<td>Lease: </td>
						<td><input type="radio" name="leaseType" value="May-May" class="leaseTypeRadio">May-May</input></td>	
						<td><input type="radio" name="leaseType" value="Sep-Sep" class="leaseTypeRadio">Sep-Sep</input></td>		
						<td><input type="radio" name="leaseType" value="Other" class="leaseTypeRadio">Other</input></td>		
					</tr>
					<tr>
						<td>Type: </td>
						<td><input type="radio" value="House">House</input></td>	
						<td><input type="radio" value="Duplex">Duplex</input></td>		
						<td><input type="radio" value="Apartment">Apartment</input></td>		
					</tr>
				</table>
				</form> -->
			</div>
		</div> <!-- end of filterBoxBackground -->
		<!--div id="bottomBar">
		</div> -->
	</div>
	</body>

<script type="text/javascript">

favoritesListingIds = [];
northeastBound = null;
southwestBound = null;

var FavoritesManager = {	
	FavoritesList: [], 
	AddFavorite: function(newFavorite, listingid)
	{
		var favorite = {
			Address: newFavorite["address"], 
			LeaseRange: newFavorite["lease_range"],
			UnitType: newFavorite["unit_type"],
			UnitDescription: newFavorite["unit_description"],
			Beds: newFavorite["beds"],
			Bathrooms: newFavorite["bathrooms"],
			Rent: newFavorite["rent"],
			Company: newFavorite["company"],
			Electric: newFavorite["electric"],
			Water: newFavorite["water"],
			Heat: newFavorite["heat"],
			Air: newFavorite["air"],
			Parking: newFavorite["parking"],
			Furnished: newFavorite["furnished"],
			Url: newFavorite["url"],
			ListingId: listingid
		};
		this.FavoritesList.push(favorite);
		// js for all the images	



		// sort favorites list
/*		this.FavoritesList.sort(
			function(a, b)
			{
				var a_address = String(a["Address"]);
				var b_address = String(b["Address"]);
				var a_index = a_address.indexOf(" ");
				if (a_index == undefined)
					a_index = -1;
				var b_index = b_address.indexOf(" ");
				if (b_index == undefined)
					b_index = -1;

				var a_streetName = a_address.substring(a_index + 1);
				var b_streetName = b_address.substring(b_index + 1);
				return a_streetName < b_streetName;
			});	
*/	
	// Set onclick handler for Remove Button
		if (favoritesListingIds.indexOf(parseInt(listingid)) == -1)	
		{
			$.tmpl($("#favoriteTemplate"), favorite).appendTo("#personalFavoritesList");
			favoritesListingIds.push(parseInt(listingid));
		}
		$("#removeBtn" + listingid).click(
			function() 
			{
				EditFavorite(listingid, "delete");
			});

		$("#address" + listingid).click(
			function() 
			{
//				newFavorite["markerid"] = AddressToPropertiesMap[newFavorite["address"]][0]["markerid"];
					var nextIndex = 0;
					for (var i = 0; i < AddressToPropertiesMap[ListingIdToAddressMap[listingid]].length; i++)
					{
						if (parseInt(AddressToPropertiesMap[ListingIdToAddressMap[listingid]][i]["listingid"]) == parseInt(listingid))
							nextIndex = i;	
					}
				CreateInfoBubble(AddressToPropertiesMap[ListingIdToAddressMap[listingid]][nextIndex], AddressToPropertiesMap[ListingIdToAddressMap[listingid]][nextIndex]["markerid"], listingid)
			});

//		$("#favoriteTemplate").render(this.FavoritesList).appendTo("#favoritesBar");	

	}
}

FavoritesManager.FavoritesList = [];

var _startX;    // mouse starting positions 
var _startY; 
var _offsetX = 0;       // current element offset 
var _offsetY = 0; 
var _dragElement;       // needs to be passed from OnMouseDown to OnMouseMove 
var barsArray;
var _rentBar;
var _rentBoxMin;
var _rentBoxMax;
var _bedsBoxMin;
var _bedsBoxMax;

var _clickElement; 
var _oldZIndex = 0;
var output; 
var d_min; 
var d_max; 
var box_current;
var BAR_WIDTH;
var BAR_X_MIN;
var BAR_X_MAX;
var FILTER_BOX_X_MIN;
var FILTER_BOX_Y_MIN;
var FILTER_BOX_HEIGHT = 150;
var BOX_WIDTH = 12;
var BOX_WIDTH_CORRECTION = 0;
var RENT_MAX = 4000;
var BEDS_MAX = 10;

var NUM_RENT_BAR_DIVISIONS = 40;
var NUM_BEDS_BAR_DIVISIONS = 10;

//if boxes overlap
var needToDecide = false;
var initialPosition;

var elementOffset = 0;
var elementOppositeOffset = 0;

var TOOLTIP_TAB_HEIGHT = 30;
var HOUSE_DATA_DIV_HEIGHT = 135;
var TOOLTIP_HEIGHT = TOOLTIP_TAB_HEIGHT + HOUSE_DATA_DIV_HEIGHT;
var TOOLTIP_WIDTH = 295;
var TOOLTIP_PADDING = 20;
 
var TOP_MENU_HEIGHT = 50; 

InitDragDrop();
InitFilters();



</script>

<?php


function GetAccessToken()
{

}

?>

</html>
