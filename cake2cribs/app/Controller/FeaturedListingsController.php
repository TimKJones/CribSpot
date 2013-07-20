<?php
class FeaturedListingsController extends AppController {
  public $helpers = array('Html');
  public $components = array('Auth');
  public $uses = array('Listing', 'User', 'FeaturedListing');


  // This action can only be run by a user with Super Privalleges
  // such as the Michigan Daily. It'll grab all the listings from

  public function suDash(){
    //We need to make sure the user has Super User rights
    $dates = $this->FeaturedListing->getDatesWithNOrMoreListings(2);
    
    $user = $this->_getUserId();

    // if($user['User']['type'] != SUPERUSER){
    //     throw new NotFoundException();
    // }
    
    // $listings = $this->Listing->GetListingsByType(Listing::LISTING_TYPE_RENTAL);
    
    $this->set("unavaildates", json_encode($dates));

    // $this->set('listings', $listings);
  }

  public function getListings(){
    
    $up_lat = $this->request->query['up_lat'];
    $low_lat = $this->request->query['low_lat'];

    $up_long = $this->request->query['up_long'];
    $low_long = $this->request->query['low_long'];

    $day = null;
    
    // If day is null we use today's date
    if($day == null){
      $day = time();
    }
    $date = date("Y-m-d", $day);

    $listings = $this->FeaturedListing->get($up_lat, $low_lat, $up_long, $low_long, $date);

    $this->layout = 'ajax';
    $this->set('listings', $listings);

  }

  // Returns an array of date strings representing the dates
  // that are unavailable to feature a listing
  public function getUnavailableDates(){

    $dates = $this->FeaturedListing->getDatesWithNOrMoreListings(2);

    $this->layout = 'ajax';
    $this->set('response', json_encode($dates));
  } 

  // Return a json array containing all the data needed to order
  // a featured listing. This will include the following listing fields
  // listing_id, address, alt_name for property, listing_type (string)
  // as well as any dates specific to that listing that its already 
  // featured on.

  public function flOrderData(){
    $user_id = $this->_getUserId();
    $response = array();
    

    // TODO if super user (Mich Daily) fetch all listings
    
    // if (super user){
    //   blah blah blah
    // }

    $listings = $this->Listing->GetListingsByUserId($user_id);


    // Push onto the response array the specifc data mentioned above
    foreach($listings as $listing){
      $listing_id = $listing['Listing']['listing_id'];
      $address = $listing['Marker']['street_address'];
      $alt_name = $listing['Marker']['alternate_name'];
      $listing_type = $listing['Listing']['listing_type'];
      $listing_type_str = Listing::listing_type($listing['Listing']['listing_type']);
      $dates = $this->FeaturedListing->getDates($listing_id);
      
      $data = array(
          'listing_id' => $listing_id,
          'address' => $address,
          'alt_name' => $alt_name,
          'listing_type_str' => $listing_type_str,
          'listing_type' => intval($listing_type),
          'unavailable_dates'=>$dates
        );

      array_push($response, $data);

    }

    $this->layout = 'ajax';
    $this->set("response", json_encode($response));


  }

  public function all(){
    $featured_listings = $this->FeaturedListing->find('all');
    die(debug($featured_listings));
  }

  


  

}