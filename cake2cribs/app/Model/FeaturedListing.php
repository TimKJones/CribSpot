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

    public function add($listing_id, $date, $user_id){
        
        $listing = $this->Listing->Get($listing_id);
        
        if($listing == null){
            CakeLog::write($this->$TAG, "Listing " . $listing_id . 
                " not found while trying to buy a featured listing");
            return null;
        }

        $latitude  = $listing['Marker']['latitude'];
        $longitude  = $listing['Marker']['longitude'];

        $type = 1; //Only one type of featured listing right now

        $featured_listing_data = array(
            'FeaturedListing' => array(
                'listing_id' => $listing['Listing']['listing_id'],
                'street_address' => $listing['Marker']['street_address'],
                'user_id' => $user_id,
                'date'=>date('Y-m-d', strtotime($date)),
                'type' => $type,
                'latitude' => $latitude,
                'longitude' => $longitude,
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

        $this->contain('Listing', 'Listing.Marker', 'User');
        $conditions = array(
        'conditions' => array(
            'and' => array(
                        array(
                            'FeaturedListing.date = ' => $date,
                            'FeaturedListing.latitude <=' => $up_lat,
                            'FeaturedListing.latitude >=' => $low_lat,
                            'FeaturedListing.longitude <=' => $up_long,
                            'FeaturedListing.longitude >=' => $low_long
                            ),
                    )
            )
        );

        return $this->find('all', $conditions);

    }

    // Given a mysql formatted date string y-m-d count the num listings on that day.
    public function countListingsOnDate($date){
        return $this->find('count', array('conditions'=>array("FeaturedListing.date"=>$date)));
    }
    // Returns true or false based on whether the following listing is featured on the provided date
    public function featuredOnDate($listing_id, $date){
        $conditions = array('FeaturedListing.listing_id'=>$listing_id, "FeaturedListing.date"=>$date);
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
    
        Returns an array of dates (strings) coming up that the listing is featured
        on, there is also an optional parameter that can be set to true so that it'll 
        show all dates that the listing has been and will be featured on

    */ 

    public function getDates($listing_id, $show_past=false){
        
        if($show_past){
            $date = date('Y-m-d', 0); //Time of Epoch
        }else{
            $date = date('Y-m-d');
        }

        $options = array(
                "conditions"=>array(
                    "FeaturedListing.listing_id = "=>$listing_id,
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





}

?>