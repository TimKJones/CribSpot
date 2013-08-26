<?php 
class SitemapController extends AppController{ 

    var $name = 'Sitemaps'; 
    var $uses = array('Listing', 'Image', 'University'); 
    var $helpers = array('Time');
    var $components = array('RequestHandler'); 

    function index () 
    {     
    	//debug logs will destroy xml format, make sure were not in drbug mode 
		Configure::write ('debug', 0); 

    	$listings = $this->Listing->find('all', array(
    		'conditions' => array('Listing.visible' => 1)	
    	));

        $universities = $this->University->find('all', array(
            'contains' => array('University')
        ));

    	foreach ($listings as &$listing){
			$full_address = $listing["Marker"]["street_address"];
			$full_address .= " " . $listing["Marker"]["city"];
			$full_address .= " " . $listing["Marker"]["state"];
			$full_address .= " " . $listing["Marker"]["zip"];
			$full_address = str_replace(" ", "-", $full_address);
			$listing['url'] = 'listings/' . $listing['Listing']['listing_id'] . '/' . $full_address;
    	}

        foreach ($universities as &$university){
            $school_name = str_replace(" ", "_", $university['University']['name']);
            $university['url'] = urlencode('rental/' . $school_name);
        }

    	$this->set('listings', $listings);
        $this->set('universities', $universities);
        CakeLog::write('universities_sitemap', print_r($universities, true));

    	$this->RequestHandler->respondAs('xml');
    } 
} 
?> 