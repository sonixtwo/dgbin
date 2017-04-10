<!DOCTYPE html>

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

	$events = getEvents($link);
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../public.css" />

		<script type="text/javascript">
			function isEventValid()
			{
				if ( document.getElementById( "eventName" ).value == "" )
				{
					alert( "Event Name is a required field." );
					document.getElementById( "eventName" ).focus();
			
					return false;
				}
			
				if ( document.getElementById( "eventURL" ).value == "" )
				{
					alert( "Event URL is a required field." );
					document.getElementById( "eventURL" ).focus();
			
					return false;
				}
			
				return true;
			}
			
			function deleteOnClick( eventKey )
			{
				if ( confirm( "Are you sure you want to delete this event?" ) == true )
				{
					location.href = "delete-event.php?eventKey=" + eventKey;
				}
			}

			function updateStatsOnClick( eventKey )
			{
				if ( confirm( "Are you sure you want to update the statistics for this event?" ) == true )
				{
					location.href = "update-statistics.php?eventKey=" + eventKey;
				}
			}

			function pickTeamsOnClick( eventKey, groupName, divisionName )
			{
				location.href = "pick-teams.php?eventKey=" + eventKey + "&groupName=" + encodeURIComponent(groupName) + "&divisionName=" + encodeURIComponent(divisionName);
			}

			function draftOrderOnClick( eventKey, groupName )
			{
				location.href = "draft-order.php?eventKey=" + eventKey + "&groupName=" + encodeURIComponent(groupName);
			}

			function runDraftOnClick( eventKey, groupName )
			{
				location.href = "run-draft.php?eventKey=" + eventKey + "&groupName=" + encodeURIComponent(groupName);
			}

			function scoringOnClick( eventKey, groupName )
			{
				location.href = "calculate-scores.php?eventKey=" + eventKey + "&groupName=" + encodeURIComponent(groupName);
			}
		</script>
	</head>

	<body>
		<?php
			require("../menu.html");
		?>

		<form id="eventsForm" method="post" action="add-event.php" onsubmit="return isEventValid();">
			<table>
				<tr>
					<td>&nbsp;</td>
				
					<td style="color:  white;"><?php echo EVENT_NAME; ?></td>
					<td style="color:  white;"><?php echo EVENT_URL; ?></td>
					<td>&nbsp;</td>
				</tr>
				
				<?php
					$rowNumber = 0;
					$stylesheetClass = "";
					
					foreach ($events as &$event)
					{
						if ($rowNumber % 2 == 0)
						{
							$stylesheetClass = "evenRow";
						}
						else
						{
							$stylesheetClass = "oddRow";
						}
							
						echo "<tr>";
	
						echo "<td style=\"vertical-align:  middle; text-align:  center;\"><a style=\"color:  black;\" href=\"javascript:  deleteOnClick( " . htmlspecialchars($event["event_key"]) . " );\"><img src=\"../../private/images/Delete.png\" alt=\"Delete\" width=\"16\" height=\"16\" title=\"Delete\"></a></td>";
						echo "<td class=\"" . $stylesheetClass . "\">" . htmlspecialchars($event["event_name"]) . "</td>";
						echo "<td class=\"" . $stylesheetClass . "\">" . htmlspecialchars($event["event_url"]) . "</td>";
						echo "<td class=\"" . $stylesheetClass . "\"><a style=\"color:  black;\" href=\"javascript:  updateStatsOnClick( " . htmlspecialchars($event["event_key"]) . " );\">" . UPDATE_STATISTICS . "</a>&nbsp;&nbsp;<a style=\"color:  black;\" href=\"javascript:  pickTeamsOnClick( " . htmlspecialchars($event["event_key"]) . ", '', '' );\">" . CREATE_TEAM . "</a></td>";
	
						echo "</tr>";
						
						$games = getGames($link, $event["event_key"]);
						if (count($games) > 0)
						{
							echo "<tr><td>&nbsp;</td>";
							echo "<td colspan=\"3\">";
	
							echo "<table>";					
							foreach ($games as &$game)
							{
								echo "<tr><td style=\"width:  75px;\">&nbsp;</td>";
								echo "<td style=\"color:  white;\"><a style=\"color:  white;\" href=\"javascript:  pickTeamsOnClick( " . htmlspecialchars($event["event_key"]) . ", '" . htmlspecialchars($game["fg_group_name"]) . "', '" . htmlspecialchars($game["fg_division"]) . "' );\">" . $game["fg_group_name"] . "</a></td>";
								echo "<td style=\"color:  white;\">" . $game["fg_division"] . "</td>";
								echo "<td>";

								if ( $game["fg_type"] == 1)
								{
									echo "&nbsp;&nbsp;<a style=\"color:  white;\" href=\"javascript:  draftOrderOnClick( " . htmlspecialchars($event["event_key"]) . ", '" . htmlspecialchars($game["fg_group_name"]) . "' );\">" . DRAFT_ORDER . "</a>";
									echo "&nbsp;&nbsp;<a style=\"color:  white;\" href=\"javascript:  runDraftOnClick( " . htmlspecialchars($event["event_key"]) . ", '" . htmlspecialchars($game["fg_group_name"]) . "' );\">" . RUN_DRAFT . "</a>";
								}

								echo "&nbsp;&nbsp;<a style=\"color:  white;\" href=\"javascript:  scoringOnClick( " . htmlspecialchars($event["event_key"]) . ", '" . htmlspecialchars($game["fg_group_name"]) . "' );\">" . SCORING . "</a>";
								echo "</td></tr>";
							}
							echo "</table>";						
							
							echo "</td></tr>";
						}
						
						$rowNumber++;
					}
				?>
			</table>
			
			<table>
				<tr>
					<td style="color:  white;">&nbsp;</td>
					<td style="color:  white;"><?php echo EVENT_NAME; ?></td>
					<td style="color:  white;"><?php echo EVENT_URL; ?></td>
				</tr>

				<tr>
					<td style="vertical-align:  middle; text-align:  center;"><input type="image" src="../../private/images/Save.png" width="16" height="16" alt="Save" title="Save"></td>
					<td><input id="eventName" name="eventName" type="text" maxlength="128" value=""></td>
					<td><input id="eventURL" name="eventURL" type="text" maxlength="256" value=""></td>
				</tr>
			</table>
		</form>
	</body>	
</html>

<?php
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
?>