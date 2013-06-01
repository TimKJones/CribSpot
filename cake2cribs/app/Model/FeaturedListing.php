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

    // duration in days
    public function newFL($listing, $user, $duration){
        $now = time();

        $start = $now + (60 * 60 * 24); // Start date is tomorrow
        $start_date = date("Y-m-d", $start);
        $end = $now + (60 * 60 * 24 * (1 + $duration)); // Expires duration num days from tomorrow
        $end_date = date("Y-m-d", $end);

        $latitude  = $listing['Marker']['latitude'];
        $longitude  = $listing['Marker']['longitude'];

        $type = 1; //Only one type of featured listing right now

        $featured_listing_data = array(
            'FeaturedListing' => array(
                'listing_id' => $listing['Listing']['listing_id'],
                'user_id' => $user['User']['id'],
                'start' => $start_date,
                'end' => $end_date,
                'type' => $type,
                'latitude' => $latitude,
                'longitude' => $longitude,
            )    
        );

        if(!$this->save($featured_listing_data)){
            die(debug($this->validationErrors));
        }

        $featuredListing = $this->read();

        return $featuredListing;

    }

    public function add($order_data){
        
        $listing = $this->Listing->find('first', array(
            'conditions'=>'Listing.listing_id='.$order_data->listing_id)
        );

        if($listing == null){
            CakeLog::write($this->$TAG, "Listing " . $order_data->listing_id . " not found while trying to buy a featured listing");
            return null;
        }

        $duration = $order_data->duration;   

        $now = time();

        $start = $now + (60 * 60 * 24); // Start date is tomorrow
        $start_date = date("Y-m-d", $start);
        $end = $now + (60 * 60 * 24 * (1 + $duration)); // Expires duration num days from tomorrow
        $end_date = date("Y-m-d", $end);

        $latitude  = $listing['Marker']['latitude'];
        $longitude  = $listing['Marker']['longitude'];

        $user_id = $order_data->user_id;

        $type = 1; //Only one type of featured listing right now

        $featured_listing_data = array(
            'FeaturedListing' => array(
                'listing_id' => $listing['Listing']['listing_id'],
                'user_id' => $user_id,
                'start' => $start_date,
                'end' => $end_date,
                'type' => $type,
                'latitude' => $latitude,
                'longitude' => $longitude,
            )    
        );

        if(!$this->save($featured_listing_data)){
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
                            'FeaturedListing.start <= ' => $date,
                            'FeaturedListing.end >= ' => $date,
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