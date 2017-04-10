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
		<link rel="stylesheet" type="text/css" href="../../public.css" />
		<script>
			function onLoad()
			{
				document.getElementById("typeIndex").focus();	
			}
		</script>
	</head>
	<body onload="javascript: onLoad();">
		<?php
			require("../menu.html");
		?>

		<?php echo getListSummaryTable($link); ?>

		<form action="save-ebay-data.php" method="post">
			<table>
				<tr>
					<td>Type</td>
					<td><?php echo getTypeDropdown($link, "", false) ?></td>
				</tr>
			</table>
			
			<br>
			
			<input type="submit" value="Save Ebay Data">
		</form>
	</body>	
</html>

<?php
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
?>