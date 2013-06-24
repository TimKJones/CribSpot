<?php 

class Rental extends AppModel {
	public $name = 'Rental';
	public $primaryKey = 'rental_id';
	public $hasOne = array('Listing');
	public $validate = array(
		'rental_id' => 'numeric',
		'listing_id' => 'numeric',
		'address' => 'alphaNumeric',
		'unit_style_options' => 'alphaNumeric',
		'unit_style_type' => 'alphaNumeric',
		'unit_style_description' => 'alphaNumeric',
		'building_name' => 'alphaNumeric',
		'beds' => 'numeric',
		'min_occupancy' => 'numeric',
		'max_occupancy' => 'numeric',
		'building_type' => 'numeric',
		'rent' => 'numeric',
		'rent_negotiable' => 'boolean',
		'unit_count' => 'numeric',
		'start_date' => 'date',
		'alternate_start_date' => 'date',
		'lease_length' => 'numeric', /* in months */
		'available' => 'boolean',
		'baths' => 'numeric',
		'air' => 'boolean',
		'parking_type' => 'numeric',
		'parking_spots' => 'numeric',
		'street_parking' => 'boolean',
		'furnished_type' => 'numeric',
		'pets_type' => 'numeric',
		'smoking' => 'boolean',
		'square_feet' => 'numeric',
		'year_built' => 'numeric',
		'electric' => 'numeric',
		'water' => 'numeric',
		'gas' => 'numeric',
		'heat' => 'numeric',
		'sewage' => 'numeric',
		'trash' => 'numeric',
		'cable' => 'numeric',
		'internet' => 'numeric',
		'utility_total_flat_rate' => 'numeric',
		'utility_estimate_winter' => 'numeric',
		'utility_estimate_summer' => 'numeric',
		'deposit' => 'numeric',
		/* MAKE TABLE FOR THESE OTHER FEES */
		/*'admin_fee' => 'alphaNumeric',
		'parking_fee' INTEGER,
		'furniture_fee' INTEGER,
		'pets_fee' INTEGER,
		'amenity_fee' INTEGER, --> ??? WHAT IS THIS?
		'upper_floor_fee' INTEGER,
		'extra_occupants_fee' INTEGER,
		'other_fees_amount' INTEGER,
		'other_fees_description' INTEGER,*/ 
		'highlights' => 'alphaNumeric',
		'description' => 'alphaNumeric',
		'waitlist' => 'boolean',
		'waitlist_open_date' => 'date',
		'lease_office_address' => 'alphaNumeric',
		'contact_email' => 'email',
		'contact_phone' => array('phone', null, 'us'),
		'website' => 'url',
		'is_complete' => 'boolean'
	);

	/*
	Marks the rental as either complete or incomplete, depending on whether all fields have been filled in.
	Then it saves the rental.
	REQUIRES: it has been verified that user is logged-in.
	*/
	public function Save($rental, $user_id)
	{
		if ($rental == null || $user_id == null || $user_id == 0)
			return false;

		$rental = $this->_markAsSaved($rental);

	}

	/*
	Delete the rental with rental_id = $rental_id.
	REQUIRES: it has been verified that the logged-in user owns this rental.
	*/
	public function Delete ($rental_id)
	{

	}

	/*
	Marks $rental (via the 'is_complete' field) as complete or incomplete by checking for all required fields.
	Returns modified $rental object;
	*/
	private function _markAsSaved($rental)
	{
		return $rental;
	}
}

?>