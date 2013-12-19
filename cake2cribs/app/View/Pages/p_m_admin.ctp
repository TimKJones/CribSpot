<?php
foreach ($loginLinks as $link) {
	echo "<a href='".$link['link']."'>".$link['company_name'].' - '.$link['city'].' - '.$link['state']."</a><br/>";
}

?>