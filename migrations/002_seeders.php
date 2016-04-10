<?php
return [

    "up" => [
  
        "INSERT INTO rooms(name, description, price_normal) VALUES ('ensuite', 'luxury suite',988), ('ensuite-2', 'luxury suite',988) ,('ensuite-3', 'luxury suite',988), ('ensuite-4', 'luxury suite',988) ,('ensuite-5', 'luxury suite',988) , ('encom-1', 'economy room', 600), ('encom-2', 'economy room', 600), ('encom-3', 'economy room', 600), ('encom-4', 'economy room', 600), ('encom-5', 'economy room', 600), ('encom-6', 'economy room', 600), ('encom-7', 'economy room', 600), ('encom-8', 'economy room', 600), ('encom-9', 'economy room', 600), ('encom-10', 'economy room', 600), ('encom-11', 'economy room', 600), ('encom-12', 'economy room', 600), ('encom-13', 'economy room', 600), ('encom-14', 'economy room', 600), ('encom-15', 'economy room', 600), ('encom-16', 'economy room', 600), ('encom-17', 'economy room', 600), ('encom-18', 'economy room', 600), ('encom-19', 'economy room', 600), ('encom-20', 'economy room', 600)"
    ],

    "down" => [
        "TRUNCATE rooms"
    ]
];