<?php
class FeaturedListingsController extends AppController {
  public $helpers = array('Html');
  public $components = array('Auth');
  public $uses = array('Listing', 'User', 'FeaturedListing');


  // This action can only be run by a user with Super Privalleges
  // such as the Michigan Daily. It'll grab all the listings from

  public function suDash(){
    //We need to make sure the user has Super User rights

    $user = $this->User->get($this->Auth->User('id'));

    // if($user['User']['type'] != SUPERUSER){
    //     throw new NotFoundException();
    // }
    
    // $listings = $this->Listing->GetListingsByType(Listing::LISTING_TYPE_RENTAL);
    


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

  public function all(){
    $featured_listings = $this->FeaturedListing->find('all');
    die(debug($featured_listings));
  }

  


  

}