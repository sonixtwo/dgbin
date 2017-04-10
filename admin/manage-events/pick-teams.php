<!DOCTYPE html>

<?php
	include "../../shared/settings.php";
	include "../../shared/event-functions.php";
	include "../../shared/userdata-functions.php";
	include "../../shared/fantasygames-functions.php";
	
	session_start()
		or die("Could not start session.");

	$eventKey = $_REQUEST["eventKey"];
	if (isset($_REQUEST["groupName"]) == TRUE)
	{
		$groupName = $_REQUEST["groupName"];
	}
	else
	{
		$groupName = "";
	}
	
	if (isset($_REQUEST["divisionName"]) == TRUE)
	{
		$divisionName = $_REQUEST["divisionName"];
	}
	else
	{
		$divisionName = "";
	}

	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	$fantasyUsers = getFantasyUsers($link);
	$divisions = getDivisions($link, $eventKey);
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../public.css" />

		<script type="text/javascript">
			function onLoad()
			{
				document.getElementById("groupName").focus();
			}
			
			function saveTeams()
			{
			    var selectedTeams = 0;
				for (var i=0; i < document.getElementsByName("fantasyUsers[]").length; i++)
				{
					if (document.getElementsByName("fantasyUsers[]")[i].checked == true)
					{
						selectedTeams++;
					}
				}

				if (document.getElementById("groupName").value.trim() == "")
				{
					alert("Group Name is a required field.");
					
					document.getElementById("groupName").focus();
					
					return false;
				}
				
				return true;
			}
		</script>
	</head>

	<body onload="javascript: onLoad();">
		<?php
			require("../menu.html");
		?>

		<form id="eventsForm" method="post" action="save-teams.php" onsubmit="return saveTeams();">
			<table>
				<?php
					$recordCount = 0;
					
					echo "<tr colspan=\"3\">";
					echo "<td style=\"color:  white; width:  200px;\">" . GROUP_NAME . "</td>";
					echo "</tr><tr colspan=\"3\">";
					echo "<td style=\"color:  white; width:  200px;\"><input type=\"text\" id=\"groupName\" name=\"groupName\" value=\"" . $groupName . "\"></td>";
					echo "</tr>";

					echo "<tr colspan=\"3\">";
					echo "<td style=\"color:  white; width:  200px;\">" . DIVISION . "</td>";
					echo "</tr><tr colspan=\"3\">";
					echo "<td style=\"color:  white; width:  200px;\"><select id=\"division\" name=\"division\">";
					foreach ($divisions as &$division)
					{
						if (strcmp($division["division_name"], $divisionName) == 0)
						{
							echo "<option value=\"" . htmlspecialchars($division["division_name"]) . "\" selected>" . htmlspecialchars($division["division_name"]) . "</option>";
						}
						else
						{
							echo "<option value=\"" . htmlspecialchars($division["division_name"]) . "\">" . htmlspecialchars($division["division_name"]) . "</option>";
						}
					}					
					echo "</select></td>";
					echo "</tr><tr>";

					foreach ($fantasyUsers as &$fantasyUser)
					{
						if (($recordCount != 0) && ($recordCount % 3 == 0))
						{
							echo "</tr><tr>";
						}

						echo "<td style=\"color:  white; width:  200px;\"><input name=\"fantasyUsers[]\" type=\"checkbox\" " . (doesFantasyGameExistForUser($link, $eventKey, $groupName, $fantasyUser["user_data_index"]) == TRUE ? " checked " : "") . " value=\"" . $fantasyUser["user_data_index"] . "\">" . htmlspecialchars($fantasyUser["user_data_user_name"]) . "</td>";
	
						$recordCount++;
					}

					echo "</tr>";
				?>
			</table>

			<input type="hidden" id="eventKey" name="eventKey" value="<?php echo $eventKey; ?>">
						
			<input type="submit" value="Save">
		</form>
	</body>	
</html>

<?php
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
?>
