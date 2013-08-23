<?php
/*
Contains functionality for importing listings from csv to database
*/
class ImportController extends AppController {
	public $uses = array('Listing', 'Rental', 'GeocoderAddress', 'User', 'Marker');
	public $components= array();
	private $NUM_LISTING_COLUMNS = 0; /* number of columns in the excel doc for a listing */
	private $MAPS_HOST = "maps.google.com";
	private $MAPS_KEY = "AIzaSyChGyO2wCFqmDe8FNh_6GxITy7dDLQ0ZpE";
	/* utilities are really just yes/no/? fields */
	private $utilities = array('electric', 'water', 'gas', 'trash', 'cable', 'internet', 'air',
		'street_parking', 'private_parking');
	private $amenities = array('tv', 'balcony', 'fridge', 'storage', 'pool', 'hot_tub', 'fitness_center', 
		'game_room', 'front_desk', 'security_system', 'tanning_beds', 'study_lounge', 'patio_deck', 'yard_space', 
		'elevator');

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('GetListings');
		$this->Auth->allow('SaveListings');
		$this->Auth->allow('TestGeocoderFunctionality');
		$this->Auth->allow('Index');
  	}

  	public function index()
  	{
  		
  	}

/*
Returns json_encoded array of listings
if $fileName is null, processes all files in app/webroot/listings/
otherwise, processes only app/webroot/listings/$fileName
*/
	public function GetListings($fileName='michigan.csv')
	{
		$this->layout = 'ajax';
		$listings = array();
		if ($fileName != null){
			/* only retrieve file specified */
			$file = fopen(WWW_ROOT . 'listings/' . $fileName, 'r');
			$listing = $this->_processFileToJSON($file);
			if ($listing != null)
				array_push($listings, $listing);
		}
		else {
			/* retrieve ALL files in /app/webroot/listings */
			$dir = new DirectoryIterator(dirname(WWW_ROOT . 'listings/'));
			foreach ($dir as $fileinfo) {
			    if (!$fileinfo->isDot()) {
			        CakeLog::write("filesInDirectory", $fileinfo->getFilename());
			    }
			}
				/*$listing = $this->_processFileToJSON($file)
				if ($listing == null)
					CakeLog::write('Import_GetListings', 'Failed: code: 2; name: ' . $file->name)
				else
					array_push($listings, $listing)*/
		}

		$this->set('response', json_encode($listings));
		//CakeLog::write("GetListingsSuccess", print_r($listings, true));
	}

/*
Sets lat and long values for each address
Then saves the array of listing objects.
*/
	public function SaveListings()
	{
		App::Import('model', 'User');

		$this->layout = 'ajax';
		$listing = $this->request->data;
		//CakeLog::write("listings_preprocessing", print_r($listing, true));
		//foreach ($listings as &$listing){
			CakeLog::write("nextlisting", print_r($listing, true));
			/* Convert address to what gets returned from google geocoder */
			$address = array(
				'street_address' => $listing['Marker']['street_address'],
				'city' => $listing['Marker']['city'],
				'state' => $listing['Marker']['state'],
			);
			$formatted_address = $this->_geocoderProcessAddress($address);
			CakeLog::write("formatted_address", print_r($formatted_address, true));
			$listing['Marker']['street_address'] = $formatted_address['street_address'];
			$listing['Marker']['city'] = $formatted_address['city'];
			$listing['Marker']['state'] = $formatted_address['state'];
			$listing['Marker']['latitude'] = $formatted_address['latitude'];
			$listing['Marker']['longitude'] = $formatted_address['longitude'];

			/* Convert fields from strings to their database values */
			$listing = $this->_convertTypeStringsToIntegers($listing);

			/* Get existing marker object if this address has already been used */
			$marker = $this->Marker->GetMarkerByAddress($listing['Marker']);
			if ($marker != null)
				$listing['Marker'] = $marker['Marker'];

			/* Get existing user object if this company name has already been used */
			$user = $this->User->GetUserByCompanyName($listing['User']['company_name']);
			if ($user != null)
				$listing['User'] = $user['User'];
			else{
				/* Add in some user fields that are blank */
				$listing['User']['user_type'] = User::USER_TYPE_PROPERTY_MANAGER;
				$listing['User']['password'] = uniqid();
				$listing['User']['verified'] = 0;
				//$listing['User']['company_name'] = preg_replace('/([^[:alnum:]])/', '\\\\$1', $listing['User']['company_name']);
			}

			/* remove null fields */
			$listing['User'] = $this->_removeNullEntries($listing['User']);
			$listing['Rental'] = $this->_removeNullEntries($listing['Rental']);
			$listing['Marker'] = $this->_removeNullEntries($listing['Marker']);
			$listing['Listing'] = $this->_removeNullEntries($listing['Listing']);
		//}

		CakeLog::write('imported_listings', print_r($listing, true));
		if (!$this->Listing->saveAll($listing, array('deep' => true)))
			CakeLog::write("failure", print_r($this->Listing->validationErrors, true));

		$this->set('response', '');
	}

	private function _removeNullEntries($rental)
	{
		foreach ($rental as $key => $value)
		{
			if ($rental[$key] === null || trim($rental[$key]) === "")
				unset($rental[$key]);
		}

		return $rental;
	}

	private function _convertTypeStringsToIntegers(&$listing)
	{
		App::Import('model', 'Rental');

		if (array_key_exists('start_date', $listing['Rental']) && !empty($listing['Rental']['start_date']))
			$listing['Rental']['start_date'] = date('Y-m-d', strtotime($listing['Rental']['start_date']));
		else
			unset($listing['Rental']['start_date']);

		if (array_key_exists('alternate_start_date', $listing['Rental']) && !empty($listing['Rental']['alternate_start_date']))
			$listing['Rental']['alternate_start_date'] = date('Y-m-d', strtotime($listing['Rental']['alternate_start_date']));
		else
			unset($listing['Rental']['alternate_start_date']);

		if (array_key_exists('end_date', $listing['Rental']) && !empty($listing['Rental']['end_date']))
			$listing['Rental']['end_date'] = date('Y-m-d', strtotime($listing['Rental']['end_date']));
		else
			unset($listing['Rental']['end_date']);

CakeLog::write("listingWithDates", print_r($listing, true));
		/* Convert combo of move_in/move_out to lease_length */
		if (array_key_exists('start_date', $listing['Rental']) &&
			array_key_exists('end_date', $listing['Rental']) &&
			!empty($listing['Rental']['start_date']) &&
			!empty($listing['Rental']['end_date'])) {
			$startMonth = intval(date('n', strtotime($listing['Rental']['start_date'])));
			$startDay = intval(date('j', strtotime($listing['Rental']['start_date'])));
			$startYear = intval(date('Y', strtotime($listing['Rental']['start_date'])));
			$endMonth = intval(date('n', strtotime($listing['Rental']['end_date'])));
			$endDay = intval(date('j', strtotime($listing['Rental']['end_date'])));
			$endYear = intval(date('Y', strtotime($listing['Rental']['end_date'])));
			CakeLog::write("dates", 'start_month: ' . $startMonth);
			CakeLog::write("dates", 'start_day: ' . $startDay);
			CakeLog::write("dates", 'start_year: ' . $startYear);
			CakeLog::write("dates", 'end_month: ' . $endMonth);
			CakeLog::write("dates", 'end_day: ' . $endDay);
			CakeLog::write("dates", 'end_year: ' . $endYear);
			$leaseLength = abs(($endYear - $startYear) * 12 - ($endMonth - $startMonth));
			$leaseLength = $leaseLength + ($endDay > $startDay);
			$listing['Rental']['lease_length'] = $leaseLength;
			CakeLog::write('dates', 'start: ' . $listing['Rental']['start_date']);
			//$listing['Rental']['start_date'] = date('Y-m-d', $listing['Rental']['start_date']);
			//CakeLog::write('dates', 'start: ' . $listing['Rental']['start_date']);
			//$listing['Rental']['end_date'] = date('Y-m-d', $listing['Rental']['end_date']);
			CakeLog::write('dates', 'end_date: ' . $listing['Rental']['end_date']);
			//$listing['Rental']['lease_length'] = date('Y-m-d', $listing['Rental']['lease_length']);
			CakeLog::write('dates', 'lease_length: ' . $listing['Rental']['lease_length']);
		}
		else if (array_key_exists('lease_length', $listing['Rental']))
			unset($listing['Rental']['lease_length']);

		if (array_key_exists('beds', $listing['Rental']) &&
			$listing['Rental']['beds'] === 'Studio')
				$listing['Rental']['beds'] = 0;

		/* Convert utilities to boolean values (or remove from array if '?') */
		foreach ($this->utilities as $utility){
			if (!array_key_exists($utility, $listing['Rental']) || 
				$listing['Rental'][$utility] === '?' || 
				empty($listing['Rental'][$utility]) ||
				$listing['Rental'][$utility] === '')
					unset($listing['Rental'][$utility]);
			else if ($listing['Rental'][$utility] === 'Flat Rate' ||
				$listing['Rental'][$utility] === 'Yes' || 
				$listing['Rental'][$utility] === 'Y')
					$listing['Rental'][$utility] = true;
			else
				$listing['Rental'][$utility] = false;
		}

		/* square_feet */
		$listing['Rental']['square_feet'] = intval($listing['Rental']['square_feet']);
		if (!array_key_exists('square_feet', $listing['Rental']) ||
			$listing['Rental']['square_feet'] === 0)
				unset($listing['Rental']['square_feet']);

		/* pets_type */
		if (array_key_exists('pets', $listing['Rental'])){
			if ($listing['Rental']['pets'] === 'Dogs')
				$listing['Rental']['pets_type'] = Rental::PETS_DOGS_ONLY;
			else if ($listing['Rental']['pets'] === 'Cats')
				$listing['Rental']['pets_type'] = Rental::PETS_CATS_ONLY;
			else if ($listing['Rental']['pets'] === 'Both')
				$listing['Rental']['pets_type'] = Rental::PETS_CATS_AND_DOGS;
			else if ($listing['Rental']['pets'] === 'No')
				$listing['Rental']['pets_type'] = Rental::PETS_NOT_ALLOWED;
			else
				unset($listing['Rental']['pets']);
		}

		/* parking_type */
		if (array_key_exists('parking_type', $listing['Rental'])){
			if ($listing['Rental']['parking_type'] === 'Garage')
				$listing['Rental']['parking_type'] = Rental::PARKING_GARAGE;
			else if ($listing['Rental']['parking_type'] === 'Lot')
				$listing['Rental']['parking_type'] = Rental::PARKING_PARKING_LOT;
			else if ($listing['Rental']['parking_type'] === 'Driveway')
				$listing['Rental']['parking_type'] = Rental::PARKING_DRIVEWAY;
			else if ($listing['Rental']['parking_type'] === 'Other')
				$listing['Rental']['parking_type'] = Rental::PARKING_OTHER;
			else
				unset($listing['Rental']['parking_type']);
		}	

		/* furnished_type */
		if (array_key_exists('furnished_type', $listing['Rental'])){
			if ($listing['Rental']['furnished_type'] === 'Yes' || 
				$listing['Rental']['furnished_type'] === 'Y')
				$listing['Rental']['furnished_type'] = Rental::FURNISHED_FULLY;
			else if ($listing['Rental']['furnished_type'] === 'Partial')
				$listing['Rental']['furnished_type'] = Rental::FURNISHED_PARTIALLY;
			else if ($listing['Rental']['furnished_type'] === 'No' ||
				$listing['Rental']['furnished_type'] === 'N')
				$listing['Rental']['furnished_type'] = Rental::FURNISHED_NO;
			else
				unset($listing['Rental']['furnished_type']);
		}

		/* building_type_id */

		if (array_key_exists('building_type_id', $listing['Marker']))
			$listing['Marker']['building_type_id'] = Rental::building_type_reverse($listing['Marker']['building_type']);

		/* amenities */
		foreach ($this->amenities as $amenity){
			if (!array_key_exists($amenity, $listing['Rental']) ||
				$listing['Rental'][$amenity] === '-' ||
				$listing['Rental'][$amenity] === '' ||
				empty($listing['Rental'][$amenity]))
					unset($listing['Rental'][$amenity]);
			else if ($listing['Rental'][$amenity] === 'N' ||
				$listing['Rental'][$amenity] === 'No')
					$listing['Rental'][$amenity] = false;
			else
				$listing['Rental'][$amenity] = true;
		}

		/* smoking */
		if (!array_key_exists('smoking', $listing['Rental']) || $listing['Rental']['smoking'] === '?')
			unset($listing['Rental']['smoking']);
		else
			$listing['Rental']['smoking'] = ($listing['Rental']['smoking'] === 'Allowed');

		/* washer_dryer */
		if (array_key_exists('washer_dryer', $listing['Rental']))
			$listing['Rental']['washer_dryer'] = Rental::washer_dryer_reverse($listing['Rental']['washer_dryer']);
CakeLog::write("finalprocesslisting", print_r($listing, true));
		return $listing;
	}

/*
returns array of contents inside $file, 
with each item in array created by separating items in $file by commas.
return null on failure
*/
	private function _processFileToJSON($handle)
	{
		if ($handle) {
			$listings = array();
			$counter = 1; 
		    while (!feof($handle)) {
		    	$nextline = fgets($handle);
		    	CakeLog::write('nextline', $nextline);
		    	/* use preg_split to escape \ before commas */
				$listing = preg_split('~(?<!\\\)' . preg_quote(',', '~') . '~', $nextline);
				if (count($listing) < $this->NUM_LISTING_COLUMNS){
					/* not enough fields were found. Report error and continue */
					CakeLog::write("ImportErrors", "Failed to import row " . $counter);
					continue;
				
				}

				$this->_trimListing($listing); /* remove excess white space from each field */
				array_push($listings, $listing);
				$counter++;
		    }

		    fclose($handle);
		    return $listings;
		}

		return null;
	}

/*
Unit test for _convertAddressToGeocoderFormat and _convertAddressToLatLong
*/
	public function TestGeocoderFunctionality()
	{
		$addresses = array(
			array(
				'street_address' => '330 N Carroll St',
				'city' => 'Madison',
				'state' => 'WI'
			),
			array(
				'street_address' => '305 N Frances St ',
				'city' => 'Madison',
				'state' => 'WI'
			),
			array(
				'street_address' => '454 W Gilman St ',
				'city' => 'Madison',
				'state' => 'WI'
			),
			array(
				'street_address' => '15 E Gorham St ',
				'city' => 'Madison',
				'state' => 'WI'
			),
			array(
				'street_address' => '130 E Gorham St  ',
				'city' => 'Madison',
				'state' => 'WI'
			)
		);

		for ($i = 0; $i < 1000; $i++)
		{
			$address = $this->_geocoderProcessAddress($addresses[$i%5]);	
			CakeLog::write("GeocoderTests", print_r($address, true));
		}
	}

/*
Takes an address as input as an array of (street_address, city, state)
Returns the lat, long coordinates as well as a formatted address.
*/
	private function _geocoderProcessAddress($input_address, $use_geocoder=true)
	{
		if (!array_key_exists('street_address', $input_address) ||
			!array_key_exists('city', $input_address) ||
			!array_key_exists('state', $input_address)) {
				CakeLog::write('IMPORT_ERRORS', print_r($input_address, true));
				return;
		}

		if (!$use_geocoder)
			return $this->_processGeocoderFromSavedData($input_address);	

		$base_url = "http://maps.googleapis.com/maps/api/geocode/json?address=";
		$address = urlencode(trim($input_address['street_address'])) . ',' . urlencode(trim($input_address['city'])) . ',' . 
			urlencode(trim($input_address['state']));
        $request_url = $base_url . $address . "&sensor=false";

        $geocode_pending = true;
        $delay = 0;
        while ($geocode_pending){
        	//sleep(1);
        	$json = file_get_contents($request_url) or die("url not loading");
	        $address = json_decode($json);
	        CakeLog::write('address_1', print_r($address, true));
	        $status = $address->status;
	        if (strcmp($status, "200") == 0 || strcmp($status, "OK") == 0) {
	        	 // Successful geocode
	        	$this->GeocoderAddress->SaveAddress($input_address['street_address'], $json);
	        	$geocode_pending = false;	
	        	$address_components = $this->_getAddressComponents($address);
	        	CakeLog::write("address_components", print_r($address_components, true));
	        	$lat_long = $this->_getLatLong($address);
	        	$response = array(
	        		'street_address' => $address_components['street_address'],
	        		'city' => $address_components['city'],
	        		'state' => $address_components['state'],
	        		'latitude' => $lat_long['lat'],
	        		'longitude' => $lat_long['lng']
	        	);
	        	if (array_key_exists('zip', $address_components))
	        		$response['zip'] = $address_components['zip'];
	        	
	            return $response;
	        }
	        else if (strcmp($status, "620") == 0) {
	            // sent geocodes too fast
	            $delay += 100000;
	        }
	        else {
	            // failure to geocode
	            CakeLog::write('failedToGeocode', print_r($input_address, true));
	            CakeLog::write('failedToGeocode', 'status: ' . $status);
	            $geocode_pending = false;
	            $delay += 100000;
	        }

	        usleep($delay);
	    }
	}

	private function _processGeocoderFromSavedData($input_address)
	{
		$geocoderOutput = $this->GeocoderAddress->GetGeocoderOutputFromAddress($input_address['street_address']);
		if ($geocoderOutput == null){
			CakeLog::write("failedToGetGeocodeOutput", $input_address);
			die();
		}

		$address = json_decode($geocoderOutput);

		$address_components = $this->_getAddressComponents($address);
    	CakeLog::write("address_components", print_r($address_components, true));
    	$lat_long = $this->_getLatLong($address);
    	$response = array(
    		'street_address' => $address_components['street_address'],
    		'city' => $address_components['city'],
    		'state' => $address_components['state'],
    		'zip' => $address_components['zip'],
    		'latitude' => $lat_long['lat'],
    		'longitude' => $lat_long['lng'],
    	);
CakeLog::write("response", print_r($response, true));
        return $response;
	}

	/*
	returns array of address components from geocoder response
	*/
	private function _getAddressComponents($address)
	{
		$response = array();
        $street_number = null;
        $street_name = null;

		for ($i = 0; $i < count($address->results[0]->address_components); $i++){
    		$next = $address->results[0]->address_components[$i];
    		if ($this->_isStreetNumber($next)){
    			$street_number = $next->short_name;
    		}
    		else if ($this->_isStreetName($next)){
    			$street_name = $next->short_name;
    		}
    		else if ($this->_isCity($next)){
    			$response['city'] = $next->short_name;
    		}
    		else if ($this->_isState($next)){
    			$response['state'] = $next->short_name;
    		}
    		else if ($this->_isZip($next)){
    			$response['zip'] = $next->short_name;
    		}
    	}

    	$response['street_address'] = $street_number . ' ' . $street_name;
    	return $response;
	}

	/*
	returns array of (lat, lng), extracting it from a geocoder response.
	*/
	private function _getLatLong($response)
	{
		$location = $response->results[0]->geometry->location; 	
		return array(
			'lat' => $location->lat,
			'lng' => $location->lng
		);
	}

	private function _isStreetNumber($component)
	{
		return in_array('street_number', $component->types);
	}

	private function _isStreetName($component)
	{
		return in_array('route', $component->types);
	}

	private function _isCity($component)
	{
		return in_array('locality', $component->types);
	}

	private function _isState($component)
	{
		return in_array('administrative_area_level_1', $component->types);
	}

	private function _isZip($component)
	{
		return in_array('postal_code', $component->types);
	}

	/*
	Trims excess white space from beginning and end of all fields in listing array
	*/
	private function _trimListing($listing)
	{
		foreach ($listing as &$value){
			$value = trim($value);
		}
	}
}