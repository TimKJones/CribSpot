<script type="text/javascript">

var FriendsManager = {
	FriendsList: [], 	
	AddFriend: function (friendId, name, url)
	{
		var friend = {
			Friendid: friendId, 
			Name: name, 
			Url: url	
		};	
		this.FriendsList.push(friend);
		$.tmpl($("#friendTemplate"), friend).appendTo("#friendsList");
	}
}

function FacebookLogin()
{
	var xmlhttp;
	if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  	xmlhttp=new XMLHttpRequest();
  }
	else
  {// code for IE6, IE5
  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }

	xmlhttp.onreadystatechange=function()
  {
  	if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
			var xml = xmlhttp.responseText;
			var xmlDoc;
			if (window.DOMParser)
			{
				var parser = new DOMParser();
				xmlDoc=parser.parseFromString(xml,"text/xml");
			}		
			else // Internet Explorer
  		{
  			xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
  			xmlDoc.async=false;
  			xmlDoc.loadXML(xml); 
  		}
			
			var houseList = xmlDoc.getElementsByTagName("addressList");	
			var houseInfo = houseList[0].getElementsByTagName("houseInfo");
			var count = houseList[0].getElementsByTagName("count")[0].firstChild.nodeValue;
			$("#numFavorites").html(count);
			var nextFavorite = [];
			for (i = 0; i < houseInfo.length; i++)
			{
				var address, lease_range, unit_type, unit_description, beds, bathrooms, rent, company, electric, water, heat, air, parking, furnished, url; 
	
				address = houseInfo[i].getElementsByTagName("address")[0].firstChild.nodeValue;
			
				lease_range = houseInfo[i].getElementsByTagName("lease_range")[0].firstChild.nodeValue;
				if (lease_range == "Other")
					lease_range = "N/A";
			
				unit_type = houseInfo[i].getElementsByTagName("unit_type")[0].firstChild.nodeValue;
				if (unit_type == "?")
					unit_type = "N/A";
			
				unit_description = houseInfo[i].getElementsByTagName("unit_description")[0].firstChild.nodeValue;
				beds = houseInfo[i].getElementsByTagName("beds")[0].firstChild.nodeValue;
				if (beds == "?")
					beds = "N/A";
				bathrooms = houseInfo[i].getElementsByTagName("bathrooms")[0].firstChild.nodeValue;	
				if (bathrooms == "?")
					bathrooms = "N/A";
				rent = numberWithCommas(houseInfo[i].getElementsByTagName("rent")[0].firstChild.nodeValue);
				company = houseInfo[i].getElementsByTagName("company")[0].firstChild.nodeValue;
			
				electric = houseInfo[i].getElementsByTagName("electric")[0].firstChild.nodeValue;
				if (electric == "?")
					electric = "N/A";
			
				water = houseInfo[i].getElementsByTagName("water")[0].firstChild.nodeValue;
				if (water == "?")
					water = "N/A";

				heat = houseInfo[i].getElementsByTagName("heat")[0].firstChild.nodeValue;
				if (heat == "?")
					heat = "N/A";

				air = houseInfo[i].getElementsByTagName("air")[0].firstChild.nodeValue;
				if (air == "?")
					air = "N/A";

				parking = houseInfo[i].getElementsByTagName("parking")[0].firstChild.nodeValue;
				if (parking == "?")
					parking = "N/A";

				furnished = houseInfo[i].getElementsByTagName("furnished")[0].firstChild.nodeValue;
				if (furnished == "?")
					furnished="N/A";
	
				nextFavorite["address"] = address; 
				nextFavorite["lease_range"] = lease_range; 
				nextFavorite["unit_type"] = unit_type;
				nextFavorite["unit_description"] = unit_description;
				nextFavorite["beds"] = beds;
				nextFavorite["bathrooms"] = bathrooms;
				nextFavorite["rent"] = rent;
				nextFavorite["company"] = company;
				nextFavorite["electric"] = electric;
				nextFavorite["water"] = water;
				nextFavorite["heat"] = heat;
				nextFavorite["air"] = air;
				nextFavorite["parking"] = parking;
				nextFavorite["furnished"] = furnished;
				nextFavorite["url"] = url;
				var listingid = houseInfo[i].getElementsByTagName("listingid")[0].firstChild.nodeValue;
				FavoritesManager.AddFavorite(nextFavorite, listingid);
			}			
		}
	}
	xmlhttp.open("GET","loadFavorites.php?", true);
	xmlhttp.send();
}

function FacebookLogout()
{
	for (var i = 0; i < FavoritesManager.FavoritesList.length; i++)
	{
		var element = $("#favoriteDiv" + FavoritesManager.FavoritesList[i]["ListingId"])
		element.remove();
	}

	$("#numFavorites").html(0);
	$("#addFavoriteImg").attr("src", "images/star.png");

	FavoritesManager.FavoritesList = [];
	favoritesListingIds = [];
	
	document.getElementById('logout_link').style.visibility="hidden";
	document.getElementById('fb_login').style.visibility="visible";

}

function LoadFriendsList()
{
	FB.api(
		{
			method: 'fql.multiquery',
			queries: {
				query1: 'SELECT name, uid from user where uid in (select uid2 FROM friend WHERE uid1=me())',
				query2: 'SELECT url, id from profile_pic where id in (select id from #query1)'
			}
		}, 
		function(data)
		{
			var names = data[0].fql_result_set;
			var urls = data[1].fql_result_set;

			for (var i = 0; i < names.length; i++)
			{
				FriendsManager.AddFriend(i, names[i]["name"], urls[i]["url"]);
			}
		}
	);	
}

function IsLoggedIn()
{
	return FB.getUserID() != 0;
}

</script>
