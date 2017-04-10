<?php
	include "../../shared/settings.php";
	include "../../shared/functions.php";

	session_start()
		or die("Could not start session.");

	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");
?>

<html>
	<head>
		<script>
			function onLoad()
			{
				document.getElementById("type").focus();	
			}
		</script>
	</head>
	<body onload="javascript: onLoad();">
		<?php
			require("../menu.html");
		?>

		<form action="remove-duplicates.php" method="post">
			<br>
			
			<div>The following items appears under multiple types.&nbsp;&nbsp;Please select the type to remove.</div>
			
			<br>
			
			<table>
			<?php
				$duplicateItems = getDuplicateItems($link);
				
				foreach ($duplicateItems as &$item)
				{
					echo "<tr>";
					
					echo "<td><div>" . $item["list_item_number"] . "</div></td>";
	
					echo "<td>";
	
					$types = getTypesForItem($link, $item["list_item_number"]);
					foreach ($types as &$type)
					{
						echo "<input type=\"radio\">" . $types["types_name"];
					}
	
					echo "</td>";
	
					echo "</tr>";
				}
			?>
			</table>

			<input type="submit" value="Remove Duplicates">
		</form>
	</body>	
</html>

<?php
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
?>