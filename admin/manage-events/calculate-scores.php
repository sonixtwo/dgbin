<?php
	include "../../shared/settings.php";
	include "../../shared/event-functions.php";
	include "../../shared/fantasygames-functions.php";

	session_start()
		or die("Could not start session.");
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	$eventKey = $_REQUEST["eventKey"];
	$groupName = $_REQUEST["groupName"];

	$fantasyGames = getFantasyGamesForEventAndGroup($link, $eventKey, $groupName);
	if ((count($fantasyGames) == 1) && ($fantasyGames[0]["fg_type"] == 2))
	{
		calculateDraftOrder($link, $eventKey, $groupName, FANTASY_B_PICKS_SCORED);
		runDraft2($link, $eventKey, $groupName);
	}
	
	$eventPlayers = getEventPlayers($link, $eventKey, $groupName);

	foreach ($eventPlayers as &$eventPlayer)
	{
		$roundCount = 0;
		$roundTotal = 0;
		
		if ($eventPlayer["round_1_rating"] != "")
		{
			$roundCount += 1;
			$roundTotal += $eventPlayer["round_1_rating"];
		}
		
		if ($eventPlayer["round_2_rating"] != "")
		{
			$roundCount += 1;
			$roundTotal += $eventPlayer["round_2_rating"];
		}

		if ($eventPlayer["round_3_rating"] != "")
		{
			$roundCount += 1;
			$roundTotal += $eventPlayer["round_3_rating"];
		}

		if ($eventPlayer["round_4_rating"] != "")
		{
			$roundCount += 1;
			$roundTotal += $eventPlayer["round_4_rating"];
		}

		if ($eventPlayer["round_5_rating"] != "")
		{
			$roundCount += 1;
			$roundTotal += $eventPlayer["round_5_rating"];
		}

		$roundAverage = $roundTotal / $roundCount;
		$ratingScore = $roundAverage - min($eventPlayer["player_rating"], $roundAverage);
		
		$maxPlace = getMaxPlace($link, $eventPlayer["event_key"], $eventPlayer["division_name"]);
		$placeScore = $maxPlace["max_place"] - $eventPlayer["place"];
		
		$totalScore = $ratingScore + $placeScore;
		
		mysql_query("UPDATE fantasy_data SET fd_rating_score = " . $ratingScore . ", fd_place_score = " . $placeScore . ", fd_total_score = " . $totalScore . " WHERE fd_game_ref = " . $eventPlayer["fg_index"] . " AND fd_picked_player = '" . mysql_real_escape_string($eventPlayer["player_name"], $link) . "'", $link)
			or die("Could not update fantasy_data table.  " . mysql_error());

		mysql_query("UPDATE fantasy_games SET fg_status = 3, fg_total_score = ( SELECT SUM(fd_total_score) FROM ( SELECT fd_total_score FROM fantasy_data WHERE fd_game_ref = " . $eventPlayer["fg_index"] . " ORDER by fd_total_score DESC LIMIT " . PLAYERS_TO_CALC . " ) AS top_scores ) WHERE fg_index = " . $eventPlayer["fg_index"] . " AND fg_type = 1", $link)
			or die("Could not update into fantasy_games table.  " . mysql_error());
	
		mysql_query("UPDATE fantasy_games SET fg_status = 3, fg_total_score = ( SELECT SUM(fd_total_score) FROM ( SELECT fd_total_score FROM fantasy_data WHERE fd_game_ref = " . $eventPlayer["fg_index"] . " ORDER by fd_total_score DESC LIMIT " . FANTASY_B_PICKS_SCORED . " ) AS top_scores ) WHERE fg_index = " . $eventPlayer["fg_index"] . " AND fg_type = 2", $link)
			or die("Could not update into fantasy_games table.  " . mysql_error());
	}
		
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
	
	header("Location: manage-events.php");
?>