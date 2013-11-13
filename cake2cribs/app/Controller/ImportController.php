<?php
/*
Contains functionality for importing listings from csv to database
*/
class ImportController extends AppController {
	public $uses = array('Listing', 'Rental', 'GeocoderAddress', 'User', 'Marker', 'Image');
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
		$this->Auth->allow('SaveMultipleImageCopies');
		$this->Auth->allow('GetListings');
		$this->Auth->allow('SaveListings');
		$this->Auth->allow('TestGeocoderFunctionality');
		$this->Auth->allow('index');
		$this->Auth->allow('ImportImages');
  	}

  	public function index()
  	{
  		
  	}

/*
Returns json_encoded array of listings
if $fileName is null, processes all files in app/webroot/listings/
otherwise, processes only app/webroot/listings/$fileName
*/
	public function GetListings($fileName='iowa.csv')
	{
		ini_set('auto_detect_line_endings',true);
		$this->layout = 'ajax';
		$listings = array();
		if ($fileName != null){
			/* only retrieve file specified */
			$file = fopen(WWW_ROOT . 'listings/' . $fileName, 'r');
			$listing = $this->_processFileToJSON($file);
			CakeLog::write('jsonlisting', print_r($listing, true));
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



****** CHANGE STATE FROM IA TO WHATEVER FOR NEXT ONE *******

****** NEED TO UPDATE SCHEDULING AND AVAILABLE IN LISTINGS *********



*/
	public function SaveListings($geocoder_necessary=true)
	{
		App::Import('model', 'User');

		$this->layout = 'ajax';
		$listing = $this->request->data;
		//CakeLog::write("listings_preprocessing", print_r($listing, true));
		//foreach ($listings as &$listing){
			CakeLog::write("nextlisting", print_r($listing, true));
			/* Convert address to what gets returned from google geocoder */
			if (!array_key_exists('Marker', $listing) ||
				!array_key_exists('street_address', $listing['Marker']) ||
				!array_key_exists('city', $listing['Marker']) || 
				!array_key_exists('state', $listing['Marker'])){
				$this->set('response', '');
				return;
			}

			if (!array_key_exists('Marker', $listing) || !array_key_exists('User', $listing) ||
				!array_key_exists('Rental', $listing) || !array_key_exists('Listing', $listing)){
				CakeLog::write("ImportFailed", 'MISSING KEY:'.print_r($listing, true));
				$this->set('response', '');
				return;
			}
			$address = array(
				'street_address' => $listing['Marker']['street_address'],
				'city' => $listing['Marker']['city'],
				'state' => $listing['Marker']['state'],
			);
			$formatted_address = array();
			if ($geocoder_necessary)
				$formatted_address = $this->_geocoderProcessAddress($address);
			else{
				$formatted_address = $this->_processGeocoderFromSavedData($address);
			}
			if ($formatted_address === null)
				return;
			if (!array_key_exists('latitude', $formatted_address) || 
				!array_key_exists('longitude', $formatted_address) || 
				!array_key_exists('city', $formatted_address) ||
				!array_key_exists('state', $formatted_address)) {
					CakeLog::write('FAILED_IMPORT', 'GEOCODER: '.print_r($listing, true));
					return;
			}

			CakeLog::write("formatted_address", print_r($formatted_address, true));
			$listing['Marker']['street_address'] = $formatted_address['street_address'];
			$listing['Marker']['city'] = $formatted_address['city'];
			$listing['Marker']['state'] = 'IA'; //$formatted_address['state'];
			if (array_key_exists('zip', $formatted_address))
				$listing['Marker']['zip'] = $formatted_address['zip'];

			$listing['Marker']['latitude'] = $formatted_address['latitude'];
			$listing['Marker']['longitude'] = $formatted_address['longitude'];

			/* Convert fields from strings to their database values */
			$listing = $this->_convertTypeStringsToIntegers($listing);

			/* Get existing marker object if this address has already been used */
			$marker = $this->Marker->GetMarkerByAddress($listing['Marker']);
			if ($marker != null)
				$listing['Marker'] = $marker['Marker'];

			if (!array_key_exists('User', $listing))
				return;
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

			/* Handle case of ? in rent and baths */
			$fields = array('rent', 'baths');
			foreach ($fields as $field){
				if (array_key_exists($field, $listing['Rental'])){
					if (trim($listing['Rental'][$field]) === '?')
						unset($listing['Rental'][$field]);
				}
			}

			/* remove null fields */
			$listing['User'] = $this->_removeNullEntries($listing['User']);
			$listing['Rental'] = $this->_removeNullEntries($listing['Rental']);
			$listing['Marker'] = $this->_removeNullEntries($listing['Marker']);
			$listing['Listing'] = $this->_removeNullEntries($listing['Listing']);

			/* fix phone number formatting issues */
			if (array_key_exists('phone', $listing['User']) && !empty($listing['User']['phone']))
				$listing['User']['phone'] = str_replace('-', '', $listing['User']['phone']);

			/* Copy contact info from user object to the rental object */
			$this->_copyUserInfoToRental($listing);

			/* unit_count should default to 1 if its null */
			if (!array_key_exists('unit_count', $listing['Rental'])){
				$listing['Rental']['unit_count'] = 1;
			}
			else
				$listing['Rental']['unit_count'] = intval($listing['Rental']['unit_count']);

			/* Handle some random cases that have been throwing errors */
			if (array_key_exists('Rental', $listing) && array_key_exists('rent', $listing['Rental']))
				$listing['Rental']['rent'] = intval($listing['Rental']['rent']);

			if (array_key_exists('Rental', $listing) && array_key_exists('upper_floor_amount', $listing['Rental'])
				&& !is_numeric($listing['Rental']['upper_floor_amount']))
				unset($listing['Rental']['upper_floor_amount']);

			

		//}

		/* Set this so we can tell later which users to send the welcome email to */
		$listing['User']['received_welcome_email'] = 1;

		CakeLog::write('imported_listings', print_r($listing, true));
		if (!$this->Listing->saveAll($listing, array('deep' => true))){
			CakeLog::write("failure", print_r($this->Listing->validationErrors, true));
			CakeLog::write("failure", print_r($listing, true));
		}
			

		$this->set('response', '');
	}

	/*
	Re-save current images as 3 copies:
	1) lrg_filename - image big enough for full-page listing.
	2) med_filename - image big enough for listing popup
	3) sml_filename - image big enough for sidebar thumbnail
	*/	
	public function SaveMultipleImageCopies($dir='img/listings')
	{
		foreach (scandir($dir) as $item) {
	        if ($item == '.' || $item == '..') 
	        	continue;
	        $image = WideImage::load($dir . '/' . $item);
	        /* Large image for full-page listing */
	        $old = WWW_ROOT.$dir.'/'.$item;
	        $new = WWW_ROOT.$dir.'/lrg_'.$item;
	        copy($old, $new);
	        unlink($old);
	        /* Medium image for listing popup */
	        $image->resize(260, null)->saveToFile(WWW_ROOT.$dir.'/med_'.$item);
	        /* Small image for sidebar */
	        $image->resize(98, null)->saveToFile(WWW_ROOT.$dir.'/sml_'.$item);
	    }
	}

	public function ResizeAllImages($dir='img/listings')
	{
		foreach (scandir($dir) as $item) {
	        if ($item == '.' || $item == '..') 
	        	continue;

	        $image = imagecreatefromjpeg($dir.'/'.$item);
	        $new_path = WWW_ROOT.'img/listings/' . $item;
	        imagejpeg($image, $new_path, 45);
	    }
	}

	/*
	Copies the following fields (if they exist) from user to rental
	phone, email, street_address, city, state, zip
	*/
	private function _copyUserInfoToRental(&$listing)
	{
		/*
		Users table
		- street_address
		- city
		- state
		- zipcode
		- website
		- email
		- phone

		Rentals table
		- contact_email
		- contact_phone (format this correctly)
		- lease_office_street_address
		- lease_office_city
		- lease_office_state
		- lease_office_zipcode
		*/

		if (!array_key_exists('User', $listing) || !array_key_exists('Rental', $listing)) {
			return;
		}

		$fieldsMap = array(
			'street_address' => 'lease_office_street_address',
			'city' => 'lease_office_city',
			'state' => 'lease_office_state',
			'zipcode' => 'lease_office_zipcode',
			'website' => 'website',
			'email' => 'contact_email',
			'phone' => 'contact_phone'
		);

		foreach ($fieldsMap as $userField => $rentalField){
			if (!array_key_exists($rentalField, $listing['Rental']) ||
				array_key_exists($rentalField, $listing['Rental']) && empty($listing['Rental'][$rentalField])){
					/* only overwrite rental fields if they don't already exist and if they aren't null in users table. */
					if (array_key_exists($userField, $listing['User']) && !empty($listing['User'][$userField])) {
						$listing['Rental'][$rentalField] = $listing['User'][$userField];
					}
			}
		}
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
				empty($listing['Rental'][$utility])) {
				unset($listing['Rental'][$utility]);
			}	
			else if ($listing['Rental'][$utility] === 'Flat Rate' ||
				$listing['Rental'][$utility] === 'Yes' || 
				$listing['Rental'][$utility] === 'Y')
					$listing['Rental'][$utility] = true;
			else
				$listing['Rental'][$utility] = 0;
		}

		/* square_feet */
		if (array_key_exists('square_feet', $listing['Rental'])){
			if ($listing['Rental']['square_feet'] === 0)
				unset($listing['Rental']['square_feet']);
			else
				$listing['Rental']['square_feet'] = intval($listing['Rental']['square_feet']);
		}

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

		/* unit_style_options */
		if (array_key_exists('unit_style_options', $listing['Rental']))
			$listing['Rental']['unit_style_options'] = Rental::unit_style_options_reverse($listing['Rental']['unit_style_options']);

		/* building_type_id */

		if (array_key_exists('building_type_id', $listing['Marker']))
			$listing['Marker']['building_type_id'] = Rental::building_type_reverse($listing['Marker']['building_type_id']);

		/* amenities */
		foreach ($this->amenities as $amenity){
			if (!array_key_exists($amenity, $listing['Rental']) ||
				$listing['Rental'][$amenity] === '-' ||
				$listing['Rental'][$amenity] === '' ||
				empty($listing['Rental'][$amenity]))
					unset($listing['Rental'][$amenity]);
			else if ($listing['Rental'][$amenity] === 'N' ||
				$listing['Rental'][$amenity] === 'No')
					$listing['Rental'][$amenity] = 0;
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
		ini_set("auto_detect_line_endings", true);
		if ($handle) {
			$listings = array();
			$counter = 1; 
		    while (!feof($handle)) {
		    	$nextline = fgets($handle);
		    	if (strrpos($nextline,'Ê') === false)
					$listing=str_replace('Ê', '', $nextline);

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
		    CakeLog::write('listings', print_r($listings, true));
		    return $listings;
		}

		return null;
	}

	public function ImportImages($directory='img/temp/iowa/')
	{
		$path_to_directory = WWW_ROOT.$directory;
		$counter = 0;
		$listing_ids_processed = array();
		$consecutive_errors = 0;
		foreach (scandir($path_to_directory) as $file) { 
		    if ('.' === $file) continue;
		    if ('..' === $file) continue;
		    //if ($counter > 5) return;
		    //$counter ++;

		    $dashPos = strrpos($file, '-');
		    $dotPos = strrpos($file, '.');
		    $address_length = $dotPos;
		    if ($dashPos)
		    	$address_length = min($dashPos, $dotPos);

		    $address = substr($file, 0, $address_length);
		    $full_address = array(
		    	'street_address' => trim($address), 
		    	'city' => 'Iowa City',
		    	'state' => 'IA'
		    );
		//CakeLog::write('full_address', print_r($full_address, true));
		    //$geocoded_address = $this->_geocoderProcessAddress($full_address);
		    /*
		    $geocoded_address = null;
		    if (!array_key_exists($address, $filename_address_to_geocoded_address)) {
		    	$geocoded_address = $this->_geocoderProcessAddress($full_address);
		    	$filename_address_to_geocoded_address[$address] = $geocoded_address;
		    }
		    else
		    	$geocoded_address = $filename_address_to_geocoded_address[$address];
		    	*/
		//CakeLog::write('geocoded_address', print_r($geocoded_address, true));
		    $listings = $this->Listing->GetListingIdFromAddress($full_address, true);
		//CakeLog::write('listings', print_r($listings, true));
		    if (!array_key_exists('street_address', $full_address))
		    {
		    	CakeLog::write("IMAGE_UPLOAD_FAILED", print_r($full_address, true));
		    	return;
		    }
		    $street_address = $full_address['street_address'];
		    if ($listings === null) {
		    	CakeLog::write('marker_doesnt_exist_yet', print_r($full_address, true));
		    	copy($directory . $file, WWW_ROOT.'img/failed_import/');
		    	continue;
		    }

		    foreach ($listings as $listing){
		    	$listing_id = intval($listing['Listing']['listing_id']);
			    $user_id = $listing['Listing']['user_id'];
			    $path = $directory . $file;
			    $is_primary = 0;
			    if (!in_array($listing_id, $listing_ids_processed)){
			    	$is_primary = 1;
			    	array_push($listing_ids_processed, $listing_id);
			    }
			    	
			CakeLog::write('beforeSaving', 'listing_id: ' . $listing_id);
			CakeLog::write('beforeSaving', 'user_id: ' . $user_id);
			CakeLog::write('beforeSaving', 'path: ' . $path);
			CakeLog::write('beforeSaving', 'is_primary: ' . $is_primary); /* BUG */
			CakeLog::write('listing_ids_processed', print_r($listing_ids_processed, true));

				if ($listing_id === null || $path === null){
					CakeLog::write('FAILED_IMPORT', '1: ' . $listing_id);
					continue;
				}
					
				$response = $this->Image->SaveImageFromImport($path, $user_id, $listing_id, $is_primary);
			CakeLog::write('response', print_r($response, true));
				if (array_key_exists('error', $response) || $response === null){
					CakeLog::write('FailedImageSave', print_r($path, true));
					$consecutive_errors ++;
					if ($consecutive_errors === 5)
						return;
				} else {
					$consecutive_errors = 0;
				}
		    }	
		}
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
	        	if (!array_key_exists('street_address', $address_components) ||
	        		!array_key_exists('city', $address_components) ||
	        		!array_key_exists('state', $address_components))
	        		return null;

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
			CakeLog::write("failedToGetGeocodeOutput", print_r($input_address, true));
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
    		'latitude' => $lat_long['lat'],
    		'longitude' => $lat_long['lng'],
    	);

    	if (array_key_exists('zip', $address_components))
    		$response['zip'] = $address_components['zip'];

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