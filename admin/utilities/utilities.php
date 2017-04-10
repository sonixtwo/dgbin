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
			function checkForDuplicates()
			{
				document.getElementById("utilities").action = "display-duplicates.php";
				document.getElementById("utilities").submit();
			}
		</script>
	</head>

	<body>
		<?php
			require("../menu.html");
		?>

		<br>
		
		<form id="utilities" method="post">
			<input type="submit" id="duplicates" name="duplicates" value="Check for Duplicate Items" onclick="javascript:  checkForDuplicates();">
 		</form>
	</body>	
</html>
