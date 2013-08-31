<?php

class FeaturedListing extends AppModel {
    public $name = 'FeaturedListing';
    public $actsAs = array('Containable');
    public $belongsTo = array('Listing', 'User');
    
    public $validate = array(
        'user_id' => 'numeric',
        'listing_id' => 'numeric',
        'latitude' => 'numeric',
        'longitude' => 'numeric',
    );

    public $TAG = 'FeaturedListing'; 

    public function add($listing_id, $university_id, $date, $user_id){
        
        $featured_listing_data = array(
            'FeaturedListing' => array(
                'listing_id' => $listing_id,
                'university_id'=>$university_id,
                'user_id' => $user_id,
                'date'=>date('Y-m-d', strtotime($date)),
            )    
        );
        $this->create($featured_listing_data);
        if(!$this->save()){
            die(debug($this->validationErrors));
        }

        $featuredListing = $this->read();
        return $featuredListing;
    }

    public function getByType($listing_type){
        return $this->find('all', array("conditions"=>"Listing.listing_type=$listing_type"));
    }

    public function get($up_lat, $low_lat, $up_long, $low_long, $date){

        $this->contain('Listing', 'Listing.Marker', 'Listing.Rental', 'User');

        $conditions = array(
        'conditions' => array(
            'and' => array(
                        array(
                            // 'FeaturedListing.date = ' => $date,
                            'FeaturedListing.latitude <=' => $up_lat,
                            'FeaturedListing.latitude >=' => $low_lat,
                            'FeaturedListing.longitude <=' => $up_long,
                            'FeaturedListing.longitude >=' => $low_long
                            ),
                    )
            ),
        );
        // 'Listing.listing_id',
        //     'Marker.street_address',
        //     'Listing.Rental.min_occupancy',
        //     'Listing.Rental.max_occupancy',
        //     'Listing.Rental.start_date',
        //     'Listing.Rental.end_date',
        //     'Listing.Rental.rent',
        //     'Listing.Rental.building_type',

        return $this->find($find_type, $conditions);
    }


    // A seed value is used along with the page size to pull out a select few
    // featured listings. 
    public function getSideBarConditions($date, $university_id){

        $this->contain('Listing');

        $conditions = array(
            'and' => array(
                        array(
                            'FeaturedListing.date = ' => $date,
                            'FeaturedListing.university_id = ' => $university_id
                            ),
                    )
            );

        return $conditions;
    }

    // Given a mysql formatted date string y-m-d count the num listings on that day.
    public function countListingsOnDate($date){
        return $this->find('count', array('conditions'=>array("FeaturedListing.date"=>$date)));
    }
    // Returns true or false based on whether the following listing is featured on the provided date
    public function featuredOnDate($listing_id, $university_id, $date){
        $conditions = array('FeaturedListing.listing_id'=>$listing_id, "FeaturedListing.university_id"=>$university_id, "FeaturedListing.date"=>$date);
        return $this->hasAny($conditions);
    }

    // Takes in a number n
    // Returns upcoming dates that have at least N featuredlistings on it.
    public function getDatesWithNOrMoreListings($n, $minDate=null){
        if($minDate == null)
            $minDate = date('Y-m-d');

        $dates = $this->find('list', array(
            'fields' => array('FeaturedListing.date'),
            'conditions' => array('FeaturedListing.date >=' => $minDate),
            'group' => array("FeaturedListing.date HAVING COUNT(*) >= $n")
            )
        );
        return array_values($dates);
    }

    /*
        Returns an array  of all the featured listings for a given date
    */
    public function getByDate($date){
        /*$this->contain('Listing', 'Listing.Marker', 'Listing.Rental', 'User');
        $options = array(
            'conditions'=>array(
                    'FeaturedListing.date' => $date,
                )
            );

        return $this->find('all', $options);*/

        $this->contain();
        $listings = $this->find('all', array(
            'conditions'=>array(
                    'FeaturedListing.date' => $date,
                ),
            'fields' => array('FeaturedListing.listing_id')
        ));
        $listing_ids = array();
        foreach ($listings as $listing)
            array_push($listing_ids, intval($listing['FeaturedListing']['listing_id']));

        return $listing_ids;
    }

    /*
    
        Returns an array of dates (strings) coming up that the listing is featured
        on, there is also an optional parameter that can be set to true so that it'll 
        show all dates that the listing has been and will be featured on

    */ 

    public function getDates($listing_id, $university_id, $show_past=false){
        
        if($show_past){
            $date = date('Y-m-d', 0); //Time of Epoch
        }else{
            $date = date('Y-m-d');
        }

        $options = array(
                "conditions"=>array(
                    "FeaturedListing.listing_id = "=>$listing_id,
                    "FeaturedListing.university_id"=>$university_id,
                    "FeaturedListing.date >= " => $date,
                    ),
                "fields"=>array(
                    "FeaturedListing.date"
                    )
            );
        $dates = $this->find('list', $options);
        // $new_dates = array();
        // foreach ($dates as $date){
        //     array_push($new_dates, date('m/d/Y', strtotime($date)));
        // }
        // return $new_dates;
        return array_values($dates);
    }

    public function getForNewspaper($currentDate)
    {
        $num_days = 3;
        $listings = array();
        $date = $currentDate;
        for($i = 0; $i < $num_days; ++$i){
            $listings[$date] = array();
            $featuredListings = $this->getByDate($date);
            // Go through and add the relevant data as a new array to the listings array

            foreach ($featuredListings as $key => $fl) {
                CakeLog::write('fl', print_r($fl, true));
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
                    'url' => 'www.cribspot.com/listing/' . $fl['Listing']['listing_id']
                    );
                array_push($listings[$date],$fldata);
            }

            $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
        }

        return $listings;
    }
}

?>