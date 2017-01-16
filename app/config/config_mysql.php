<?php

return [
    /*
	'dsn'     		  => "mysql:host=blu-ray.student.bth.se;dbname=mara14;",
    'username'        => "mara14",
    'password'        => "2qR7W%2t",
	*/
	
    'dsn'    	  	  => "mysql:host=localhost;dbname=test;",
    'username'        => "root",
    'password'        => "",
	
	
    'driver_options'  => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
    'table_prefix'    => "univ_",
	//'verbose'		  => true,
	'debug_connect' => true,

];