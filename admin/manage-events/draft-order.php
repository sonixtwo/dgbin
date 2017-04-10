<!DOCTYPE html>

<?php
	include "../../shared/settings.php";
	include "../../shared/event-functions.php";
	include "../../shared/userdata-functions.php";
	include "../../shared/fantasygames-functions.php";
	
	session_start()
		or die("Could not start session.");

	$eventKey = $_REQUEST["eventKey"];
	$groupName = $_REQUEST["groupName"];
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	calculateDraftOrder($link, $eventKey, $groupName, ROUNDS_PER_DRAFT);
	
	$draftOrder = getEventDraftOrder($link, $eventKey, $groupName);
	$fantasyData = getFantasyData($link, $eventKey, $groupName);
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../public.css" />
	</head>

	<body>
		<?php
			require("../menu.html");
		?>

		<form>
			<table>
				<?php
					$recordCount = 0;
					
					if (count($draftOrder) > 0)
					{
						echo "<tr>";

						foreach ($draftOrder as $fantasyUser)
						{
							echo "<td style=\"color:  white; width:  160px; border-bottom: 1px solid black; font-size:  small;\">" . htmlspecialchars($fantasyUser["user_data_user_name"]) . " (Team Score - " . $fantasyUser["fg_total_score"] . ")</td>";
						}
						
						echo "</tr><tr>";
	
						$round = 1;
						$stack = array();
												
						foreach ($fantasyData as &$fantasyUser)
						{
							if (($recordCount != 0) && ($recordCount % count($draftOrder) == 0))
							{
								while (count($stack) > 0)
								{
									if ($round % 2 == 0)
									{
										echo array_shift($stack); 
									}
									else
									{
										echo array_pop($stack);
									}
								}
															
								echo "</tr><tr>";
								$round++;
							}
							
							if ($fantasyUser["fd_picked_player"] == "")
							{
								$cellContents = "<div style=\"font-size:  small;\">" . htmlspecialchars($fantasyUser["fd_pick_number"]) . "</div>";

								array_unshift($stack, "<td style=\"color:  white; width:  160px; vertical-align:  top;\">" . $cellContents . "</td>");
							}
							else
							{
								$cellContents = "<div style=\"font-size:  small;\">" . htmlspecialchars($fantasyUser["fd_pick_number"]) . " - " . htmlspecialchars($fantasyUser["fd_picked_player"]) . "</div>";
								
								if ($fantasyUser["fd_rating_score"] != "")
								{
									$cellContents = $cellContents . "<div style=\"font-size:  x-small;\">Rating Score - " . $fantasyUser["fd_rating_score"] . "</div>";
								}
								
								if ($fantasyUser["fd_place_score"] != "")
								{
									$cellContents = $cellContents . "<div style=\"font-size:  x-small;\">Place Score - " . $fantasyUser["fd_place_score"] . "</div>";
								}

								if ($fantasyUser["fd_total_score"] != "")
								{
									$cellContents = $cellContents . "<div style=\"font-size:  x-small;\">Total Score - " . $fantasyUser["fd_total_score"] . "</div>";
								}

								array_unshift($stack, "<td style=\"color:  white; width:  160px; vertical-align:  top;\">" . $cellContents . "</td>");
							} 
							
							$recordCount++;
						}
	
						while (count($stack) > 0)
						{
							if ($round % 2 == 0)
							{
								echo array_shift($stack); 
							}
							else
							{
								echo array_pop($stack);
							}
						}
													
						echo "</tr><tr>";

						echo "</tr>";
					}
				?>
			</table>
		</form>
	</body>	
</html>

<?php
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
?>
