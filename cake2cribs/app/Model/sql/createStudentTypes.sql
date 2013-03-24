create table student_types (
id INTEGER NOT NULL,
name VARCHAR(255),
description varchar(255),
PRIMARY KEY (id)
);

insert into student_types values (1, "Graduate", "Graduate Student");
insert into student_types values (2, "Undergraduate", "Undergraduate Student");

update housemates set student_type = 1 where student_type="Graduate";
update housemates set student_type = 2 where student_type="Undergraduate";