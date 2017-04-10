<?php
	include "../../shared/settings.php";
	include "../../shared/functions.php";

	session_start()
		or die("Could not start session.");
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	$type = $_REQUEST["typeIndex"];
	
	$parsedData = $_SESSION["parsedData"];
	foreach ($parsedData as &$value) 
	{
		$itemNumber = str_replace("'", "''", $value["itemNumber"]);
		$description = str_replace("'", "''", $value["description"]);
		
		$result = mysql_query("SELECT * FROM list WHERE list_item_number = '" . $itemNumber . "'", $link);
		if (mysql_num_rows($result) == 0)
		{
			mysql_query("INSERT INTO list (list_item_number, list_item_title, list_item_type_ref, list_data_complete) VALUES ('" . $itemNumber . "', '" . $description . "', '" . $type . "', 0)", $link)
				or die("Could not insert record: " . mysql_error());
		}

		mysql_free_result($result)
			or die("Could not free results: " . mysql_error());
	}
		
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
	
	header("Location: select-file.php");
?>