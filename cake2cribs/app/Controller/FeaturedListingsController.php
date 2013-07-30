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

  //Returns a list view of featured listing items
  //the fls selected change every time the user loads
  //the page. I use sessions to store data so I can cycle through

  //along with the listing search parameters based on location
  //there is a limit variable and we use a session variable to hold
  //a seed


  const ARBITRARY_SEED_CAP_VALUE = 100;

  public function getListings(){


      
    $up_lat = $this->request->query['up_lat'];
    $low_lat = $this->request->query['low_lat'];

    $up_long = $this->request->query['up_long'];
    $low_long = $this->request->query['low_long'];

    $day = null;

    $limit = $this->request->query['limit'];
    
    // If day is null we use today's date
    if($day == null){
      $day = time();
    }
    
    //TODO get date relative to users time zone

    $date = date("Y-m-d", $day);

    // echo $date;

    // Get the session data so we we know what data to get

    $seed = $this->Session->read('FeaturedListing.GetListingSeed');
    
    if($seed == null){
      //generate a psuedo random seed.
      $seed = rand(0, self::ARBITRARY_SEED_CAP_VALUE);
    }

    //increment the seed
    $seed = ($seed+1)%self::ARBITRARY_SEED_CAP_VALUE;

    $this->Session->write('FeaturedListing.GetListingSeed', $seed);

    $page = ($seed % ($limit+1)) + 1;

    $conditions = $this->FeaturedListing->cycleConditions($up_lat, $low_lat, $up_long, $low_long, $date);

    $this->paginate = array(
                'conditions' => $conditions,
                'page' => $page,
                'limit' => $limit,
                'order' => array('FeaturedListing.id' => 'desc'),
            );
    
    $listings_data = $this->paginate('FeaturedListing');
    // debug($listings_data);

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