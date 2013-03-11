create table click_analytics (
click_analytic_id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
filter_analytic_id INTEGER, 	 
marker_id INTEGER,
user_id INTEGER,
created DATETIME
);

create table favorites (
favorite_id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY, 
listing_id INTEGER NOT NULL, 
user_id INTEGER NOT NULL
);

CREATE TABLE listings 
(listing_id INTEGER AUTO_INCREMENT, 
marker_id INTEGER, 
available boolean DEFAULT true, 
lease_range VARCHAR(255), 
unit_type VARCHAR(255), 
unit_description VARCHAR(255), 
beds INTEGER, baths DOUBLE, 
rent DOUBLE, 
electric BOOLEAN, 
water BOOLEAN, 
heat BOOLEAN, 
air BOOLEAN, 
parking BOOLEAN, 
furnished BOOLEAN, 
url VARCHAR(255), 
realtor_id INTEGER NOT NULL, 
PRIMARY KEY (listing_id)); 

CREATE TABLE realtors (realtor_id INTEGER AUTO_INCREMENT PRIMARY KEY, company VARCHAR(255), username VARCHAR(50), password VARCHAR(50), email VARCHAR(255));

create table filter_analytics (
filter_analytic_id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY, 
min_rent DOUBLE, 
max_rent DOUBLE,
min_beds DOUBLE,
max_beds DOUBLE,
fall boolean,
spring boolean,
other boolean,
house boolean,
apartment boolean,
duplex boolean,
count INTEGER
);

CREATE TABLE markers (marker_id INTEGER AUTO_INCREMENT, alternate_name VARCHAR(255), unit_type VARCHAR(255), address VARCHAR(255), latitude DOUBLE, longitude DOUBLE, PRIMARY KEY (marker_id)); 
