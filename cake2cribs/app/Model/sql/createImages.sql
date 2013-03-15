create table cake2cribs.images (
image_id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
sublet_id INTEGER, 	 
user_id INTEGER,
image_path VARCHAR(255),
is_primary BOOLEAN not null default 0, 
caption VARCHAR(255),
created DATETIME,
modified DATETIME
);