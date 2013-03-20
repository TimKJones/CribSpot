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
    $marker_id_to_open = -1;
    $subletData = -1;
    if ($sublet_id != null)
    {
      $this->set("listing_id_to_open", $sublet_id);
      $subletData = $this->Sublet->getSubletData($sublet_id);
      if (array_key_exists("Sublet", $subletData) && array_key_exists("marker_id", $subletData['Sublet']))
        $marker_id_to_open = $subletData['Sublet']['marker_id'];  
    }
    else
      $marker_id_to_open = -2;

    $this->set("marker_id_to_open", $marker_id_to_open);
    $this->set("sublet_data_for_tooltip", $subletData);

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
    $this->Session->write('start_date', "NOT_SET");
    $this->Session->write('end_date', "NOT_SET"); 
    $this->Session->write('min_rent', 0); 
    $this->Session->write('max_rent', 999999); 
    $this->Session->write('beds', 0);
    $this->Session->write('house', true); 
    $this->Session->write('apt', true); 
    $this->Session->write('unit_type_other', true);  
    $this->Session->write('male', "NOT_SET");  
    $this->Session->write('female', "NOT_SET");  
    $this->Session->write('students_only', "NOT_SET");  
    $this->Session->write('grad', "NOT_SET"); 
    $this->Session->write('undergrad', "NOT_SET"); 
    $this->Session->write('bathroom_type', "NOT_SET"); 
    $this->Session->write('ac', "NOT_SET");
    $this->Session->write('parking', "NOT_SET");
    $this->Session->write('utilities_included', "NOT_SET");  
    $this->Session->write('no_security_deposit', "NOT_SET");
  }

  }