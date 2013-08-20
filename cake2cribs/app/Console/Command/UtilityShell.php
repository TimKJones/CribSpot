<?php

class UtilityShell extends AppShell 
{
	public $uses = array('Listing');

    public function main() {
        
    }

    /*
	Function called periodically (initially every day at midnight) to generate a new sitemap
    */
    public function generate_sitemap() {
    	App::import('Xml');
    	$xml = array();
    }
}

?>