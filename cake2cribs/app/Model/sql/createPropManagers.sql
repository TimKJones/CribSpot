create table property_managers (
property_manager_id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
user_id INTEGER NOT NULL,
lease_office_address VARCHAR(255),
contact_email VARCHAR(150),
contact_phone VARCHAR(20),
website VARCHAR(255),
created DATETIME,
modified DATETIME
);