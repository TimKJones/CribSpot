<?php
class FeaturedListingsController extends AppController {
  public $helpers = array('Html');
  public $components = array('Auth');
  public $uses = array('Listing', 'User', 'FeaturedListing');



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

  

  public function create(){
    
    if($this->request->isPost()){
      
      $listing_id = $this->request->data['listing_id'];
      $duration = $this->request->data['duration'];  
      $options['conditions'] = array('Listing.listing_id'=>$listing_id);
      $listing = $this->Listing->find('first', $options);
      $user = $this->User->get($this->Auth->User('id'));

      $featured_listing = $this->FeaturedListing->newFL($listing, $user, $duration);

      $this->redirect('/featuredListings/all');
    }
  }

  public function all(){
    $featured_listings = $this->FeaturedListing->find('all');
    die(debug($featured_listings));
  }

  public function test(){}


  

}