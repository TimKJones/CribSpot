create table listings (
listing_id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
listing_type INTEGER NOT NULL,
created DATETIME,
modified DATETIME
);