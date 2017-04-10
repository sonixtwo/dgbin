<?php
	include "../../shared/settings.php";
	include "../../shared/functions.php";
	
	session_start()
		or die("Could not start session.");
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	$typeIndex = $_REQUEST["typeIndex"];
	$typeName = getTypesName($link, $typeIndex);

	$result = mysql_query("SELECT * FROM list WHERE list_item_type_ref = " . $typeIndex . " AND list_data_complete = 0", $link)
		or die("Query failed: " . mysql_error());
		
	$recordsProcessed = 0;
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) 
	{
		$listIndex = $line["list_index"];
		$itemNumber = $line["list_item_number"];

		$getSingleItemResponse = fopen("http://open.api.ebay.com/shopping?callname=GetSingleItem&responseencoding=XML&appid=" . APP_ID . "&siteid=0&version=705&ItemID=" . $itemNumber . "&IncludeSelector=Description,ItemSpecifics", "rb");
		$xmlResponse = stream_get_contents($getSingleItemResponse);
		fclose($getSingleItemResponse);

		$xmlDOM = new SimpleXMLElement($xmlResponse);
		$xmlDOM->registerXPathNamespace("ebay", "urn:ebay:apis:eBLBaseComponents");
		
		$xPathResult = $xmlDOM->xpath("/ebay:GetSingleItemResponse/ebay:Ack");

		list( , $ack) = each($xPathResult); 
		if ($ack == "Success")
		{
			$ebayDataResult = mysql_query("SELECT * FROM ebay_data WHERE ebay_data_item_number_ref = " . $listIndex, $link)
				or die("Query failed: " . mysql_error());
			
			if (mysql_num_rows($ebayDataResult) == 0)
			{
				list( , $soldPrice) = each($xmlDOM->xpath("/ebay:GetSingleItemResponse/ebay:Item/ebay:ConvertedCurrentPrice"));
				list( , $description) = each($xmlDOM->xpath("/ebay:GetSingleItemResponse/ebay:Item/ebay:Description")); 
				list( , $endDate) = each($xmlDOM->xpath("/ebay:GetSingleItemResponse/ebay:Item/ebay:EndTime")); 
				list( , $imageURL) = each($xmlDOM->xpath("/ebay:GetSingleItemResponse/ebay:Item/ebay:PictureURL")); 
				list( , $condition) = each($xmlDOM->xpath("/ebay:GetSingleItemResponse/ebay:Item/ebay:ConditionDisplayName")); 
				
				if (strlen($imageURL) > 0)
				{
					$imagePath = saveImage($typeName, $itemNumber, $imageURL);
				}
				
				if (strlen($description) > 0)
				{
					$description = strip_tags($description);
					$description = trim(str_replace("&nbsp;", " ", $description));
				}

				mysql_query("INSERT INTO ebay_data (ebay_data_item_number_ref, ebay_data_end_date, ebay_data_sold_price, ebay_data_image_path, ebay_data_description, ebay_data_condition) VALUES ('" . $listIndex . "', '" . $endDate . "', " . $soldPrice . ", '" . $imagePath . "', '" . str_replace("'", "''", $description) . "', '" . str_replace("'", "''", $condition) . "')", $link)
					or die("Could not insert record: " . mysql_error());
			}

			mysql_query("UPDATE list SET list_data_complete = 1 WHERE list_index = " . $listIndex, $link)
				or die("Could not update record: " . mysql_error());
		}
		
		$recordsProcessed++;
		
		if ( $recordsProcessed >= MAXIMUM_NUMBER_OF_RECORDS_TO_PROCESS)
		{
			break;	
		}
	}
	
	mysql_free_result($result)
		or die("Could not free results: " . mysql_error());

	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
	
	header("Location: select-type.php");
?>
