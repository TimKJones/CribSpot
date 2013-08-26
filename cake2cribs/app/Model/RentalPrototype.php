<?php

class RentalPrototype extends AppModel {
	public $name = 'Rental';
	public $uses = array();

	/* ---------- unit_style_options ---------- */
	const UNIT_STYLE_OPTIONS_UNIT = 0;
	const UNIT_STYLE_OPTIONS_LAYOUT = 1;
	const UNIT_STYLE_OPTIONS_ENTIRE_HOUSE = 2;

	public static function unit_style_options($value = null) {
		$options = array(
			self::UNIT_STYLE_OPTIONS_UNIT => __('Unit',true),
		    self::UNIT_STYLE_OPTIONS_LAYOUT => __('Layout',true),
		    self::UNIT_STYLE_OPTIONS_ENTIRE_HOUSE => __('Entire House',true),
		);
		return parent::enum($value, $options);
	}

	public static function unit_style_options_reverse($value = null) {
		$options = array(
			'Unit' => self::UNIT_STYLE_OPTIONS_UNIT,
			'Layout' => self::UNIT_STYLE_OPTIONS_LAYOUT,
			'Entire House' => self::UNIT_STYLE_OPTIONS_ENTIRE_HOUSE
		);
		return parent::StringToInteger($value, $options);
	}

	/* ---------- building_type ---------- */
	const BUILDING_TYPE_HOUSE = 0;
	const BUILDING_TYPE_APARTMENT = 1;
	const BUILDING_TYPE_DUPLEX = 2;
	const BUILDING_TYPE_CONDO = 3;
	const BUILDING_TYPE_TOWNHOUSE = 4;
	const BUILDING_TYPE_COOP = 5;
	const BUILDING_TYPE_DORM = 6;
	const BUILDING_TYPE_GREEK = 7;
	const BUILDING_TYPE_OTHER = 8;

	public static function building_type($value = null) {
		$options = array(
		    self::BUILDING_TYPE_HOUSE => __('House',true),
		    self::BUILDING_TYPE_APARTMENT => __('Apartment',true),
		    self::BUILDING_TYPE_DUPLEX => __('Duplex',true),
		    self::BUILDING_TYPE_CONDO => __('Condo',true),
		    self::BUILDING_TYPE_TOWNHOUSE => __('Townhouse',true),
		    self::BUILDING_TYPE_COOP => __('Co-Op',true),
		    self::BUILDING_TYPE_DORM => __('Dorm',true),
		    self::BUILDING_TYPE_GREEK => __('Greek',true),
		    self::BUILDING_TYPE_OTHER => __('Other',true),
		);
		return parent::enum($value, $options);
	}

	public static function building_type_reverse($value = null) {
		$options = array(
			'House' => self::BUILDING_TYPE_HOUSE,
			'Apartment' => self::BUILDING_TYPE_APARTMENT,
			'Duplex' => self::BUILDING_TYPE_DUPLEX,
			'Condo' => self::BUILDING_TYPE_CONDO,
			'Townhouse' => self::BUILDING_TYPE_TOWNHOUSE,
			'Co-Op' => self::BUILDING_TYPE_COOP,
			'Dorm' => self::BUILDING_TYPE_DORM,
			'Greek' => self::BUILDING_TYPE_GREEK,
			'Other' => self::BUILDING_TYPE_OTHER,
		);
		return parent::StringToInteger($value, $options);
	}

	/* ---------- air ---------- */
	const AIR_NO_AIR = 0;
	const AIR_CENTRAL = 1;
	const AIR_WALL_UNIT = 2;

	public static function air($value = null) {
		$options = array(
			self::AIR_NO_AIR => __('None',true),
		    self::AIR_CENTRAL => __('Central',true),
		    self::AIR_WALL_UNIT => __('Wall Unit',true),
		);
		return parent::enum($value, $options);
	}

	/* ---------- parking ---------- */
	const PARKING_NO_PARKING = 0;
	const PARKING_PARKING_LOT = 1;
	const PARKING_DRIVEWAY = 2;
	const PARKING_GARAGE = 3;
	const PARKING_OFF_SITE = 4;
	const PARKING_OTHER = 5;

	public static function parking($value = null) {
		$options = array(
		    self::PARKING_NO_PARKING => __('No',true),
		    self::PARKING_PARKING_LOT => __('Parking Lot',true),
		    self::PARKING_DRIVEWAY => __('Driveway',true),
		    self::PARKING_GARAGE => __('Garage',true),
		    self::PARKING_OFF_SITE => __('Off Site',true),
		    self::PARKING_OTHER => __('Other',true),
		);
		return parent::enum($value, $options);
	}

	/* ---------- furnished ---------- */
	const FURNISHED_NO = 0;
	const FURNISHED_PARTIALLY = 1;
	const FURNISHED_FULLY = 2;
	

	public static function furnished($value = null) {
		$options = array(
			self::FURNISHED_NO => __('No',true),
			self::FURNISHED_PARTIALLY => __('Partially',true),
		    self::FURNISHED_FULLY => __('Fully',true),
		);
		return parent::enum($value, $options);
	}

	/* ---------- pets ---------- */
	const PETS_NOT_ALLOWED = 0;
	const PETS_CATS_ONLY = 1;
	const PETS_DOGS_ONLY = 2;
	const PETS_CATS_AND_DOGS = 3;

	public static function pets($value = null) {
		$options = array(
			self::PETS_NOT_ALLOWED => __('Pets Not Allowed',true),
		    self::PETS_CATS_ONLY => __('Cats Only',true),
		    self::PETS_DOGS_ONLY => __('Dogs Only',true),
		    self::PETS_CATS_AND_DOGS => __('Cats and Dogs',true),
		);
		return parent::enum($value, $options);
	}

	public static function pets_reverse($value = null) {
		$options = array(
			'Pets Not Allowed' => self::PETS_NOT_ALLOWED,
			'Cats Only' => self::PETS_CATS_ONLY,
			'Dogs Only' => self::PETS_DOGS_ONLY,
			'Cats and Dogs' => self::PETS_CATS_AND_DOGS
		);
		return parent::StringToInteger($value, $options);
	}

	/* ---------- washer_dryer ---------- */
	const WASHER_DRYER_NONE = 0;
	const WASHER_DRYER_IN_UNIT = 1;
	const WASHER_DRYER_ON_SITE = 2;
	const WASHER_DRYER_OFF_SITE = 3;

	public static function washer_dryer($value = null) {
		$options = array(
			self::WASHER_DRYER_NONE => __('None',true),
		    self::WASHER_DRYER_IN_UNIT => __('In-Unit',true),
		    self::WASHER_DRYER_ON_SITE => __('On-Site',true),
		    self::WASHER_DRYER_OFF_SITE => __('Off-Site',true),
		);
		return parent::enum($value, $options);
	}

	public static function washer_dryer_reverse($value = null) {
		$options = array(
			'None' => self::WASHER_DRYER_NONE,
			'In-Unit' => self::WASHER_DRYER_IN_UNIT,
			'On-Site' => self::WASHER_DRYER_ON_SITE,
			'Off-Site' => self::WASHER_DRYER_OFF_SITE,
		);
		return parent::StringToInteger($value, $options);
	}

	/* ---------- utilities_included ---------- */
	const UTILITY_INCLUDED_NO = 0;
	const UTILITY_INCLUDED_YES = 1;
	const UTILITY_INCLUDED_FLAT_RATE = 2;

	public static function utilities_included($value = null) {
		$options = array(
		    self::UTILITY_INCLUDED_NO => __('No',true),
		    self::UTILITY_INCLUDED_YES => __('Yes',true),
		    self::UTILITY_INCLUDED_FLAT_RATE => __('Flat Rate',true),
		);
		return parent::enum($value, $options);
	}

	/* ------------------------------------------*/
}
?>