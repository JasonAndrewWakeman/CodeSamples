<?php
	// Opens a connection to the database
	// Since it is a php file it won't open in a browser
	// It must be saved in exactly the save directory that the working directory is inside of. ie one folder outside of the main web documents folder.
		 
	/*
	Command that gives the database user the least amount of power
	as is needed.
	GRANT INSERT, SELECT, DELETE, UPDATE ON users.*
	
	SELECT : Select rows in tables
	INSERT : Insert new rows into tables
	UPDATE : Change data in rows
	DELETE : Delete existing rows 	
	*/
	// Defined as constants so that they can't be changed
	DEFINE ('DB_USER', 'u309R13');
	DEFINE ('DB_PASSWORD', 'GfS+RS3,3');
	DEFINE ('DB_HOST', 'barium.cs.iastate.edu');
	DEFINE ('DB_NAME', 'db309R13');
	 
	//$dbc will contain a resource link to the database
	// @ keeps the error from showing in the browser

	$dbc = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
	OR die('Could not connect to MySQL: ' .
	mysqli_connect_error());
	?>
