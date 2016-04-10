<?php

/**
  Basic routing - convention to follow:
     ControllerName#method
  There is no need to specify get parameters.

  Routes not listed below will be automatically 
  blocked by the framework.
   
**/
return [
    "Reservations#index",
    "Reservations#capture_details",
    "Reservations#search_rooms",
    "Reservations#toggle_room",
    "Reservations#confirm",
    "Reservations#cancel"

];