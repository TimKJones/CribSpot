<?php
class FeaturedListingsController extends AppController {
  public $helpers = array('Html');
  public $components = array('Auth');
  public $uses = array('Listing', 'User', 'FeaturedListing', 'University');

  
  const ARBITRARY_SEED_CAP_VALUE = 100;
  const ELIGIBLE_UNI_RADIUS_MILES = 20;
  
  //Returns a list view of featured listing items
  //the fls selected change every time the user loads
  //the page. I use sessions to store data so I can cycle through

  //along with the listing search parameters based on location
  //there is a limit variable and we use a session variable to hold
  //a seed
  
  public function getSideBarData(){

    $limit = $this->request->query['limit'];
    $listings_data = $this->cycleFeaturedListings($limit);
    

    // if(count($listings_data) < $limit){
    //   // There aren't enough featuerd listings to populate the entire sidebar

    // }
    // debug($listings_data);
    // Going to build a custom array to pass to the View using the data in the listings_data
    $listings = array();
    
    foreach($listings_data as $listing){
      $start_date = $listing['Listing']['Rental']['start_date'];
      $end_date = $listing['Listing']['Rental']['end_date'];

      $start_time = strtotime($start_date);
      $end_time = strtotime($end_date);

      $start_month_str = date("M", $start_time);
      $end_month_str = date("M", $end_time);

      $time_dif = abs($start_time - $end_time);
      $num_months = intval(ceil($time_dif/ (30*60*60*24)));

      // echo $listing['FeaturedListing']['id'];

      $_listing = array(
          'address' => $listing['Listing']['Marker']['street_address'],
          'min_occup' => $listing['Listing']['Rental']['min_occupancy'],
          'max_occup' => $listing['Listing']['Rental']['max_occupancy'],
          'rent' => $listing['Listing']['Rental']['rent'],
          'start_month' => $start_month_str,
          'end_month' => $end_month_str,
          'num_months' => $num_months,
        );

      array_push($listings, $_listing);
    }


    // die(debug($listings));
    // $listings = $this->FeaturedListing->find('all');

    // $this->layout = 'ajax';
    $this->set('listings', $listings);

  }

  /*
  Returns an array of featured listings, that get cycled
  each time this function is invoked
  */
  private function cycleFeaturedListings($limit){
    $day = time();
    //TODO get date relative to users time zone

    $date = date("Y-m-d", $day);

    // Get the session data so we we know what data to get

    $seed = $this->Session->read('FeaturedListing.SideBarSeed');
    
    if($seed == null){
      //generate a psuedo random seed.
      $seed = rand(0, self::ARBITRARY_SEED_CAP_VALUE);
    }
    //increment the seed
    $seed = ($seed+1)%self::ARBITRARY_SEED_CAP_VALUE;
    $this->Session->write('FeaturedListing.SideBarSeed', $seed);
    $page = ($seed % ($limit+1)) + 1;
    $conditions = $this->FeaturedListing->getSideBarConditions($date);
    // Paginate is not available in the model so it has to be in the controller
    $this->paginate = array(
                'conditions' => $conditions,
                'page' => $page,
                'limit' => $limit,
                'order' => array('FeaturedListing.id' => 'desc'),
            );
    
    $listings_data = $this->paginate('FeaturedListing');
    return $listings_data;

  }

  // Returns an array of date strings representing the dates
  // that are unavailable to feature a listing
  public function getUnavailableDates(){
    $listing_ids = $this->Listing->GetListingIdsByUserId($this->_getUserId());
    $listing_dates = array();
    foreach($listing_ids as $listing_id){
      $listing_dates[$listing_id] = $this->FeaturedListing->getDates($listing_id);
    }

    $full_dates = $this->FeaturedListing->getDatesWithNOrMoreListings(2);
    $response = array(
      'full_dates' => $full_dates,
      'listing_dates' => $listing_dates,
      );
    $this->layout = 'ajax';
    $this->set('response', json_encode($response));
  } 

  /*
    Returns a JSON array containing the universities the listing
    is eligable to be featured at mapped to the pricing rates 
    specific to the university based on pricing.
  */

  public function getUniPricingForListing($listing_id){
    


    // TODO Add Newspaper Admin code to pull eligable uni's based
    // on their requirements and make the cost 0 to be safe.
    
    $user_id = $this->_getUserId();
    
    if(!$this->Listing->UserOwnsListing($listing_id, $user_id)){
      throw new NotFoundException();
    }

    $listing = $this->Listing->Get($listing_id);
    $lat = $listing['Marker']['latitude'];
    $lon = $listing['Marker']['longitude'];

    $unis = $this->University->getUniversitiesAround($lat, $lon, 15);
    $response = array();
    foreach ($unis as $uni) {
      //TODO get unique price for each uni

      array_push($response, array(
          'name' => $uni['University']['name'],
          'university_id' => $uni['University']['id'],
          'weekend_price' => 5,
          'weekday_price' => 15,
        ));
    }

    

    $this->layout = 'ajax';
    $this->set('response', json_encode($response));    



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
          'marker_id' => $listing['Marker']['marker_id'],
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