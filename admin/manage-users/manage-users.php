<?php
	include "../../shared/settings.php";
	include "../../shared/userdata-functions.php";
	
	session_start()
		or die("Could not start session.");

	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	$users = getAllUsers($link);
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../public.css" />
	</head>

	<body>
		<?php
			require("../menu.html");
		?>

		<form method="post">
			<table border="1px" cellspacing="0">
				<tr>
					<td>&nbsp;</td>
					<td>Email</td>
					<td>First Name</td>
					<td>Last Name</td>
					<td>Date Created</td>
					<td>Last Login</td>
				</tr>

				<?php
					$rowNumber = 0;
					$stylesheetClass = "";
					
					foreach ($users as &$user)
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
	
						echo "<td class=\"" . $stylesheetClass . "\" style=\"vertical-align:  middle; text-align:  center;\"><a href=\"delete-user.php?userDataIndex=" . htmlspecialchars($user["user_data_index"]) . "\"><img src=\"../../private/images/Delete.png\" alt=\"Delete\" width=\"16\" height=\"16\"></a><a href=\"view-user.php?userDataIndex=" . htmlspecialchars($user["user_data_index"]) . "\"><img src=\"../../private/images/Modify.png\" width=\"16\"></a></td>";
						echo "<td class=\"" . $stylesheetClass . "\" style=\"width:  175px;\">" . $user["user_data_email"] . "</td>";
						echo "<td class=\"" . $stylesheetClass . "\" style=\"width:  175px;\">" . $user["user_data_first_name"] . "</td>";
						echo "<td class=\"" . $stylesheetClass . "\" style=\"width:  175px;\">" . $user["user_data_last_name"] . "</td>";
						echo "<td class=\"" . $stylesheetClass . "\" style=\"width:  175px;\">" . $user["user_data_date_created"] . "</td>";
						echo "<td class=\"" . $stylesheetClass . "\" style=\"width:  175px;\">" . $user["user_data_last_login"] . "</td>";
	
						echo "</tr>";
						
						$rowNumber++;
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