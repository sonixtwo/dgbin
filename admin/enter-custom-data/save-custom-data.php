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

	$items = $_SESSION["items"];
	$columnHeaders = getColumnHeaders($link);	
	
	$numberOfRecords = 0;
	foreach ($items as &$line)
	{
		if ($numberOfRecords >= MAXIMUM_NUMBER_OF_RECORDS_TO_DISPLAY)
		{
			break;	
		}
		
		$listIndex = $line["list_index"];
				
		if (isset($_REQUEST["checkbox-$listIndex"]) == true)
		{
			mysql_query("UPDATE list SET list_data_complete = 3 WHERE list_index = " . $listIndex, $link)
				or die("Could not update record: " . mysql_error());
		}
		else
		{
			$custom_data_fields = array();
			for ($i=0; $i < count($columnHeaders); $i++)
			{
				$custom_data_fields[$i] = $_REQUEST["$listIndex-$columnHeaders[$i]"];
			}
			
			$description = $_REQUEST["$listIndex-description"];
			$serialNumber = $_REQUEST["$listIndex-serialNumber"];
			$condition = $_REQUEST["$listIndex-condition"];

			$keywords = array();
			if (strlen($_REQUEST["$listIndex-keyword1"]) > 0)
			{
				$keywords[] = $_REQUEST["$listIndex-keyword1"];
			}
			
			if (strlen($_REQUEST["$listIndex-keyword2"]) > 0)
			{
				$keywords[] = $_REQUEST["$listIndex-keyword2"];
			}

			if (strlen($_REQUEST["$listIndex-keyword3"]) > 0)
			{
				$keywords[] = $_REQUEST["$listIndex-keyword3"];
			}

			if (strlen($_REQUEST["$listIndex-keyword4"]) > 0)
			{
				$keywords[] = $_REQUEST["$listIndex-keyword4"];
			}

			if (strlen($_REQUEST["$listIndex-keyword5"]) > 0)
			{
				$keywords[] = $_REQUEST["$listIndex-keyword5"];
			}
			
			if ( ( $custom_data_fields[0] != "" ) ||
				( $custom_data_fields[1] != "" ) ||
				( $custom_data_fields[2] != "" ) ||
				( $custom_data_fields[3] != "" ) ||
				( $custom_data_fields[4] != "" ) )
			{
				$custom_data_fields[0] = getCustomFieldByValue($link, "1", $custom_data_fields[0]);
				if (array_key_exists("custom_field_index", $custom_data_fields[0]) == false)
				{
					$custom_data_fields[0] = "null";
				}
				else
				{
					$custom_data_fields[0] = $custom_data_fields[0]["custom_field_index"];
				}
				
				$custom_data_fields[1] = getCustomFieldByValue($link, "2", $custom_data_fields[1]);
				if (array_key_exists("custom_field_index", $custom_data_fields[1]) == false)
				{
					$custom_data_fields[1] = "null";
				}
				else
				{
					$custom_data_fields[1] = $custom_data_fields[1]["custom_field_index"];
				}

				$custom_data_fields[2] = getCustomFieldByValue($link, "3", $custom_data_fields[2]);
				if (array_key_exists("custom_field_index", $custom_data_fields[2]) == false)
				{
					$custom_data_fields[2] = "null";
				}
				else
				{
					$custom_data_fields[2] = $custom_data_fields[2]["custom_field_index"];
				}

				$custom_data_fields[3] = getCustomFieldByValue($link, "4", $custom_data_fields[3]);
				if (array_key_exists("custom_field_index", $custom_data_fields[3]) == false)
				{
					$custom_data_fields[3] = "null";
				}
				else
				{
					$custom_data_fields[3] = $custom_data_fields[3]["custom_field_index"];
				}

				$custom_data_fields[4] = getCustomFieldByValue($link, "5", $custom_data_fields[4]);
				if (array_key_exists("custom_field_index", $custom_data_fields[4]) == false)
				{
					$custom_data_fields[4] = "null";
				}
				else
				{
					$custom_data_fields[4] = $custom_data_fields[4]["custom_field_index"];
				}
				
				mysql_query("UPDATE ebay_data SET ebay_data_condition = '" . str_replace("'", "''", $condition) . "', ebay_data_description = '" . str_replace("'", "''", $description) . "', ebay_data_field_1_ref = " . $custom_data_fields[0] . ", ebay_data_field_2_ref = " . $custom_data_fields[1] . ", ebay_data_field_3_ref = " . $custom_data_fields[2] . ", ebay_data_field_4_ref = " . $custom_data_fields[3] . ", ebay_data_field_5_ref = " . $custom_data_fields[4] . ", ebay_data_serial_number = '" . mysql_real_escape_string($serialNumber) . "' WHERE ebay_data_item_number_ref = " . $listIndex, $link)
					or die("Could not update ebay_data record: " . mysql_error());

				if (count($keywords) > 0)
				{
					deleteKeywords($link, $listIndex);
					addKeywords($link, $listIndex, $keywords);
				}
				
				mysql_query("UPDATE list SET list_data_complete = 2 WHERE list_index = " . $listIndex, $link)
					or die("Could not update list record: " . mysql_error());
			}
		}
		
		$numberOfRecords++;
	}

	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
	
	header("Location: enter-custom-data.php");
?>