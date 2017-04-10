<?php
	include "../../shared/settings.php";
	include "../../shared/fantasygames-functions.php";

	session_start()
		or die("Could not start session.");
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	$eventKey = $_REQUEST["eventKey"];
	$groupName = $_REQUEST["groupName"];

	runDraft( $link, $eventKey, $groupName );
		
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
	
	header("Location: manage-events.php");
?>