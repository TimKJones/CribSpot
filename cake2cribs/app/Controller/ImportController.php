<?php
/*
Contains functionality for importing listings from csv to database
*/
class ImportController extends AppController {
	public $uses = array('Listing');
	public $components= array();
	private $NUM_LISTING_COLUMNS = 37; /* number of columns in the excel doc for a listing */

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('GetListings');
		$this->Auth->allow('SaveListings');
  	}

/*
Returns json_encoded array of listings
if $fileName is null, processes all files in app/webroot/listings/
otherwise, processes only app/webroot/listings/$fileName
*/
	public function GetListings($fileName='wisconsin.csv')
	{
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

		CakeLog::write("GetListingsSuccess", print_r($listings, true));
	}

/*
Sets lat and long values for each address
Then saves the array of listing objects.
*/
	public function SaveListings($listings)
	{
		
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