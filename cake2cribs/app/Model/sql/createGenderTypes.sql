create table gender_types (
id INTEGER NOT NULL,
name VARCHAR(255),
description varchar(255),
PRIMARY KEY (id)
);

insert into gender_types values (1, "Male", "Male");
insert into gender_types values (2, "Female", "Female");
insert into gender_types values (3, "Mix", "Mix of male and female");

update housemates set gender = 1 where gender="Male";
update housemates set gender = 2 where gender="Female";
update housemates set gender = 3 where gender="Mix";