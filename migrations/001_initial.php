<?php
return [
	"up" => [
		"CREATE TABLE rooms(id INT not null primary key auto_increment,name VARCHAR(50),description TEXT,price_normal DECIMAL(18,2),price_peak DECIMAL(18,2),price_mid DECIMAL(18,2));",
        "CREATE TABLE reservations(id INT not null primary key auto_increment,client_first_name VARCHAR(100),client_surname  VARCHAR(100),client_telephone VARCHAR(10),client_email_address VARCHAR(255), check_in_datetime DATETIME,check_out_datetime DATETIME,no_rooms_booked TINYINT,date_of_booking DATE,total_cost DECIMAL(18, 2),paid TINYINT default 0,paid_amount DECIMAL(18, 2));",
		"CREATE TABLE reserved_rooms(room_id INT not null,reservation_id INT not null,check_in_datetime DATETIME,check_out_datetime DATETIME, PRIMARY KEY(room_id, reservation_id));",
		              
	],
	"down" => [
	    "DROP TABLE rooms;",
	    "DROP TABLE reservations;",
	    "DROP TABLE reserved_rooms;",

	]
];