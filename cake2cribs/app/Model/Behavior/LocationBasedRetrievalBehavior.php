<?php

/*
Behavior handling all functionality related to location-based data retrieval.
*/

class LocationBasedRetrievalBehavior extends ModelBehavior {

	public $ONE_DEGREE_IN_MILES = 69; // One degree is approximately 69 miles

/*
Fetches marker_ids for all markers within $radius of ($latitude, $longitude)
*/
	public function GetMarkerIdsCloseToPoint($latitude, $longitude, $radius)
	{
		$markerIds = $this->query("
			SELECT
			marker_id,
			(GLength(
			LineStringFromWKB(
			  LineString(
			    coordinates, 
			    GeomFromText('POINT(".$latitude." ".$longitude.")')
			  )
			 )
			))
			AS distance
			FROM markers
			order by distance"
		);

		/* Filter for only the listings within $radius of the $target_lat_long */
		$filteredMarkerIds = $this->_filterQueryResultsByDistance($markerIds, $radius);
        return $filteredMarkerIds;
	}

/*
Fetches the basic data fields for all listings within $radius of ($target_lat_long, $target_lat_long)
Basic data fields are defined in the listing model as BASIC_DATA_FIELDS, and vary based on listing type.
*/
	public function GetBasicDataCloseToPoint(Model $model, $listing_type, $target_lat_long, $radius)
	{
		if (!array_key_exists('latitude', $target_lat_long) || !array_key_exists('longitude', $target_lat_long))
                return null;

        $latitude = $target_lat_long['latitude'];
        $longitude = $target_lat_long['longitude'];

        /* Form the query string */
        $listing_type_inner_join = $this->_getListingTypeInnerJoinString($model, $listing_type);
        $fields = implode(',', $model->BASIC_DATA_FIELDS[$model::listing_type($listing_type)]);
        $queryString = "SELECT " . $fields . 
				",
			(GLength(
			LineStringFromWKB(
			  LineString(
			    coordinates, 
			    GeomFromText('POINT(".$latitude." ".$longitude.")')
			  )
			 )
			))
			AS distance
			FROM markers Marker
			inner join listings Listing on Listing.marker_id = Marker.marker_id ".
			$listing_type_inner_join .
			" order by distance";

        $basicData = $model->query($queryString);

        /* Filter for only the listings within $radius of the $target_lat_long */
        $filteredBasicData = $this->_filterQueryResultsByDistance($basicData, $radius);
        return $filteredBasicData;
	}

/*
Fetches all listings with listing_type = $listing_type within $radius of ($latitude, $longitude)
*/
	public function GetListingsCloseToPoint(Model $model, $latitude, $longitude, $radius, $listing_type)
	{
		/* Formulate the query string */
		$listing_type_inner_join = $this->_getListingTypeInnerJoinString($model, $listing_type);
		$queryString = "
			SELECT
			Marker.*,
			Listing.*,
			".$model::listing_type($listing_type).".*,
			(GLength(
			LineStringFromWKB(
			  LineString(
			    coordinates, 
			    GeomFromText('POINT(".$latitude." ".$longitude.")')
			  )
			 )
			))
			AS distance,
			X(Marker.coordinates) as latitude,
			Y(Marker.coordinates) as longitude
			FROM markers Marker
			inner join listings Listing on Listing.marker_id = Marker.marker_id ".
			$listing_type_inner_join .
			" order by distance";
		$listings = $model->query($queryString);

		/* Filter for only the listings within $radius of the $target_lat_long */
		$filteredListings = $this->_filterQueryResultsByDistance($listings, $radius);
        return $filteredListings;
	}

/* ------------------------------- private functions ------------------------------------ */

	/*
	Takes as input the result of a distance-based query as well as a radius.
	Returns an array of all listings that have distance values <= $radius
	REQUIRES: 
	- There exists a float at [0]['distance'] that is the distance from the initial target point 
	- Listings are sorted by distance in ascending order.
	*/
	private function _filterQueryResultsByDistance($listings, $radius)
	{
		$matchedResults = array();
		App::import('model', 'Rental');
		$Rental = new Rental();
        foreach ($listings as &$listing) {
    		if (!array_key_exists(0, $listing) || !array_key_exists('distance', $listing[0]) || 
    			!array_key_exists('latitude', $listing[0]) || !array_key_exists('longitude', $listing[0]))
    			continue;
    			
    		if ($listing[0]['distance'] * $this->ONE_DEGREE_IN_MILES < $radius){
            	/* TODO: THIS SHOULDN'T BE IN THIS SPOT */
            	if (array_key_exists('Marker', $listing) && array_key_exists('building_type_id', $listing['Marker']))
            		$listing["Marker"]["building_type_id"] = $Rental->building_type(intval($listing['Marker']['building_type_id']));

            	/* Move latitude and longitude aliases into Marker object */
            	$listing['Marker']['latitude'] = $listing[0]['latitude'];
            	$listing['Marker']['longitude'] = $listing[0]['longitude'];

            	/* Remove the distance index...having this there will mess up client-side caching process */
            	unset($listing[0]);
            	array_push($matchedResults, $listing);
    		}
    		else
    			break;
        }

        return $matchedResults;
	}

	/* 
	Returns the inner join piece of a location-based query for the listing type table (Rental, Sublet, Parking)
	INPUTS:
	- $listingModel is the calling instance of a Listing model
	- $listing_type is an integer
	*/
	private function _getListingTypeInnerJoinString($listingModel, $listing_type)
	{
		$listing_type_string = $listingModel::listing_type($listing_type); /* ex. 'Rental' */
		$listing_type_table_name = lcfirst($listing_type_string) . 's';
        return "inner join ".$listing_type_table_name." ".$listing_type_string." on ".$listing_type_string.
        	".listing_id = Listing.listing_id";
	}
}