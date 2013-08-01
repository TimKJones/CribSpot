<?php 

class FLEmailServiceShell extends AppShell{
    public $uses = array('User', 'FeaturedListing');

    public function main(){
        $this->out('Hello World.');
    }

    // Fetch all the listings that will be featured over the next 3 days
    // and email them out to people.


    public function emailListings(){
        // Set timezone
        date_default_timezone_set('UTC');
        // Start date
        $date = date('Y-m-d');
        // End date
        $num_days = 3;
        $listings = array();
        for($i = 0; $i < $num_days; ++$i){
            $listings[$date] = array();
            $featuredListings = $this->FeaturedListing->getByDate($date);
            // Go through and add the relevant data as a new array to the listings array

            foreach ($featuredListings as $key => $fl) {
                $fldata = array(
                    'address' => $fl['Listing']['Marker']['street_address'],
                    'city' => $fl['Listing']['Marker']['city'],
                    'state'=> $fl['Listing']['Marker']['state'],
                    'zip'=> $fl['Listing']['Marker']['zip'],
                    'beds'=>$fl['Listing']['Rental']['beds'],
                    'baths'=>$fl['Listing']['Rental']['baths'],
                    'rent'=>$fl['Listing']['Rental']['rent'],
                    'highlights'=>$fl['Listing']['Rental']['highlights'],
                    'description'=>$fl['Listing']['Rental']['description'],
                    'contact_email'=>$fl['Listing']['Rental']['contact_email'],
                    'contact_phone'=>$fl['Listing']['Rental']['contact_phone'],
                    );
                array_push($listings[$date],$fldata);
            }

            $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
        }      

        $template_data = array("listings"=>$listings);
        
        $subject = "Featured Listings For The Next $num_days Days";
        $this->_emailUser("mikenike192@gmail.com", $subject, "featured_listings", $template_data);

    }


}

?>