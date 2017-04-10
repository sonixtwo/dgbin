<?php
	include "../../shared/settings.php";
	include "../../shared/functions.php";
	include "../../shared/customfields-functions.php";
	
	session_start()
		or die("Could not start session.");
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	// Update the list table.
	$listIndex = $_REQUEST["listIndex"];
	$listItemTypeRef = $_REQUEST["typeIndex"];
	$listItemNumber = $_REQUEST["listItemNumber"];
	$listItemTitle = $_REQUEST["listItemTitle"];
	$listDataComplete = $_REQUEST["listDataComplete"];
	
	mysql_query("UPDATE list SET list_item_type_ref = " . $listItemTypeRef . ", list_data_complete = '" . $listDataComplete . "', list_item_number = '" . mysql_real_escape_string($listItemNumber, $link) . "', list_item_title = '" . mysql_real_escape_string($listItemTitle, $link) . "' WHERE list_index = " . $listIndex, $link)
		or die("Could not update list table.  " . mysql_error());

	// Update the ebay_data table.
	$ebayDataIndex = $_REQUEST["ebayDataIndex"];
	$ebayDataDescription = $_REQUEST["ebayDataDescription"];
	$ebayDataEndDate = $_REQUEST["ebayDataEndDate"];
	$ebayDataSoldPrice = $_REQUEST["ebayDataSoldPrice"];
	$ebayDataCondition = $_REQUEST["ebayDataCondition"];
	$serialNumber = $_REQUEST["serialNumber"];

	$customDataField1 = $_REQUEST["customDataField1"];
	$customField1 = getCustomFieldByValue($link, "1", $customDataField1);
	if (array_key_exists("custom_field_index", $customField1) == false)
	{
		$customDataField1 = addCustomField($link, "1", $customDataField1);
	}
	else
	{
		$customDataField1 = $customField1["custom_field_index"];
	}

	$customDataField2 = $_REQUEST["customDataField2"];
	$customField2 = getCustomFieldByValue($link, "2", $customDataField2);
	if (array_key_exists("custom_field_index", $customField2) == false)
	{
		$customDataField2 = addCustomField($link, "2", $customDataField2);
	}
	else
	{
		$customDataField2 = $customField2["custom_field_index"];
	}

	$customDataField3 = $_REQUEST["customDataField3"];
	$customField3 = getCustomFieldByValue($link, "3", $customDataField3);
	if (array_key_exists("custom_field_index", $customField3) == false)
	{
		$customDataField3 = addCustomField($link, "3", $customDataField3);
	}
	else
	{
		$customDataField3 = $customField3["custom_field_index"];
	}

	$customDataField4 = $_REQUEST["customDataField4"];
	$customField4 = getCustomFieldByValue($link, "4", $customDataField4);
	if (array_key_exists("custom_field_index", $customField4) == false)
	{
		$customDataField4 = addCustomField($link, "4", $customDataField4);
	}
	else
	{
		$customDataField4 = $customField4["custom_field_index"];
	}

	$customDataField5 = $_REQUEST["customDataField5"];
	$customField5 = getCustomFieldByValue($link, "5", $customDataField5);
	if (array_key_exists("custom_field_index", $customField5) == false)
	{
		$customDataField5 = addCustomField($link, "5", $customDataField5);
	}
	else
	{
		$customDataField5 = $customField5["custom_field_index"];
	}

	mysql_query("UPDATE ebay_data SET ebay_data_serial_number = '" . mysql_real_escape_string($serialNumber, $link) . "', ebay_data_field_1_ref = " . $customDataField1 . ", ebay_data_field_2_ref = " . $customDataField2 . ", ebay_data_field_3_ref = " . $customDataField3 . ", ebay_data_field_4_ref = " . $customDataField4 . ", ebay_data_field_5_ref = " . $customDataField5 . ", ebay_data_end_date = '" . mysql_real_escape_string($ebayDataEndDate, $link) . "', ebay_data_sold_price = '" . mysql_real_escape_string($ebayDataSoldPrice, $link) . "', ebay_data_condition = '" . mysql_real_escape_string($ebayDataCondition, $link) . "', ebay_data_description = '" . mysql_real_escape_string($ebayDataDescription, $link) . "' WHERE ebay_data_index = " . $ebayDataIndex, $link)
		or die("Could not update ebay_data table.  " . mysql_error());

	// Update the item_keyword table.
	$keywords = array();
	if (strlen(trim($_REQUEST["keyword1"])) > 0)
	{
		$keywords[] = trim($_REQUEST["keyword1"]);
	}
	
	if (strlen(trim($_REQUEST["keyword2"])) > 0)
	{
		$keywords[] = trim($_REQUEST["keyword2"]);
	}

	if (strlen(trim($_REQUEST["keyword3"])) > 0)
	{
		$keywords[] = trim($_REQUEST["keyword3"]);
	}

	if (strlen(trim($_REQUEST["keyword4"])) > 0)
	{
		$keywords[] = trim($_REQUEST["keyword4"]);
	}

	if (strlen(trim($_REQUEST["keyword5"])) > 0)
	{
		$keywords[] = trim($_REQUEST["keyword5"]);
	}
				
	deleteKeywords($link, $listIndex);
	addKeywords($link, $listIndex, $keywords);
	
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
	
	header("Location: select-list-index.php");
?>