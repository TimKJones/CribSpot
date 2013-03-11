<script type="text/javascript">

function EditFavorite(listingid, op)
{
	if (IsLoggedIn() == false)
	{
		alert("You must be logged in with Facebook to add favorites.", "Add Favorite");
		return;
	}

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

			
			var favorite = xmlDoc.getElementsByTagName("favorite");
			var successNode = favorite[0].getElementsByTagName("success");
			var success = successNode[0].textContent;	
			var new_op;
			if (op == "add")
				new_op = "add";
			else if (op == "delete")
				new_op = "delete";
			else 
				new_op = favorite[0].getElementsByTagName("new_op")[0].firstChild.nodeValue;

			if (new_op == "add" && success == "true")
			{
				var newFavorite = {}

//TODO: add all other fields copied from init.php (after making sure that code works)

				newFavorite["address"] = favorite[0].getElementsByTagName("address")[0].textContent;
				newFavorite["unit_type"] = favorite[0].getElementsByTagName("unit_type")[0].textContent;
				newFavorite["rent"] = favorite[0].getElementsByTagName("rent")[0].textContent;
				newFavorite["beds"] = favorite[0].getElementsByTagName("beds")[0].textContent;
				newFavorite["company"] = favorite[0].getElementsByTagName("company")[0].textContent;

				FavoritesManager.AddFavorite(newFavorite, listingid);
				$("#addFavoriteImg").attr("src", "images/star_pushed.png");	
			}					
			else if (new_op == "delete" && success == "true")
			{ 
				var index = favoritesListingIds.indexOf(parseInt(listingid));
				if (index != -1)
				{
					favoritesListingIds.splice(index, 1);
					$("#addFavoriteImg").attr("src", "images/star.png");
					$("#favoriteDiv" + listingid).remove();	
				}
			}
			if (success == "true")
			{	
				var count = favorite[0].getElementsByTagName("count")[0].textContent;
				$("#numFavorites").html(count);
			}
		}
	}
	
	xmlhttp.open("GET","editFavorite.php?houseid=" + listingid + "&op=" + op, true);
	xmlhttp.send();
}

function favoriteAlreadyAdded(listingid)
{
	if (!IsLoggedIn())
	{
		return false;
	}

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

			var favoriteAlreadyAdded = xmlDoc.getElementsByTagName("favoriteAlreadyAdded")[0].firstChild.nodeValue;
		
			if (favoriteAlreadyAdded == "true")
				$("#addFavoriteImg").attr("src", "images/star_pushed.png");
			
		}
	}
	
	xmlhttp.open("GET","favoriteAlreadyAdded.php?houseid=" + listingid, true);
	xmlhttp.send();
}
</script>
