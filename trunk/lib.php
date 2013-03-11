<?php
function get_header()
{

echo("
<div class=\"top_section\">
		<div id=\"logo_wrap\">
			<img src=\"../images/logo.png\" alt=\"UBid\" id=\"logo\"/>
		</div>

		
		<div class=\"nav_bar_wrap\">
			<ul\"\">
				<li class=\"top_nav mid\"><a href=\"#\" class=\"no_underline\">My UBid</a>&nbsp<img class=\"prof_pic\" id=\"prof_pic\" src=\"../images/tim_prof_pic.jpg\"/>&nbsp</li>
				<li class=\"top_nav mid\"><a href=\"#\" class=\"no_underline\">Help</a>&nbsp&nbsp</li>
				<li class=\"top_nav\"><a href=\"#\" class=\"no_underline\">About</a>&nbsp&nbsp</li>
			</ul>
		</div>
</div>

");
}

function get_searchbar()
{
echo("
<div id=\"search_bar\">		
		<div id=\"search_box_wrap\">
			<form action=\"/ubid/trunk/search_results/results.php\" id=\"home_search\">	
			<input type=\"text\" name=\"q\" class=\"search_bar_align\" id=\"search_input\"/>
			<select class=\"search_bar_align\" id=\"search_cat\">
				<option>All Categories</option>
				<option>Tickets</option>
				<option>Textbooks</option>
				<option>Subletting</option>
				<option>Everything Else</option>
			</select>		
			<div>
				<input type=\"submit\" class=\"search_bar_align\" id=\"search_submit\" value=\"Search!\"/>
			</div>
		</div>	
</div>
");
}

?>
