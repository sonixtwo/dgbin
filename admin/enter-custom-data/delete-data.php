<?php
	include "../../shared/settings.php";
	include "../../shared/functions.php";
	
	session_start()
		or die("Could not start session.");
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	$items = $_SESSION["items"];
	
	foreach ($items as &$line)
	{
		$listIndex = $line["list_index"];
		
		if (isset($_REQUEST["checkbox-$listIndex"]) == true)
		{
			mysql_query("DELETE FROM list WHERE list_index = " . $listIndex, $link)
				or die("Could not update record: " . mysql_error());
		}				
	}

	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
	
	header("Location: enter-custom-data.php");
?>