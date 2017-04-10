<?php
	include "../../shared/settings.php";
	include "../../shared/event-functions.php";
	
	session_start()
		or die("Could not start session.");
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	$eventKey = $_REQUEST["eventKey"];

	$eventData = getEvent($link, $eventKey);
	
	$pageContents = file_get_contents($eventData["event_url"]);
	//$pageContents = file_get_contents("C:/Users/Neal A Day/Desktop/players.txt");
	
	$groupResults = split("class=\"division\"", $pageContents);
	
	$playersFound = array();
	
	for ($i=1; $i < count($groupResults); $i++)
	{
		$divisionName = getElementContent($groupResults[$i], 0 );

		$playerPage = FALSE;
		if (strpos($groupResults[$i], "class=\"place\"") == TRUE)
		{ 
			$playerResults = split("class=\"place\"", $groupResults[$i]);
			$j=2;
		}
		else
		{
			$playerPage = TRUE;
			$playerResults = split("class=\"player\"", $groupResults[$i]);
			$j=1;
		}
		
		for(; $j < count($playerResults); $j++)
		{	
			$startingPosition = 0;

			$place = NULL;
			$playerName = NULL;
			$pdgaNumber = NULL;
			$playerRating = NULL;
			$city = NULL;
			$state = NULL;
			$country = NULL;
			$par = NULL;
			$round1 = NULL;
			$round1Rating = NULL;
			$round2 = NULL;
			$round2Rating = NULL;
			$round3 = NULL;
			$round3Rating = NULL;
			$round4 = NULL;
			$round4Rating = NULL;
			$round5 = NULL;
			$round5Rating = NULL;
			$total = NULL;

			if ($playerPage == TRUE)
			{
				if (strpos($playerResults[$j], "/player/", $startingPosition) !== FALSE)
				{
					$startingPosition = strpos($playerResults[$j], "/player/", $startingPosition);
					$playerName = getElementContent($playerResults[$j], $startingPosition);
				}
				else
				{
					$playerName = getElementContent($playerResults[$j], $startingPosition);
				}
			
				if (strpos($playerResults[$j], "class=\"pdga-number\"", $startingPosition) !== FALSE)
				{
					$startingPosition = strpos($playerResults[$j], "class=\"pdga-number\"", $startingPosition);
					$pdgaNumber = getElementContent($playerResults[$j], $startingPosition);
				}
					
				if (strpos($playerResults[$j], "class=\"player-rating", $startingPosition) !== FALSE)
				{
					$startingPosition = strpos($playerResults[$j], "class=\"player-rating", $startingPosition);
					$playerRating = getElementContent($playerResults[$j], $startingPosition);
				}
				
				if (strpos($playerResults[$j], "class=\"city\"", $startingPosition) !== FALSE)
				{
					$startingPosition = strpos($playerResults[$j], "class=\"city\"", $startingPosition);
					$city = getElementContent($playerResults[$j], $startingPosition);
				}

				if (strpos($playerResults[$j], "class=\"state\"", $startingPosition) !== FALSE)
				{
					$startingPosition = strpos($playerResults[$j], "class=\"state\"", $startingPosition);
					$state = getElementContent($playerResults[$j], $startingPosition);
				}

				if (strpos($playerResults[$j], "class=\"country\"", $startingPosition) !== FALSE)
				{
					$startingPosition = strpos($playerResults[$j], "class=\"country\"", $startingPosition);
					$country = getElementContent($playerResults[$j], $startingPosition);
				}
			}
			else
			{
				$place = getElementContent($playerResults[$j], $startingPosition);
	
				if (strpos($playerResults[$j], "/player/", $startingPosition) !== FALSE)
				{
					$startingPosition = strpos($playerResults[$j], "/player/", $startingPosition);
					$playerName = getElementContent($playerResults[$j], $startingPosition);
				}
				else
				{
					if (strpos($playerResults[$j], "class=\"player\"", $startingPosition) !== FALSE)
					{
						$startingPosition = strpos($playerResults[$j], "class=\"player\"", $startingPosition);
						$playerName = getElementContent($playerResults[$j], $startingPosition);
					}
				}
		
				if (strpos($playerResults[$j], "class=\"pdga-number\"", $startingPosition) !== FALSE)
				{
					$startingPosition = strpos($playerResults[$j], "class=\"pdga-number\"", $startingPosition);
					$pdgaNumber = getElementContent($playerResults[$j], $startingPosition);
				}
					
				if (strpos($playerResults[$j], "class=\"player-rating", $startingPosition) !== FALSE)
				{
					$startingPosition = strpos($playerResults[$j], "class=\"player-rating", $startingPosition);
					$playerRating = getElementContent($playerResults[$j], $startingPosition);
				}
				
				if (strpos($playerResults[$j], "class=\"par", $startingPosition) !== FALSE)
				{
					$startingPosition = strpos($playerResults[$j], "class=\"par", $startingPosition);
					$par = getElementContent($playerResults[$j], $startingPosition);
				}
				
				$startingPosition = strpos($playerResults[$j], "class=\"round\"", $startingPosition);
				$round1 = getElementContent($playerResults[$j], $startingPosition);
	
				$startingPosition = strpos($playerResults[$j], "class=\"round-rating\"", $startingPosition);
				$round1Rating = getElementContent($playerResults[$j], $startingPosition);
	
				if (strpos($playerResults[$j], "class=\"round\"", $startingPosition) !== FALSE)
				{
					$startingPosition = strpos($playerResults[$j], "class=\"round\"", $startingPosition);
					$round2 = getElementContent($playerResults[$j], $startingPosition);
		
					$startingPosition = strpos($playerResults[$j], "class=\"round-rating\"", $startingPosition);
					$round2Rating = getElementContent($playerResults[$j], $startingPosition);
	
					if (strpos($playerResults[$j], "class=\"round\"", $startingPosition) !== FALSE)
					{
						$startingPosition = strpos($playerResults[$j], "class=\"round\"", $startingPosition);
						$round3 = getElementContent($playerResults[$j], $startingPosition);
			
						$startingPosition = strpos($playerResults[$j], "class=\"round-rating\"", $startingPosition);
						$round3Rating = getElementContent($playerResults[$j], $startingPosition);
	
						if (strpos($playerResults[$j], "class=\"round\"", $startingPosition) !== FALSE)
						{
							$startingPosition = strpos($playerResults[$j], "class=\"round\"", $startingPosition);
							$round4 = getElementContent($playerResults[$j], $startingPosition);
				
							$startingPosition = strpos($playerResults[$j], "class=\"round-rating\"", $startingPosition);
							$round4Rating = getElementContent($playerResults[$j], $startingPosition);
	
							if (strpos($playerResults[$j], "class=\"round\"", $startingPosition) !== FALSE)
							{
								$startingPosition = strpos($playerResults[$j], "class=\"round\"", $startingPosition);
								$round5 = getElementContent($playerResults[$j], $startingPosition);
					
								$startingPosition = strpos($playerResults[$j], "class=\"round-rating\"", $startingPosition);
								$round5Rating = getElementContent($playerResults[$j], $startingPosition);
							}
						}
					}
				}
				
				if (strpos($playerResults[$j], "class=\"total\"", $startingPosition) !== FALSE)
				{
					$startingPosition = strpos($playerResults[$j], "class=\"total\"", $startingPosition);
					$total = getElementContent($playerResults[$j], $startingPosition);
					if ($total == "DNF")
					{
						$total = NULL;
					}
				}
			}
						
			$foundPlayers[] = "'" . mysql_real_escape_string($playerName, $link) . "'";

			if (doesEventPlayerExist($link, $eventKey, $playerName) == TRUE)
			{
				mysql_query("UPDATE eventdetails SET division_name = '" . mysql_real_escape_string($divisionName, $link) . "'" .
				            ( $place == NULL ? "" : ", place = " . mysql_real_escape_string($place, $link) ) .
				            ( $pdgaNumber == NULL ? "" : ", pdga_number = '" . mysql_real_escape_string($pdgaNumber, $link) . "'" ) .
				            ( $city == NULL ? "" : ", city = '" . mysql_real_escape_string($city, $link) . "'" ) .
				            ( $state == NULL ? "" : ", state = '" . mysql_real_escape_string($state, $link) . "'" ) .
				            ( $country == NULL ? "" : ", country = '" . mysql_real_escape_string($country, $link) . "'" ) .
				            ( $playerRating == NULL ? "" : ", player_rating = " . mysql_real_escape_string($playerRating, $link) ) .
				            ( $par == NULL ? "" : ", par = '" . mysql_real_escape_string($par, $link) . "'" ) .
				            ( $round1 == NULL ? "" : ", round_1 = " . mysql_real_escape_string($round1, $link) ) .
				            ( $round1Rating == NULL ? "" : ", round_1_rating = " . mysql_real_escape_string($round1Rating, $link) ) .
				            ( $round2 == NULL ? "" : ", round_2 = " . mysql_real_escape_string($round2, $link) ) .
				            ( $round2Rating == NULL ? "" : ", round_2_rating = " . mysql_real_escape_string($round2Rating, $link) ) .
				            ( $round3 == NULL ? "" : ", round_3 = " . mysql_real_escape_string($round3, $link) ) .
				            ( $round3Rating == NULL ? "" : ", round_3_rating = " . mysql_real_escape_string($round3Rating, $link) ) .
				            ( $round4 == NULL ? "" : ", round_4 = " . mysql_real_escape_string($round4, $link) ) .
				            ( $round4Rating == NULL ? "" : ", round_4_rating = " . mysql_real_escape_string($round4Rating, $link) ) .
				            ( $round5 == NULL ? "" : ", round_5 = " . mysql_real_escape_string($round5, $link) ) .
				            ( $round5Rating == NULL ? "" : ", round_5_rating = " . mysql_real_escape_string($round5Rating, $link) ) .
				            ( $total == NULL ? "" : ", total = " . mysql_real_escape_string($total, $link) ) .
				            " WHERE event_key = " . mysql_real_escape_string($eventKey, $link) . 
				            " AND player_name = " . ( $playerName == NULL ? "null" : "'" . mysql_real_escape_string($playerName, $link) . "'" ), $link)
					or die("Could not update eventdetails table.  " . mysql_error());
			}
			else
			{
				mysql_query("INSERT INTO eventdetails ( event_key, division_name, place, player_name, pdga_number, city, state, country, player_rating, par, round_1, round_1_rating, round_2, round_2_rating, round_3, round_3_rating, round_4, round_4_rating, round_5, round_5_rating, total ) " .
							"VALUES ( " . mysql_real_escape_string($eventKey, $link) . 
							", '" . mysql_real_escape_string($divisionName, $link) . 
							"', " . ( $place == NULL ? "null" : mysql_real_escape_string($place, $link) ) . 
							", " . ( $playerName == NULL ? "null" : "'" . mysql_real_escape_string($playerName, $link) . "'" ) . 
							", " . ( $pdgaNumber == NULL ? "null" : "'" . mysql_real_escape_string($pdgaNumber, $link) . "'" ) . 
							", " . ( $city == NULL ? "null" : "'" . mysql_real_escape_string($city, $link) . "'" ) . 
							", " . ( $state == NULL ? "null" : "'" . mysql_real_escape_string($state, $link) . "'" ) . 
							", " . ( $country == NULL ? "null" : "'" . mysql_real_escape_string($country, $link) . "'" ) . 
							", " . ( $playerRating == NULL ? "null" : mysql_real_escape_string($playerRating, $link) ) . 
							", " . ( $par == NULL ? "null" : "'" . mysql_real_escape_string($par, $link) . "'" ) . 
							", " . ( $round1 == NULL ? "null" : mysql_real_escape_string($round1, $link) ) . 
							", " . ( $round1Rating == NULL ? "null" : mysql_real_escape_string($round1Rating, $link) ) . 
							", " . ( $round2 == NULL ? "null" : mysql_real_escape_string($round2, $link) ) . 
							", " . ( $round2Rating == NULL ? "null" : mysql_real_escape_string($round2Rating, $link) ) . 
							", " . ( $round3 == NULL ? "null" : mysql_real_escape_string($round3, $link) ) . 
							", " . ( $round3Rating == NULL ? "null" : mysql_real_escape_string($round3Rating, $link) ) . 
							", " . ( $round4 == NULL ? "null" : mysql_real_escape_string($round4, $link) ) . 
							", " . ( $round4Rating == NULL ? "null" : mysql_real_escape_string($round4Rating, $link) ) . 
							", " . ( $round5 == NULL ? "null" : mysql_real_escape_string($round5, $link) ) . 
							", " . ( $round5Rating == NULL ? "null" : mysql_real_escape_string($round5Rating, $link) ) . 
							", " . ( $total == NULL ? "null" : mysql_real_escape_string($total, $link) ) . " )", $link)
					or die("Could not insert into eventdetails table.  " . mysql_error());
			}
		}
	}
	
	if (count($foundPlayers) > 0)
	{
		mysql_query("DELETE FROM eventdetails WHERE event_key = " . mysql_real_escape_string($eventKey, $link) . " AND player_name NOT IN ( " . join(",", $foundPlayers) . " )", $link)
			or die("Could not delete from events table.  " . mysql_error());
	}
	
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
	
	header("Location: manage-events.php");
	
	function getElementContent($content, $startingPosition)
	{
		$startPosition = strpos($content, ">", $startingPosition) + 1;
		$endPosition = strpos($content, "<", $startPosition);
		
		$elementContent = trim(substr($content, $startPosition, $endPosition - $startPosition));
		
		return htmlspecialchars_decode($elementContent, ENT_QUOTES);
	}
?>