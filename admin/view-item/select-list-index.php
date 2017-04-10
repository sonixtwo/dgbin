<?php
	include "../../shared/settings.php";
	include "../../shared/functions.php";

	session_start()
		or die("Could not start session.");
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../public.css" />
		<script>
			function onLoad()
			{
				document.getElementById("listIndex").focus();	
			}
		</script>
	</head>
	<body onload="javascript: onLoad();">
		<?php
			require("../menu.html");
		?>

		<form action="view-record.php" method="post">
			<table>
				<tr>
					<td>List Index</td>
					<td><input type="text" id="listIndex" name="listIndex" size="36"></td>
				</tr>
			</table>
			
			<br>
			
			<input type="submit" value="View Record">
		</form>
	</body>	
</html>
