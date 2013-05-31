<?php

class Listing extends AppModel {
	public $name = 'Listing';	
	public $uses = array('Listing', 'Realtor');
	public $primaryKey = 'listing_id';
	public $actsAs = array('Containable');
	public $belongsTo  = array('Realtor', 'Marker');
	// public $hasMany = array(
	// 					'Favorite' => array(
	// 							'className' => 'Favorite', 
	// 							'foreignKey' => 'listing_id'
	// 					)
	// );

	public $validate = array(
		'listing_id' => 'alphaNumeric', /*cant find an integer type, may have to do this through custom regular expression check*/
		'marker_id' => 'alphaNumeric', /*cant find an integer type, may have to do this through custom regular expression check*/

		'available' => 'boolean',
		'lease_range' => 'alphaNumeric',
		'unit_type' => 'alphaNumeric',
/* TODO: for fields where we have set values (ex. Spring or Fall, House or Duplex or Apt), I think we need to write a custom validation function - we'll add that after we get the basic model working) */
		'unit_description' => 'alphaNumeric',
		'beds' => 'alphaNumeric',
		'baths' => 'decimal',
		'rent' => array(
			'rule'    => array('decimal')
		),
		'electric' => array(
			'rule' => array('alphanumeric')
		),
		'water' => array(
			'rule' => array('alphanumeric')
		),
		'heat' => array(	
			'rule' => array('alphanumeric')
		),
		'air' => array(
			'rule' => array('alphanumeric')
		),
		'parking' => array(
			'rule' => array('alphanumeric')
		),
		'furnished' => array(
			'rule' => array('alphanumeric')
		),
		'url' => array(
			'rule' => 'url'
		),
		'realtor_id' => 'alphanumeric'
	);

/*
Returns the conditions array used to match the current filter settings in a query using model->find()
*/
private function getFilteredQueryConditions($params)
{
	$conditions = array();
	$lease_range_OR = array();
	if ($params['fall'] == "true")
		array_push($lease_range_OR, 'fall');
	if ($params['spring'] == "true")
		array_push($lease_range_OR, 'spring');
	if ($params['other'] == "true")
		array_push($lease_range_OR, 'other');

	$unit_type_OR = array();
	if ($params['house'] == "true")
		array_push($unit_type_OR, 'house');
	if ($params['apt'] == "true")
		array_push($unit_type_OR, 'apt');
	if ($params['duplex'] == "true")
		array_push($unit_type_OR, 'duplex');
	array_push($unit_type_OR, 'greek');
	array_push($unit_type_OR, 'dorm');


	if (count($lease_range_OR) > 0)
	{
		array_push($conditions, array('OR' => array(
			'Listing.lease_range' => $lease_range_OR)));
	}	
	else
		array_push($conditions, array('OR' => array(
			'Listing.lease_range' => 'NONE')));
			// Without this, all lease ranges would be returned when all check boxes are unchecked

	array_push($conditions, array('OR' => array(
		'Listing.unit_type'   => $unit_type_OR)));

	array_push($conditions, array(
		'Listing.rent >=' => $params['minRent'],
		'Listing.rent <=' => $params['maxRent'],
		'Listing.beds >=' => $params['minBeds'],
		'Listing.beds <=' => $params['maxBeds']));

	return $conditions;
}

/*
Retrieves all listing data for a specific markerId.
Returns a (json_encoded) array of associative arrays, with assoc. array for each listing. 
	Each assoc. array maps table column name to value.
*/
/*TODO: Filter which columns are retrieved - for example, not everything for realtor needs to be fetched */
public function getListingData($markerId, $includeRealtor)
{
	$conditions = array('Listing.marker_id' => $markerId);

	// Contain the query to only retrieve the fields needed for the marker tooltip.
	$contains = array();

	$listingsQuery = array();
 	$listingsQuery = $this->find('all', array(
                     'conditions' => $conditions,
                     'contain' => $contains
  	));

 	return $listingsQuery;
}

/*
Given array of parameter values as input.
Returns a list of marker_ids that have listings matching the parameter criteria.
*/
public function getFilteredMarkerIdList($params)
{
	$conditions = $this->getFilteredQueryConditions($params);

	/* Limit to only querying from the Listing table. */
	$this->contain();

	$markerIdList = $this->find('all', array(
		'conditions' => $conditions,
		'fields' => array('marker_id')));

	//return $markerIdList[0]['Listing']['marker_id'];
	$formattedIdList = array();
	for ($i = 0; $i < count($markerIdList); $i++)
		array_push($formattedIdList, $markerIdList[$i]['Listing']['marker_id']);

	return json_encode($formattedIdList);
}


/*
Returns associative array of tooltip_[field] to initial value for that field.
*/
public function getTooltipVariables()
{
	$fields = array(
		"address" => "?"
	);

	return $fields;		
}

/*
Pulls all Listing data for the listing_ids contained in $listingIds
*/
public function GetFavoritesListingsData($listingIdsResultSet)
{
	$listingIdsList = array();
	for ($i = 0; $i < count($listingIdsResultSet); $i++)
	{
		array_push($listingIdsList, $listingIdsResultSet[$i]['Favorite']['listing_id']);
	}

	$listing_ids = $this->find('all', array(
		'conditions' => array('Listing.listing_id' => $listingIdsList),
		'contain' => array('Realtor')));

	return json_encode($listing_ids);
}

public function getAllListings()
{
	return $this->find('all');
}

}


?>
