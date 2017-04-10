<?php
	include "../../shared/settings.php";
	include "../../shared/functions.php";
	
	session_start()
		or die("Could not start session.");

	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");
	
	$items = getItems($link);
	$columnHeaders = getColumnHeaders($link);
	
	$dropdownData = array();
	foreach ($columnHeaders as &$columnHeader)
	{
		$dropdownData[$columnHeader] = getColumnDropdownData($link, $columnHeader);
	}

	$_SESSION["items"] = $items;
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../public.css" />
		<script>
			function onClick(clickedButton)
			{
				if ( clickedButton.id == "delete" )
				{
					document.getElementById("grid").action = "delete-data.php";
				}
				else
				{
					document.getElementById("grid").action = "save-custom-data.php";
				}				

				document.getElementById("grid").submit();
			}
		</script>
	</head>

	<body>
		<?php
			require("../menu.html");
		?>
			
		<?php echo getListSummaryTable($link); ?>

		<form id="grid" method="post">
			<table style="width:  100%;" border="1">
				<tr>
					<td>&nbsp;</td><td>&nbsp;</td><td>Type</td><td>Item Title</td><td>Sold Price</td><td>End Date</td><td>Condition</td>
					<?php
						$columnIndex = 1;
						foreach ($columnHeaders as &$columnHeader)
						{
							echo "<td>" . eval("return CUSTOM_FIELD_HEADER_" . $columnIndex . ";") . "</td>";
							$columnIndex++;
						}
					?>
				</tr>
				
				<?php
					$numberOfRecords = 0;
					foreach ($items as &$line)
					{
						if ($numberOfRecords >= MAXIMUM_NUMBER_OF_RECORDS_TO_DISPLAY)
						{
							break;	
						}
						
						echo "<tr>";

						echo "<td><input id=\"checkbox-" . $line["list_index"] . "\" name=\"checkbox-" . $line["list_index"] . "\" type=\"checkbox\"></td>";
						
						echo "<td><a href=\"download-image.php?itemNumber=" . $line["list_item_number"] . "\"><img src=\"/shared/get-image.php?adminImage=1&typesName=" . $line["types_name"] . "&itemNumber=" . $line["list_item_number"] . "\" alt=\"" . $line["list_item_number"] . "\"></img></a></td>";

						echo "<td>" . $line["types_name"] . "</td>";

						echo "<td><a href=\"#\" onclick='javascript:  window.open(\"http://cgi.ebay.com/ws/eBayISAPI.dll?ViewItem&item=" . $line["list_item_number"] . "\");'>" . htmlspecialchars($line["list_item_title"]) . "</a></td>";
						echo "<td>" . $line["ebay_data_sold_price"] . "</td>";
						echo "<td>" . $line["ebay_data_end_date"] . "</td>";
						echo "<td><input type=\"text\" size=\"10\" id=\"" . $line["list_index"] . "-condition\" name=\"" . $line["list_index"] . "-condition\" value=\"" . htmlspecialchars($line["ebay_data_condition"]) . "\"></td>";
						

						foreach ($columnHeaders as &$columnHeader)
						{
							$dataForDropdown = $dropdownData[$columnHeader];

							echo "<td>";
														
							echo "<select id=\"" . $line["list_index"] . "-" . $columnHeader . "\" name=\"" . $line["list_index"] . "-" . $columnHeader . "\">";
							echo "<option value=\"\">-- Select One --</option>";
	
							foreach ($dataForDropdown as &$option)
							{
								echo "<option value=\"" . htmlspecialchars($option) . "\">" . htmlspecialchars($option) . "</option>";
							}
	
							echo "</select>";
							
							echo "</td>";
						}

						echo "</tr>";
						
						echo "<tr>";
						
						echo "<td colspan=\"12\"><input type=\"text\" size=\"96\" id=\"" . $line["list_index"] . "-serialNumber" . "\" name=\"" . $line["list_index"] . "-serialNumber" . "\"></td>";
						
						echo "</tr>";

						echo "<tr>";
						
						echo "<td colspan=\"12\"><textarea rows=\"6\" cols=\"96\" id=\"" . $line["list_index"] . "-description" . "\" name=\"" . $line["list_index"] . "-description" . "\">" . htmlspecialchars($line["ebay_data_description"]) . "</textarea></td>";
						
						echo "</tr>";
						
						echo "<tr>";

						echo "<td colspan=\"12\">" .
							"<input type=\"text\" size=\"32\" maxlength=\"64\" id=\"" . $line["list_index"] . "-keyword1" . "\" name=\"" . $line["list_index"] . "-keyword1" . "\">" .
							"&nbsp;<input type=\"text\" size=\"32\" maxlength=\"64\" id=\"" . $line["list_index"] . "-keyword2" . "\" name=\"" . $line["list_index"] . "-keyword2" . "\">" .
							"&nbsp;<input type=\"text\" size=\"32\" maxlength=\"64\" id=\"" . $line["list_index"] . "-keyword3" . "\" name=\"" . $line["list_index"] . "-keyword3" . "\">" .
							"&nbsp;<input type=\"text\" size=\"32\" maxlength=\"64\" id=\"" . $line["list_index"] . "-keyword4" . "\" name=\"" . $line["list_index"] . "-keyword4" . "\">" .
							"&nbsp;<input type=\"text\" size=\"32\" maxlength=\"64\" id=\"" . $line["list_index"] . "-keyword5" . "\" name=\"" . $line["list_index"] . "-keyword5" . "\"></td>";

						echo "</tr>";
						
						$numberOfRecords++;
					}
				?>
			</table>
			
			<br>
			
			<input type="submit" id="save" id="save" value="Save" onclick="javascript: onClick(this);">

			<!-- input type="submit" id="delete" name="delete" value="Delete" onclick="javascript: onClick(this);" -->
		</form>
	</body>	
</html>

<?php
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
?>