<?php
	include "../../shared/settings.php";
	include "../../shared/functions.php";
	include "../../shared/userdata-functions.php";
		
	session_start()
		or die("Could not start session.");
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	$userDataIndex = $_REQUEST["userDataIndex"];

	mysql_query("DELETE FROM user_data WHERE user_data_index = " . $userDataIndex)
		or die("Could not update user_data table.  " . mysql_error());
	
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
	
	header("Location: manage-users.php");
?>