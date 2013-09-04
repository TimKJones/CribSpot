<?php 

class FLEmailServiceShell extends AppShell{
    public $uses = array('User', 'FeaturedListing', 'Listing');

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
            $listing_ids = $this->FeaturedListing->getByDate($date);
            $featuredListings = $this->Listing->find('all', array(
                'conditions' => array(
                    'Listing.listing_id' => $listing_ids
                )
            ));
            // Go through and add the relevant data as a new array to the listings array
            foreach ($featuredListings as $key => $fl) {
                $fldata = array(
                    'listing_id' => $fl['Listing']['listing_id'],
                    'address' => $fl['Marker']['street_address'],
                    'beds'=>$fl['Rental']['beds'],
                    'baths'=>$fl['Rental']['baths'],
                    'rent'=>$fl['Rental']['rent'],
                    'highlights'=>$fl['Rental']['highlights'],
                    'description'=>$fl['Rental']['description'],
                    'contact_email'=>$fl['Rental']['contact_email'],
                    'contact_phone'=>$fl['Rental']['contact_phone'],
                    'listing_url'=>'www.cribspot.com/listing/' . $fl['Listing']['listing_id']   
                    );
                $fldata['primary_image_url'] = '';
                if (array_key_exists('Image', $fl)){
                    foreach ($fl['Image'] as $image){
                        if (array_key_exists('is_primary', $image) && intval($image['is_primary']) === 1)
                            $fldata['primary_image_url'] = 'www.cribspot.com/' . $image['image_path'];
                    }
                }

                if (!empty($fl['Marker']['alternate_name']))
                    $fldata['address'] = $fl['Marker']['alternate_name'];

                array_push($listings[$date],$fldata);
            }

            $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
        }      

        $template_data = array("listings"=>$listings);
        $month = date('F');
        $day = date('j') - 1;
        $year = date('Y');
        $subject = "Featured Listings Report for " . $month.' '.$day.', '.$year;
        $recipient = Configure::read('FEATURED_LISTINGS_REPORT_RECIPIENT');
        $this->_emailUser($recipient, $subject, "featured_listings", $template_data);
    }
}

?>