<?php 

class Listing extends AppModel {
	public $name = 'Listing';
	public $primaryKey = 'listing_id';
	/*public hasOne = array('Rental', 'Sublet', 'Parking');*/
	public $validate = array(
		'listing_id' => 'alphaNumeric',
		'listing_type' => 'alphaNumeric'
	);

	/* ---------- unit_style_options ---------- */
	const LISTING_TYPE_RENTAL = 0;
	const LISTING_TYPE_SUBLET = 1;
	const LISTING_TYPE_PARKING = 2;

	public static function listing_type($value = null) {
		$options = array(
		    self::LISTING_TYPE_RENTAL => __('Rental',true),
		    self::LISTING_TYPE_SUBLET => __('Sublet',true),
		    self::LISTING_TYPE_PARKING => __('Parking',true),
		);
		return parent::enum($value, $options);
	}

	public function SaveListing($listing_type)
	{
		$newListing = array('Listing' => array('listing_type' => $listing_type));
		if ($this->save($newListing))
			return array('listing_id' => $this->id);

		/* Listing failed to save - return error code */
		CakeLog::write("listingValidationErrors", print_r($this->validationErrors, true));
		return array("error" => "Failed to save listing");
	}
}	

?>