<?php
class FeaturedListingsController extends AppController {
  public $helpers = array('Html');
  public $components = array('Auth');
  public $uses = array('Listing', 'User', 'FeaturedListing', 'University', 'NewspaperAdmin', 'Marker');
  public function beforeFilter()
  {
    parent::beforeFilter();
    $this->Auth->allow('cycleIds');
  }
  
  const ARBITRARY_SEED_CAP_VALUE = 100;
  const ELIGIBLE_UNI_RADIUS_MILES = 20;
  
  public function cycleIds($university_id, $limit){
    $this->layout = 'ajax';
    $listings_data = $this->cycleFeaturedListings($limit, $university_id);
    $listing_ids = array();
    foreach ($listings_data as $listing) {
      array_push($listing_ids, $listing['Listing']['listing_id']);
    }

    $this->set('response', json_encode($listing_ids));

  }

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

    // $this->layout = 'ajax';
    $this->set('listings', $listings);

  }

  /*
  Returns an array of featured listings, that get cycled
  each time this function is invoked
  */
  private function cycleFeaturedListings($limit, $university_id){
    $day = time();
    $date = date("Y-m-d", $day);

    $conditions = $this->FeaturedListing->getSideBarConditions($date, $university_id);
    
    /* Get all featured listings for today */
    $listings_data = $this->FeaturedListing->find('all', array(
      'conditions' => $conditions,
      'fields' => 'Listing.listing_id'
    ));

    /* increment the seed */
    $seed = Cache::read('featured_listings_seed');
    if ($seed === null)
      $seed = 0;

    if (count($listings_data) !== 0)
      $seed = ($seed+1)%count($listings_data);
    else
      $seed = 0;
    Cache::write('featured_listings_seed', $seed);

    /* Re-order featured listings based on seed value */
    $firstPartOrderedListings = array_slice($listings_data, $seed);
    $secondPartOrderedListings = array_slice($listings_data, 0, $seed);
    $orderedListings = array_merge($firstPartOrderedListings, $secondPartOrderedListings);
    CakeLog::write('orderedlistings', 'firstpart: ' . print_r($firstPartOrderedListings, true));
    CakeLog::write('orderedlistings', 'secondpart: ' . print_r($secondPartOrderedListings, true));
    CakeLog::write('orderedlistings', 'entire list: ' . print_r($orderedListings, true));
    return $orderedListings;
  }

  public function getListingsForNPA(){
    $user_id = $this->_getUserId();
    $newspaper_admin = $this->NewspaperAdmin->getByUserId($user_id);

    if($newspaper_admin == null){
      throw new NotFoundException();
    }

    


  }

  // Returns an array of date strings representing the dates
  // that are unavailable to feature a listing
  public function getUnavailableDates(){
    
    $user_id = $this->_getUserId();
    $newspaper_admin = $this->NewspaperAdmin->getByUserId($user_id);
    if($newspaper_admin != null){
      //newspaper admin so we fetch all the listings in a ELIGIBLE_UNI_RADIUS radius
      //of their campus center
      $uni_id = $newspaper_admin['NewspaperAdmin']['university_id'];
      $uni = $this->University->findById(intval($uni_id));
      $markers = $this->Marker->getNear($uni['University']['latitude'], $uni['University']['longitude'], self::ELIGIBLE_UNI_RADIUS_MILES);
      $listing_ids = array();
      foreach ($markers as $key => $marker) {

        $ids = $this->Listing->GetListingIdsByMarkerId($marker['Marker']['marker_id']);
        foreach ($ids as $id){
           array_push($listing_ids, $id);
        }
         
      }

    }else{
      //non admin
      $listing_ids = $this->Listing->GetListingIdsByUserId($user_id);
    }

    

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
    specific to the university based on pricing. As well as an
    array of dates that the listing is already listed at that 
    univesrity for.
  */

  public function getUniDataForListing($listing_id){
    


    $user_id = $this->_getUserId();
    $newspaper_admin = $this->NewspaperAdmin->getByUserId($user_id);

    if($newspaper_admin == null){
      if(!$this->Listing->UserOwnsListing($listing_id, $user_id)){
        throw new NotFoundException();
      }
    }else{
      if(!$this->Listing->ListingExists($listing_id)){
        throw new NotFoundException();
      }
    }
    
    $listing = $this->Listing->Get($listing_id);
    $lat = $listing['Marker']['latitude'];
    $lon = $listing['Marker']['longitude'];

    $unis = $this->University->getUniversitiesAround($lat, $lon, 15);
    $response = array();
    foreach ($unis as $uni) {
      
      //TODO get unique price for each uni
      $uni_id = $uni['University']['id'];
      
      if($newspaper_admin != null){
        if($newspaper_admin['NewspaperAdmin']['university_id'] != $uni_id){
          //can't feature at a uni they aren't linked to.
          continue;
        }

        $wd_price = $we_price = 0;
      }else{
        $wd_price = 15;
        $we_rice = 5;
      }


      array_push($response, array(
          'name' => $uni['University']['name'],
          'university_id' => $uni_id,
          'weekend_price' => $wd_price,
          'weekday_price' => $we_price,
          'unavailable_dates' => $this->FeaturedListing->getDates($listing_id, $uni_id),
        ));
    }

    

    $this->layout = 'ajax';
    $this->set('response', json_encode($response));    



  }


  /*
  For a newspaper admin to fetch the featured listings
  they need to post at this url providing their secret token

  */

  public function newspaper(){
    if(!$this->request->isPost()){
      throw new NotFoundException();
    }

    $secret_token = $this->request->data('secret_token');

    if($secret_token == null){
      throw new NotFoundException();
    }

    $user_id = $this->_getUserId();
    $newspaper_admin = $this->NewspaperAdmin->getByUserId($user_id);

    if($newspaper_admin == null){
      throw new NotFoundException();
    }

    if($secret_token != $newspaper_admin['NewspaperAdmin']['secret_token']){
      throw new NotFoundException();
    }

    $listings = $this->FeaturedListing->getForNewspaper($newspaper_admin['NewspaperAdmin']['university_id']);
    $this->layout = 'ajax';
    $this->set("response", json_encode($listings));
    
  }

}