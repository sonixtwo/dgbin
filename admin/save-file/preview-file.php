<?php
	include "../../shared/settings.php";
	include "../../shared/functions.php";

	session_start()
		or die("Could not start session.");
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	$searchString = "itemId=";

	$contents = readDataFile();
	
	$parsedData = array();
	
	$startIndex = 0;
	while (($startIndex = strpos($contents, $searchString, $startIndex)) !== false):
		$startIndex = $startIndex + strlen($searchString);
		
		$tickIndex = strpos($contents, "&", $startIndex);
		$itemNumber = substr($contents, $startIndex, 12);
		
		$closingBracketIndex = strpos($contents, ">", $startIndex) + 1;
		$closingAnchorIndex = strpos($contents, "</a>", $startIndex);
		$description = substr($contents, $closingBracketIndex, $closingAnchorIndex-$closingBracketIndex);

		$startingTDIndex = strpos($contents, "<td", $closingAnchorIndex + 4) + 1;
		$closingBracketIndex = strpos($contents, ">", $startingTDIndex) + 1;
		$closingTDIndex = strpos($contents, "</td>", $closingBracketIndex);
		$sold = removeHTMLElements(trim(substr($contents, $closingBracketIndex, $closingTDIndex-$closingBracketIndex)));
		
		$startIndex = $closingAnchorIndex;
		
		if (strcasecmp($sold, "yes") == 0)
		{
			$parsedData[] = array("itemNumber" => $itemNumber, "description" => removeHTMLElements($description));
		}
	endwhile; 

	$_SESSION["parsedData"] = $parsedData;
?>

<html>
	<head>
		<script>
			function onClick(clickedButton)
			{
				if ( clickedButton.id == "cancel" )
				{
					document.getElementById("results").action = "select-file.php";
				}
				else
				{
					document.getElementById("results").action = "save-file.php";
				}
				
				document.getElementById("results").submit();
			} 
		</script>
	</head>

	<body>
		<?php
			require("../menu.html");
		?>

		<form id="results" method="post">
			<table>
				<tr>
					<td colspan="2">Type:&nbsp;&nbsp;<?php echo getTypesName($link, $_REQUEST["typeIndex"]) ?></td>
				</tr>

				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>

				<tr>
					<td>Item Number</td>
					<td>Description</td>
				</tr>
				
				<?php
					foreach ($parsedData as &$value) 
					{
						echo "<tr><td style=\"width:  150px;\">" . $value["itemNumber"] . "</td><td>" . $value["description"] . "</td></tr>"; 
					}
				?>
			</table>

			<input type="hidden" id="typeIndex" name="typeIndex" value="<?php echo $_REQUEST["typeIndex"] ?>">
						
			<input id="save" name="save" type="button" value="Save" onclick="javascript: onClick(this);">
			<input id="cancel" name="cancel" type="button" value="Cancel" onclick="javascript: onClick(this);">
		</form>
	</body>	
</html>
<?php
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
?>