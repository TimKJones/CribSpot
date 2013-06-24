create table unit_style_options (
id INTEGER NOT NULL,
name VARCHAR(255),
description varchar(255),
PRIMARY KEY (id)
);

insert into listing_types values (1, "Rental", "Posted by the owner or property manager");
insert into listing_types values (2, "Sublet", "Posted by the person currently renting the unit");
insert into listing_types values (3, "Parking", "A parking spot");