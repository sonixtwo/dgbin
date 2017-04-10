<?php
	include "../../shared/settings.php";
	include "../../shared/functions.php";
	
	session_start()
		or die("Could not start session.");
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	$itemNumber = $_REQUEST["itemNumber"];

	$result = mysql_query("SELECT types.types_name, list.* FROM list, types WHERE types.types_index = list.list_item_type_ref AND list_item_number = '" . $itemNumber . "'", $link)
		or die("Query failed: " . mysql_error());
		
	$recordsProcessed = 0;
	if ($line = mysql_fetch_array($result, MYSQL_ASSOC)) 
	{
		$listIndex = $line["list_index"];
		$itemNumber = $line["list_item_number"];
		$typeName = $line["types_name"];
		
		$getSingleItemResponse = fopen("http://open.api.ebay.com/shopping?callname=GetSingleItem&responseencoding=XML&appid=" . APP_ID . "&siteid=0&version=515&ItemID=" . $itemNumber, "rb");
		$xmlResponse = stream_get_contents($getSingleItemResponse);
		fclose($getSingleItemResponse);
		
		$xmlDOM = new SimpleXMLElement($xmlResponse);
		$xmlDOM->registerXPathNamespace("ebay", "urn:ebay:apis:eBLBaseComponents");
		
		$xPathResult = $xmlDOM->xpath("/ebay:GetSingleItemResponse/ebay:Ack");

		list( , $ack) = each($xPathResult); 
		if ($ack == "Success")
		{
			list( , $imageURL) = each($xmlDOM->xpath("/ebay:GetSingleItemResponse/ebay:Item/ebay:PictureURL")); 
			$imagePath = saveImage($typeName, $itemNumber, $imageURL);
		}
	}
	
	mysql_free_result($result)
		or die("Could not free results: " . mysql_error());

	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
	
	header("Location: enter-custom-data.php");
?>