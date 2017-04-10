<?php
	include "../../shared/settings.php";
	include "../../shared/functions.php";
	include "../../shared/keywords-functions.php";
	
	session_start()
		or die("Could not start session.");

	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");
		
	$listIndex = $_REQUEST["listIndex"];
	if (strlen($listIndex) == 0)
	{
		header("Location: select-list-index.php");
	}
	
	$item = getItem($link, $listIndex);
	if (count($item) == 0)
	{
		header("Location: select-list-index.php");
	}

	$keywords = getItemKeywords($link, $listIndex);
	for ($i=count($keywords); $i < 5; $i++)
	{
		$keywords[$i] = array();
		$keywords[$i]["keyword_name"] = "";
	}
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../public.css" />

		<script type="text/javascript">
			function onLoad()
			{
				document.getElementById("listItemNumber").focus();	
			}

			function navigateToNextPage(clickedButton)
			{
				if ( validateForm() == false )
				{
					return false;
				}

				if ( clickedButton.id == "update" )
				{
					document.getElementById( "viewForm" ).action = "update-record.php";	
				}
				else if ( clickedButton.id == "cancel" )
				{
					document.getElementById( "viewForm" ).action = "select-list-index.php";	
				}
				
				document.getElementById("viewForm").submit();	
			}
			
			function validateForm()
			{
				if ( document.getElementById( "ebayDataEndDate" ).value == "" )
				{
					document.getElementById( "ebayDataEndDate" ).focus();
					alert( "Please enter a value for the End Date field." );
					return false;
				}
				else if ( document.getElementById( "ebayDataSoldPrice" ).value == "" )
				{
					document.getElementById( "ebayDataSoldPrice" ).focus();
					alert( "Please enter a value for the Sold Price field." );
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

		<form id="viewForm" method="post" action="select-list-index.php" onsubmit="return validateForm();">
			<table>
				<tr>
					<td colspan="2"><hr></td>
				</tr>

				<tr>
					<td colspan="2"><img src="<?php echo htmlspecialchars(IMAGE_DIRECTORY . "/" . $item["types_name"] . "/" . $item["list_item_number"] . ".jpg" ) ?>" alt="<?php echo $item["list_item_number"] ?>"></img></td>
				</tr>
				
				<tr>
					<td colspan="2"><hr></td>
				</tr>
				
				<tr>
					<td class="fieldName"  style="width: 20%">Type</td>
					<td class="fieldValue"><?php echo getTypeDropdown($link, $item["types_index"], false) ?></td>
				</tr>

				<tr>
					<td class="fieldName" style="width: 20%">List Data Complete</td>
					<td class="fieldValue"><input type="text" id="listDataComplete" name="listDataComplete" size="64" value="<?php echo htmlspecialchars($item["list_data_complete"]) ?>"></td>
				</tr>

				<tr>
					<td class="fieldName" style="width: 20%">Item Number</td>
					<td class="fieldValue"><input type="text" id="listItemNumber" name="listItemNumber" size="64" value="<?php echo htmlspecialchars($item["list_item_number"]) ?>"></td>
				</tr>

				<tr>
					<td class="fieldName"  style="width: 20%">Title</td>
					<td class="fieldValue"><input type="text" id="listItemTitle" name="listItemTitle" size="64" value="<?php echo htmlspecialchars($item["list_item_title"]) ?>"></td>
				</tr>

				<tr>
					<td colspan="2"><hr></td>
				</tr>

				<tr>
					<td colspan="2"><br></td>
				</tr>

				<tr>
					<td class="fieldName"  style="width: 20%">Keywords</td>
					<td class="fieldValue"><input type="text" size="32" maxlength="64" id="keyword1" name="keyword1" value="<?php echo htmlspecialchars($keywords[0]["keyword_name"]) ?>"></td>
				</tr>

				<tr>
					<td class="fieldName"  style="width: 20%">&nbsp;</td>
					<td class="fieldValue"><input type="text" size="32" maxlength="64" id="keyword2" name="keyword2" value="<?php echo htmlspecialchars($keywords[1]["keyword_name"]) ?>"></td>
				</tr>

				<tr>
					<td class="fieldName"  style="width: 20%">&nbsp;</td>
					<td class="fieldValue"><input type="text" size="32" maxlength="64" id="keyword3" name="keyword3" value="<?php echo htmlspecialchars($keywords[2]["keyword_name"]) ?>"></td>
				</tr>

				<tr>
					<td class="fieldName"  style="width: 20%">&nbsp;</td>
					<td class="fieldValue"><input type="text" size="32" maxlength="64" id="keyword4" name="keyword4" value="<?php echo htmlspecialchars($keywords[3]["keyword_name"]) ?>"></td>
				</tr>

				<tr>
					<td class="fieldName"  style="width: 20%">&nbsp;</td>
					<td class="fieldValue"><input type="text" size="32" maxlength="64" id="keyword5" name="keyword5" value="<?php echo htmlspecialchars($keywords[4]["keyword_name"]) ?>"></td>
				</tr>

				<tr>
					<td colspan="2"><hr></td>
				</tr>

				<tr>
					<td colspan="2"><br></td>
				</tr>

				<tr>
					<td class="fieldName" style="width: 20%">EBay Description</td>
					<td class="fieldValue"><textarea id="ebayDataDescription" name="ebayDataDescription" rows="8" cols="64"><?php echo htmlspecialchars($item["ebay_data_description"]) ?></textarea></td>
				</tr>

				<tr>
					<td class="fieldName" style="width: 20%">End Date</td>
					<td class="fieldValue"><input type="text" id="ebayDataEndDate" name="ebayDataEndDate" size="24" value="<?php echo htmlspecialchars($item["ebay_data_end_date"]) ?>"></td>
				</tr>

				<tr>
					<td class="fieldName" style="width: 20%">Sold Price</td>
					<td class="fieldValue"><input type="text" id="ebayDataSoldPrice" name="ebayDataSoldPrice" size="24" value="<?php echo htmlspecialchars($item["ebay_data_sold_price"]) ?>"></td>
				</tr>

				<tr>
					<td class="fieldName" style="width: 20%">Image Path</td>
					<td class="fieldValue"><?php echo htmlspecialchars($item["ebay_data_image_path"]) ?></td>
				</tr>

				<tr>
					<td class="fieldName" style="width: 20%">Condition</td>
					<td class="fieldValue"><input type="text" id="ebayDataCondition" name="ebayDataCondition" size="64" value="<?php echo htmlspecialchars($item["ebay_data_condition"]) ?>"></td>
				</tr>

				<tr>
					<td colspan="2"><hr></td>
				</tr>
				
				<tr>
					<td colspan="2"><br></td>
				</tr>

				<tr>
					<td class="fieldName" style="width: 20%">Serial Number</td>
					<td class="fieldValue"><input type="text" id="serialNumber" name="serialNumber" size="64" value="<?php echo htmlspecialchars($item["serial_number"]) ?>"></td>
				</tr>

				<tr>
					<td class="fieldName" style="width: 20%">Custom Field 1</td>
					<td class="fieldValue"><input type="text" id="customDataField1" name="customDataField1" size="64" value="<?php echo htmlspecialchars($item["custom_data_field_1"]) ?>"></td>
				</tr>

				<tr>
					<td class="fieldName" style="width: 20%">Custom Field 2</td>
					<td class="fieldValue"><input type="text" id="customDataField2" name="customDataField2" size="64" value="<?php echo htmlspecialchars($item["custom_data_field_2"]) ?>"></td>
				</tr>

				<tr>
					<td class="fieldName" style="width: 20%">Custom Field 3</td>
					<td class="fieldValue"><input type="text" id="customDataField3" name="customDataField3" size="64" value="<?php echo htmlspecialchars($item["custom_data_field_3"]) ?>"></td>
				</tr>

				<tr>
					<td class="fieldName" style="width: 20%">Custom Field 4</td>
					<td class="fieldValue"><input type="text" id="customDataField4" name="customDataField4" size="64" value="<?php echo htmlspecialchars($item["custom_data_field_4"]) ?>"></td>
				</tr>

				<tr>
					<td class="fieldName" style="width: 20%">Custom Field 5</td>
					<td class="fieldValue"><input type="text" id="customDataField5" name="customDataField5" size="64" value="<?php echo htmlspecialchars($item["custom_data_field_5"]) ?>"></td>
				</tr>

				<tr>
					<td colspan="2"><hr></td>
				</tr>
			</table>
			
			<input type="hidden" id="listIndex" name="listIndex" value="<?php echo $_REQUEST["listIndex"]; ?>">
			<input type="hidden" id="ebayDataIndex" name="ebayDataIndex" value="<?php echo $item["ebay_data_index"]; ?>">
			<input type="hidden" id="customDataIndex" name="customDataIndex" value="<?php echo $item["custom_data_index"]; ?>">

			<table>
				<tr>
					<td><input id="update" name="update" type="button" value="Update" onclick="javascript:  navigateToNextPage( this );"></td>
					<td><input id="cancel" name="cancel" type="button" value="Cancel" onclick="javascript:  navigateToNextPage( this );"></td>
				</tr>
			</table>
		</form>
	</body>	
</html>

<?php
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
?>