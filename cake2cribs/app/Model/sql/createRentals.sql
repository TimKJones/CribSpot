create table rentals (
rental_id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
listing_id INTEGER NOT NULL,
address VARCHAR(255),
unit_style_options INTEGER, --> Make a table for this (column A in spreadsheet)
unit_style_type VARCHAR (50),
unit_style_description VARCHAR (50),
building_name VARCHAR (255),
beds INTEGER,
min_occupancy INTEGER,
max_occupancy INTEGER,
building_type INTEGER,
rent INTEGER, --> this is total rent, not per person
rent_negotiable BOOLEAN,
unit_count INTEGER,
start_date DATETIME,
alternate_start_date DATETIME,
lease_length INTEGER, --> IN MONTHS
available BOOLEAN,
baths INTEGER,
air INTEGER,
parking_type INTEGER, --> need table for this - what are all the options?
parking_spots INTEGER,
street_parking BOOLEAN,
furnished_type INTEGER,
pets_type INTEGER, --> need new table for this
smoking BOOLEAN,
square_feet INTEGER,
year_built INTEGER,
electric INTEGER, --> Need new table for this
water INTEGER,
gas INTEGER,
heat INTEGER,
sewage INTEGER,
trash INTEGER,
cable INTEGER,
internet INTEGER,
utility_total_flat_rate INTEGER,
utility_estimate_winter INTEGER,
utility_estimate_summer INTEGER,
deposit INTEGER,
admin_fee INTEGER,
parking_fee INTEGER, --> price per parking spot
furniture_fee INTEGER,
pets_fee INTEGER,
amenity_fee INTEGER, --> ??? WHAT IS THIS?
upper_floor_fee INTEGER,
extra_occupants_fee INTEGER,
other_fees_amount INTEGER,
other_fees_description INTEGER,
deals TEXT, --> ??? Is this really a necessary field?
highlights TEXT, --> ??? What is this for?
description TEXT,
waitlist BOOLEAN,
waitlist_open_date,
lease_office_address,
contact_email,
contact_phone,
website,
created DATETIME,
modified DATETIME
);