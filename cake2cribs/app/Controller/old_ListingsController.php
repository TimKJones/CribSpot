<?php

class ListingsController extends AppController {
	public $helpers = array('Html', 'Form');
	public $uses = array('Marker', 'Listing', 'Favorite', 'Realtor', 'ClickAnalytic', 'FilterAnalytic');
	public $components= array('Session');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('LoadMarkerData');
		$this->Auth->allow('ApplyFilter');
	}

	public function getSessionValues()
	{
		$sessionValues = array(
			'user_id' => $this->Session->read('user'),
	        'minRent' => $this->Session->read('minRent'),
	        'maxRent' => $this->Session->read('maxRent'),
	        'minBeds' => $this->Session->read('minBeds'),
	        'maxBeds' => $this->Session->read('maxBeds'),
	        'lease_fall' => $this->Session->read('lease_fall'),
	        'lease_spring' => $this->Session->read('lease_spring'),
	        'lease_other' => $this->Session->read('lease_other'),
	        'house' => $this->Session->read('house'),
	        'apartment' => $this->Session->read('apartment'),
	        'duplex' => $this->Session->read('duplex'));

        return $sessionValues;
	}

	public function UpdateFilterValues($params)
	{
		/*
		If sliders are at either of these maximum values, ensure that results greater than the maximum value are also returned.
		*/	
		$maxPossibleBeds = 10;
		$maxPossibleRent = 4000;
		$params['fall'] = $params['fall']  == "true";
		$params['spring'] = $params['spring'] == "true";
		$params['other'] = $params['other'] == "true";
		$params['house'] = $params['house'] == "true";
		$params['apt'] = $params['apt'] == "true";
		$params['duplex'] = $params['duplex'] == "true";

		$this->Session->write('lease_fall', $params['fall']);
		$this->Session->write('lease_spring', $params['spring']);
		$this->Session->write('lease_other', $params['other']);
		$this->Session->write('house', $params['house']);
		$this->Session->write('apartment', $params['apt']);
		$this->Session->write('duplex', $params['duplex']);

		if ($params['maxRent'] == $maxPossibleRent)
			$this->Session->write('maxRent', 999999);
		else
			$this->Session->write('maxRent', $params['maxRent']);

		if ($params['maxBeds'] == $maxPossibleBeds)
			$this->Session->write('maxBeds', 999999);
		else
			$this->Session->write('maxBeds', $params['maxBeds']);

		$this->Session->write('minRent', $params['minRent']);
		$this->Session->write('minBeds', $params['minBeds']);
		
	}

/*
Called via ajax when a marker is clicked to load all listings for that marker_id
Returns json encoded data.
*/
	public function LoadMarkerData($marker_id, $includeRealtor)
	{
		$markerListingsData = $this->Listing->getListingData($marker_id, $includeRealtor);
		$allMarkerData = array();
		array_push($allMarkerData, $markerListingsData);

		if ($includeRealtor == "true" && count($markerListingsData) > 0)
		{
			$realtorData = $this->Realtor->LoadRealtor($markerListingsData[0]['Listing']['realtor_id']);
			array_push($allMarkerData, $realtorData);
		}
		
		$allMarkerData = json_encode($allMarkerData);

		$this->layout = 'ajax';
		$this->set('response', $allMarkerData);

		/*TODO: NEED THIS TO BE DONE AFTER RETURNING MARKER_LIST TO CLIENT */
		$filter_id = $this->FilterAnalytic->AddFilter($this->getSessionValues(), $marker_id);
		$this->ClickAnalytic->AddClick($this->Session->read('user'), $marker_id, $filter_id);
	}

/*
Returns a list of marker_ids that will be visible based on the current filter settings.
*/
	public function ApplyFilter()
	{
		$this->UpdateFilterValues($this->params['url']);
		$response = $this->Listing->getFilteredMarkerIdList($this->params['url']);
		$this->layout = 'ajax';
		$this->set('response', $response);
	}
}
