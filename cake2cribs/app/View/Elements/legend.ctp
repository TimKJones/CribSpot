<style type="text/css">
	#legend
	{
		background-color: white;
		position: absolute;
		bottom: 0;
		right: 0;
		z-index: 1;
		box-shadow: 0px 0px 5px #B8B8B8;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
		margin: 10px 10px 25px 10px;
		padding: 5px;
	}
	#legend .legend_text
	{
		font-size: 12px;
		font-family: 'Helvetica';
		display: inline-block;
		font-weight: bold;
	}
	#legend .legend_dot
	{
		height: 10px;
		width: 10px;
		background-image: url('/img/dots/dot_sprite.png?v=2');
		display: inline-block;
	}
	#legend .legend_dot.leased
	{
		background-position: 0 -30px;
	}
	#legend .legend_dot.available
	{
		background-position: 0 0;
	}
	#legend .legend_dot.unknown
	{
		background-position: 0 -90px;
	}
	#legend .legend_dot.schedule
	{
		background-position: 0 -60px;
	}
</style>

<div id="legend">
	<div class="legend_dot available"></div>
	<div class="legend_text">Available</div>
	<br>
	<div class="legend_dot leased"></div>
	<div class="legend_text">Leased</div>
	<br>
	<div class="legend_dot schedule"></div>
	<div class="legend_text">Schedule Now</div>
	<br>
	<div class="legend_dot unknown"></div>
	<div class="legend_text">Unknown</div>
</div>
