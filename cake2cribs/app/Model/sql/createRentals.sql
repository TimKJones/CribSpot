create table rentals (
rental_id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
listing_id INTEGER NOT NULL,
user_id INTEGER NOT NULL,
street_address VARCHAR(255),
city VARCHAR(255),
state VARCHAR(2),
zipcode VARCHAR(12),
unit_style_options INTEGER,
unit_style_type VARCHAR (50),
unit_style_description VARCHAR (50),
building_name VARCHAR (255),
beds INTEGER,
min_occupancy INTEGER,
max_occupancy INTEGER,
building_type INTEGER,
rent INTEGER,
rent_negotiable BOOLEAN,
unit_count INTEGER,
start_date DATETIME,
alternate_start_date DATETIME,
lease_length INTEGER,
available BOOLEAN,
baths INTEGER,
air INTEGER,
parking_type INTEGER,
parking_spots INTEGER,
street_parking BOOLEAN,
furnished_type INTEGER,
pets_type INTEGER,
smoking BOOLEAN,
square_feet INTEGER,
year_built INTEGER,
electric INTEGER,
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
highlights TEXT,
description TEXT,
waitlist BOOLEAN,
waitlist_open_date DATETIME,
lease_office_street_address VARCHAR(255),
lease_office_city VARCHAR(255),
lease_office_state VARCHAR(2),
lease_office_zipcode VARCHAR(12),
contact_email VARCHAR(255),
contact_phone VARCHAR(11),
website VARCHAR(255),
created DATETIME,
modified DATETIME
);