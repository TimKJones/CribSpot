<?php
/*
Contains functionality for importing listings from csv to database
*/
class ImportController extends AppController {
	public $uses = array('Listing');
	public $components= array();
	private $NUM_LISTING_COLUMNS = 37; /* number of columns in the excel doc for a listing */
	private $MAPS_HOST = "maps.google.com";
	private $MAPS_KEY = "AIzaSyChGyO2wCFqmDe8FNh_6GxITy7dDLQ0ZpE";

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
	public function GetListings($fileName='wisconsin.csv')
	{
		$this->layout = 'ajax';
		$listings = array();
		if ($fileName != null){
			/* only retrieve file specified */
			$file = fopen(WWW_ROOT . 'listings/' . $fileName, 'r');
			$listing = $this->_processFileToJSON($file);
			if ($listing == null)
				CakeLog::write('Import_GetListings', 'Failed: code: 1; name: ' . $fileName);
			else
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
		CakeLog::write("GetListingsSuccess", print_r($listings, true));
	}

/*
Sets lat and long values for each address
Then saves the array of listing objects.
*/
	public function SaveListings($listings)
	{
		$this->layout = 'ajax';
		$listings = json_decode($listings);
/*
Call geocoderProcessAddress to get formatted address and lat/lng
Convert all type tables to their appropriate codes in the database.
*/
		$this->set('response', '');
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
		    	$nextLine = fgets($handle);
		    	/* use preg_split to escape \ before commas */
				$listing = preg_split('~(?<!\\\)' . preg_quote(',', '~') . '~', $nextLine);
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
	private function _geocoderProcessAddress($input_address)
	{
		if (!array_key_exists('street_address', $input_address) ||
			!array_key_exists('city', $input_address) ||
			!array_key_exists('state', $input_address)) {
				CakeLog::write('IMPORT_ERRORS', print_r($input_address, true));
				return;
		}

		$base_url = "http://maps.googleapis.com/maps/api/geocode/json?address=";
		$address = urlencode(trim($input_address['street_address'])) . ',' . urlencode(trim($input_address['city'])) . ',' . 
			urlencode(trim($input_address['state']));
        $request_url = $base_url . $address . "&sensor=false";

        $geocode_pending = true;
        $delay = 0;
        while ($geocode_pending){
        	$json = file_get_contents($request_url) or die("url not loading");
	        $address = json_decode($json);
	        $status = $address->status;
	        if (strcmp($status, "200") == 0 || strcmp($status, "OK") == 0) {
	        	 // Successful geocode
	        	$geocode_pending = false;
	        	$address_components = $this->_getAddressComponents($address);
	        	$lat_long = $this->_getLatLong($address);
	        	$response = array(
	        		'street_address' => $address_components['street_address'],
	        		'city' => $address_components['city'],
	        		'state' => $address_components['state'],
	        		'zip' => $address_components['zip'],
	        		'latitude' => $lat_long['lat'],
	        		'longitude' => $lat_long['lng'],
	        	);

	            return $response;
	        }
	        else if (strcmp($status, "620") == 0) {
	            // sent geocodes too fast
	            $delay += 100000;
	        }
	        else {
	            // failure to geocode
	            $geocode_pending = false;
	        }

	        usleep($delay);
	    }
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