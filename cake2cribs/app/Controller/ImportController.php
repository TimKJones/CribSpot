<?php
class ImportController extends AppController {
	public $uses = array('Listing');
	public $components= array();

/*
Returns json_encoded array of listings
if $fileName is null, processes all files in app/listings/
otherwise, processes only app/listings/$fileName
*/
	public function GetListings($fileName)
	{

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
*/
	public function _processFileToJSON($file)
	{
		
	}