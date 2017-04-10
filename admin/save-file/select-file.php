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
			
			function onSubmitValidate()
			{
				if ( document.getElementById("userfile").value == "" )
				{
				 	alert("Please select a file to preview.");
				 	document.getElementById("userfile").focus();
				 	
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

		<?php echo getListSummaryTable($link); ?>

		<form enctype="multipart/form-data" action="preview-file.php" method="post" onsubmit="javascript:  return onSubmitValidate();">
			<table>
				<tr>
					<td>Type</td>
					<td><?php echo getTypeDropdown($link, "", false) ?></td>
				</tr>
				<tr>
					<td>File</td>
					<td>
						<input type="file" id="userfile" name="userfile">
					</td>
				</td>
			</table>
			
			<br>
			
			<input type="submit" value="Preview">
		</form>
	</body>	
</html>

<?php
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
?>