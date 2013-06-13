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
        
        $listing = $this->Listing->find('first', array(
            'conditions'=>'Listing.listing_id='.$listing_id)
        );

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





}

?>