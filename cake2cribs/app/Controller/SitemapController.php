<?php 
class SitemapController extends AppController{ 

    var $name = 'Sitemaps'; 
    var $uses = array('Listing', 'Image'); 
    var $helpers = array('Time');
    var $components = array('RequestHandler'); 

    function index () 
    {     
    	//debug logs will destroy xml format, make sure were not in drbug mode 
		Configure::write ('debug', 0); 

    	$listings = $this->Listing->find('all', array(
    		'conditions' => array('Listing.visible' => 1)	
    	));

    	CakeLog::write('sitemapListings', print_r($listings, true));
    	foreach ($listings as &$listing){
			$full_address = $listing["Marker"]["street_address"];
			$full_address .= " " . $listing["Marker"]["city"];
			$full_address .= " " . $listing["Marker"]["state"];
			$full_address .= " " . $listing["Marker"]["zip"];
			$full_address = str_replace(" ", "-", $full_address);
			$listing['url'] = 'listings/' . $listing['Listing']['listing_id'] . '/' . $full_address;
    	}

    	$this->set('listings', $listings);

    	$this->RequestHandler->respondAs('xml');
    } 
} 
?> 