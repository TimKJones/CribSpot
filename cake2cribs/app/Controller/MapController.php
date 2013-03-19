<?php

class MapController extends AppController {
  public $helpers = array('Html', 'GoogleMap', 'Js');
  public $components = array('RequestHandler');
  public $uses = array('Marker', 'Listing', 'School', 'Sublet');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('LoadMarkers');
    $this->Auth->allow('index');
    $this->Auth->allow('sublet');
    $this->Auth->allow('InitFilterValues');
    $this->Auth->allow('ViewListing');
  }

  public function index() {	
    $this->set('ListingTooltip', $this->Listing->getTooltipVariables());
    $this->InitFilterValues();
  }

  public function sublet($school_name = null, $address = null, $sublet_id = null)
  {
    if ($sublet_id != null)
    {
      $this->set("listing_id_to_open", $sublet_id);
      $subletData = $this->Sublet->getSubletData($sublet_id);
      if (array_key_exists("Sublet", $subletData) && array_key_exists("marker_id", $subletData['Sublet']))
        $marker_id_to_open = $subletData['Sublet']['marker_id']; 
      else
        $marker_id_to_open = null;

      $this->set("marker_id_to_open", $marker_id_to_open);
      $this->set("sublet_data_for_tooltip", $subletData);
    }

    $this->InitFilterValues();
  }

  public function ViewListing($listing_id = null)
  {
    if (!$listing_id)
      $this->redirect(array('controller' => 'map', 'action' => 'index'));
  }

  public function LoadMarkers($school_id) {
    $target_lat_long = $this->School->getTargetLatLong($school_id);
    $markers = $this->Marker->getAllMarkers($target_lat_long);
    $this->layout = 'ajax';
    $this->set('response', $markers);
  }

  public function InitFilterValues()
  {
    $this->Session->write('minRent', 0);
    $this->Session->write('maxRent', 999999);
    $this->Session->write('minBeds', 0);
    $this->Session->write('maxBeds', 999999);
    $this->Session->write('lease_fall', true);
    $this->Session->write('lease_spring', true);
    $this->Session->write('lease_other', true);
    $this->Session->write('house', true);
    $this->Session->write('apartment', true);
    $this->Session->write('duplex', true);
    /*
    TODO: ADD NEW FILTER VALUES HERE
    */
  }

  }