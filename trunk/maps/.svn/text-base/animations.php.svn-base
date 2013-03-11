<script type="text/javascript">
function ShowFavorites()
{
	var favoritesBar = document.getElementById("favoritesBar");
	var favoritesTab = document.getElementById("favoritesTab");
	favoritesBar.style.left = -350 + 'px';
	favoritesBar.style.visibility = "visibile";

  $('#favoritesBar').animate({
    left: '+=350',
  	}, 500, function() {
			hideFavoritesDiv.innerHTML = "";
  });
  	$('#favoritesTab').animate({
    left: '-=31',
  	}, 500, function() {
  });
	
	favoritesBar.style.visibility="visible";	
}

function HideFavorites()
{
	var favoritesBar = document.getElementById("favoritesBar");
	var favoritesTab = document.getElementById("favoritesTab");
	var hideFavoritesDiv = document.getElementById("hideFavoritesDiv");

	hideFavoritesDiv.innerHTML = "";
	favoritesTab.style.visibility = "visible";

  $('#favoritesBar').animate({
    left: '-=350',
  	}, 500, function() {
			favoritesTab.style.visibility = "visible";
  });

	$('#favoritesTab').animate({
    left: '+=31',
  	}, 500, function() {
  });

	
}

</script>
