create table parkings (
parking_id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
listing_id INTEGER NOT NULL,
created DATETIME,
modified DATETIME
);