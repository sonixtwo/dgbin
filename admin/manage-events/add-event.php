<?php
	include "../../shared/settings.php";

	session_start()
		or die("Could not start session.");
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	$eventName = $_REQUEST["eventName"];
	$eventURL = $_REQUEST["eventURL"];
	
	mysql_query("INSERT INTO events ( event_name, event_url ) VALUES ( '" . mysql_real_escape_string($eventName, $link) . "', '" . mysql_real_escape_string($eventURL, $link) . "' )")
		or die("Could not insert into events table.  " . mysql_error());
	
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
	
	header("Location: manage-events.php");
?>