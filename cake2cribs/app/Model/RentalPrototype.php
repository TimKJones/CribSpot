<?php

class RentalPrototype extends AppModel {
	public $name = 'Rental';
	public $uses = array();

	/* ---------- unit_style_options ---------- */
	const UNIT_STYLE_OPTIONS_STYLE = 0;
	const UNIT_STYLE_OPTIONS_UNIT = 1;
	const UNIT_STYLE_OPTIONS_ENTIRE_UNIT = 2;

	public static function unit_style_options($value = null) {
		$options = array(
		    self::UNIT_STYLE_OPTIONS_STYLE => __('Style',true),
		    self::UNIT_STYLE_OPTIONS_UNIT => __('Unit',true),
		    self::UNIT_STYLE_OPTIONS_ENTIRE_UNIT => __('Entire Unit',true),
		);
		return parent::enum($value, $options);
	}

	/* ---------- building_type ---------- */
	const BUILDING_TYPE_HOUSE = 0;
	const BUILDING_TYPE_APARTMENT = 1;
	const BUILDING_TYPE_DUPLEX = 2;
	const BUILDING_TYPE_CONDO = 3;
	const BUILDING_TYPE_TOWNHOUSE = 4;
	const BUILDING_TYPE_COOP = 5;
	const BUILDING_TYPE_OTHER = 6;

	public static function building_type($value = null) {
		$options = array(
		    self::BUILDING_TYPE_HOUSE => __('House',true),
		    self::BUILDING_TYPE_APARTMENT => __('Apartment',true),
		    self::BUILDING_TYPE_DUPLEX => __('Duplex',true),
		    self::BUILDING_TYPE_CONDO => __('Condo',true),
		    self::BUILDING_TYPE_TOWNHOUSE => __('Townhouse',true),
		    self::BUILDING_TYPE_COOP => __('Coop',true),
		    self::BUILDING_TYPE_OTHER => __('Other',true),
		);
		return parent::enum($value, $options);
	}

	public static function building_type_reverse($value = null) {
		$options = array(
			'house' => self::BUILDING_TYPE_HOUSE,
			'apartment' => self::BUILDING_TYPE_APARTMENT,
			'duplex' => self::BUILDING_TYPE_DUPLEX,
			'condo' => self::BUILDING_TYPE_CONDO,
			'townhouse' => self::BUILDING_TYPE_TOWNHOUSE,
			'coop' => self::BUILDING_TYPE_COOP,
			'other' => self::BUILDING_TYPE_OTHER,
		);
		return parent::StringToInteger($value, $options);
	}

	/* ---------- air ---------- */
	const AIR_CENTRAL = 0;
	const AIR_WALL_UNIT = 1;
	const AIR_NONE= 2; 

	public static function air($value = null) {
		$options = array(
		    self::AIR_CENTRAL => __('Central',true),
		    self::AIR_WALL_UNIT => __('Wall Unit',true),
		    self::AIR_NONE => __('None',true),
		);
		return parent::enum($value, $options);
	}

	/* ---------- parking ---------- */
	const PARKING_PARKING_LOT = 0;
	const PARKING_DRIVEWAY = 1;
	const PARKING_GARAGE = 2;
	const PARKING_OFF_SITE = 3;

	public static function parking($value = null) {
		$options = array(
		    self::PARKING_PARKING_LOT => __('Parking Lot',true),
		    self::PARKING_DRIVEWAY => __('Driveway',true),
		    self::PARKING_GARAGE => __('Garage',true),
		    self::PARKING_OFF_SITE => __('Off Site',true),
		);
		return parent::enum($value, $options);
	}

	/* ---------- furnished ---------- */
	const FURNISHED_FULLY = 0;
	const FURNISHED_PARTIALLY = 1;
	const FURNISHED_NO = 2;

	public static function furnished($value = null) {
		$options = array(
		    self::FURNISHED_FULLY => __('Fully',true),
		    self::FURNISHED_PARTIALLY => __('Partially',true),
		    self::FURNISHED_NO => __('No',true),
		);
		return parent::enum($value, $options);
	}

	/* ---------- pets ---------- */
	const PETS_CATS_ONLY = 0;
	const PETS_DOGS_ONLY = 1;
	const PETS_CATS_AND_DOGS = 2;

	public static function pets($value = null) {
		$options = array(
		    self::PETS_CATS_ONLY => __('Cats Only',true),
		    self::PETS_DOGS_ONLY => __('Dogs Only',true),
		    self::PETS_CATS_AND_DOGS => __('Cats and Dogs',true),
		);
		return parent::enum($value, $options);
	}

	/* ---------- washer_dryer ---------- */
	const WASHER_DRYER_IN_UNIT = 0;
	const WASHER_DRYER_ON_SITE_FREE = 1;
	const WASHER_DRYER_ON_SITE_COIN_OPERATED = 2;
	const WASHER_DRYER_OFF_SITE = 3;

	public static function washer_dryer($value = null) {
		$options = array(
		    self::WASHER_DRYER_IN_UNIT => __('In Unit',true),
		    self::WASHER_DRYER_ON_SITE_FREE => __('On-Site Free',true),
		    self::WASHER_DRYER_ON_SITE_COIN_OPERATED => __('On-Site Coin Operated',true),
		    self::WASHER_DRYER_OFF_SITE => __('Off-Site',true),
		);
		return parent::enum($value, $options);
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