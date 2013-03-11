var favoritesTab, favoritesTab;

a2CribsFavoritesTab = (function() {

	function a2CribsFavoritesTab()
	{
	}

	a2CribsFavoritesTab.prototype.groupClicked = function() {
		document.getElementById('groupTabButton').style.zIndex = "4";
		document.getElementById('personalTabButton').style.zIndex = "1";
		document.getElementById('groupFavoritesList').style.display = 'block';
		document.getElementById('personalFavoritesList').style.display = 'none';
	}

	a2CribsFavoritesTab.prototype.personalClicked = function() {
		document.getElementById('personalTabButton').style.zIndex = "4";
		document.getElementById('groupTabButton').style.zIndex = "1";
		document.getElementById('personalFavoritesList').display = 'block';
		document.getElementById('groupFavoritesList').display = 'none';
	}
}
)();

//favoritesTab = new a2CribsFavoritesTab();
