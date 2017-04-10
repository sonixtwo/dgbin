<?php
	include "../../shared/settings.php";

	session_start()
		or die("Could not start session.");
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	$eventKey = $_REQUEST["eventKey"];
	$groupName = $_REQUEST["groupName"];
	$division = $_REQUEST["division"];

	mysql_query("DELETE FROM fantasy_games WHERE fg_event_ref = " . mysql_real_escape_string($eventKey, $link) . " AND fg_group_name = '" . mysql_real_escape_string($groupName, $link) . "'")
		or die("Could not delete from fantasy_games table.  " . mysql_error());

	$pickNumbers = range(1, count($_REQUEST['fantasyUsers']));
	shuffle($pickNumbers);
	
	$index = 0;
	
	foreach ($_REQUEST['fantasyUsers'] as $fantasyUser)
	{
		mysql_query("INSERT INTO fantasy_games ( fg_creator, fg_event_ref, fg_division, fg_group_name, fg_user_ref, fg_pick_number ) " .
					"VALUES ( " . ADMIN_USER_ID . ", " . mysql_real_escape_string($eventKey, $link) . ", '" . mysql_real_escape_string($division, $link) . "', '" . mysql_real_escape_string($groupName, $link) . "', " . mysql_real_escape_string($fantasyUser, $link) . ", " . $pickNumbers[$index] . " )")
			or die("Could not insert into fantasy_games table.  " . mysql_error());
			
		$index++;
	}
		
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
	
	header("Location: manage-events.php");
?>