<?php
	include "../shared/settings.php";
	include "../shared/functions.php";
	
	session_start()
		or die("Could not start session.");

	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	$typesIndex = DEFAULT_TYPES_KEY;
	$sortColumn = "ebay_data.ebay_data_sold_price";

	$customDataField = array();
	$customDataField[1] = "";
	$customDataField[2] = "";
	$customDataField[3] = "";
	$customDataField[4] = "";
	$customDataField[5] = "";

	$selectedKeyword = array();
	$selectedKeyword[1] = "";
	$selectedKeyword[2] = "";
	$selectedKeyword[3] = "";

	if (isset($_REQUEST["restoreValuesFromSession"]) == false)
	{
		if (isset($_REQUEST["typeIndex"]) == true)
		{
			$typesIndex = $_REQUEST["typeIndex"];
		}
		
		if ((isset($_REQUEST["columnToSort"]) == true) && ($_REQUEST["columnToSort"] != "")) 
		{
			$sortColumn = $_REQUEST["columnToSort"];
		}
					
		// Retrieve the any selected custom fields	
		if (isset($_REQUEST["custom_data_field_1"]))
		{
			$customDataField[1] = $_REQUEST["custom_data_field_1"];
		}
		
		if (isset($_REQUEST["custom_data_field_2"]))
		{
			$customDataField[2] = $_REQUEST["custom_data_field_2"];
		}
		
		if (isset($_REQUEST["custom_data_field_3"]))
		{
			$customDataField[3] = $_REQUEST["custom_data_field_3"];
		}
		
		if (isset($_REQUEST["custom_data_field_4"]))
		{
			$customDataField[4] = $_REQUEST["custom_data_field_4"];
		}
		
		if (isset($_REQUEST["custom_data_field_5"]))
		{
			$customDataField[5] = $_REQUEST["custom_data_field_5"];
		}
	
		// Retrieve the any selected keywords	
		if (isset($_REQUEST["keyword_filter_1"]))
		{
			$selectedKeyword[1] = $_REQUEST["keyword_filter_1"];
		}
		
		if (isset($_REQUEST["keyword_filter_2"]))
		{
			$selectedKeyword[2] = $_REQUEST["keyword_filter_2"];
		}
		
		if (isset($_REQUEST["keyword_filter_3"]))
		{
			$selectedKeyword[3] = $_REQUEST["keyword_filter_3"];
		}
	
		// Save everything in the session so we can restore state after going to the view page.
		$_SESSION["typeIndex"] = $typesIndex;
		$_SESSION["columnToSort"] = $sortColumn;
		$_SESSION["custom_data_field_1"] = $customDataField[1];
		$_SESSION["custom_data_field_2"] = $customDataField[2];
		$_SESSION["custom_data_field_3"] = $customDataField[3];
		$_SESSION["custom_data_field_4"] = $customDataField[4];
		$_SESSION["custom_data_field_5"] = $customDataField[5];
		$_SESSION["keyword_filter_1"] = $selectedKeyword[1];
		$_SESSION["keyword_filter_2"] = $selectedKeyword[2];
		$_SESSION["keyword_filter_3"] = $selectedKeyword[3];
	}
	else
	{
		$typesIndex = $_SESSION["typeIndex"];
		$sortColumn = $_SESSION["columnToSort"];
		$customDataField[1] = $_SESSION["custom_data_field_1"];
		$customDataField[2] = $_SESSION["custom_data_field_2"];
		$customDataField[3] = $_SESSION["custom_data_field_3"];
		$customDataField[4] = $_SESSION["custom_data_field_4"];
		$customDataField[5] = $_SESSION["custom_data_field_5"];
		$selectedKeyword[1] = $_SESSION["keyword_filter_1"];
		$selectedKeyword[2] = $_SESSION["keyword_filter_2"];
		$selectedKeyword[3] = $_SESSION["keyword_filter_3"];
	}
	
	// Get the data for the custom data dropdowns	
	$columnHeaders = getColumnHeaders($link);
	
	$dropdownData = array();
	foreach ($columnHeaders as &$columnHeader)
	{
		$dropdownData[$columnHeader] = getColumnDropdownData($link, $columnHeader);
	}
	
	$thirtyDayAverage = getEbayDataHistory($link, $lastMonth, $typesIndex, $selectedKeyword[1], $selectedKeyword[2], $selectedKeyword[3], $customDataField[1], $customDataField[2], $customDataField[3], $customDataField[4], $customDataField[5]);
	$sixtyDayAverage = getEbayDataHistory($link, getLastMonth($lastMonth), $typesIndex, $selectedKeyword[1], $selectedKeyword[2], $selectedKeyword[3], $customDataField[1], $customDataField[2], $customDataField[3], $customDataField[4], $customDataField[5]);
	$ninetyDayAverage = getEbayDataHistory($link, getLastMonth($lastMonth-1), $typesIndex, $selectedKeyword[1], $selectedKeyword[2], $selectedKeyword[3], $customDataField[1], $customDataField[2], $customDataField[3], $customDataField[4], $customDataField[5]);
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../public.css" />

		<script>
			function keywordFilter1OnClick()
			{
				if (document.getElementById("keyword_filter_2") != null)
				{
					document.getElementById("keyword_filter_2").selectedIndex = -1;
				}
				
				if (document.getElementById("keyword_filter_3") != null)
				{
					document.getElementById("keyword_filter_3").selectedIndex = -1;
				}
				
				document.getElementById('searchHistoryForm').submit();
			}
			
			function keywordFilter2OnClick()
			{
				if (document.getElementById("keyword_filter_3") != null)
				{
					document.getElementById("keyword_filter_3").selectedIndex = -1;
				}
				
				document.getElementById('searchHistoryForm').submit();
			}

			function keywordFilter3OnClick()
			{
				document.getElementById('searchHistoryForm').submit();
			}

			function resetCustomFieldLists()
			{
				if ( document.getElementById("custom_data_field_1") != null )
				{
					document.getElementById("custom_data_field_1").selectedIndex = -1;
				}
				
				if ( document.getElementById("custom_data_field_2") != null )
				{
					document.getElementById("custom_data_field_2").selectedIndex = -1;
				}

				if ( document.getElementById("custom_data_field_3") != null )
				{
					document.getElementById("custom_data_field_3").selectedIndex = -1;
				}

				if ( document.getElementById("custom_data_field_4") != null )
				{
					document.getElementById("custom_data_field_4").selectedIndex = -1;
				}

				if ( document.getElementById("custom_data_field_5") != null )
				{
					document.getElementById("custom_data_field_5").selectedIndex = -1;
				}
			}

			function resetKeywordLists()
			{
				if ( document.getElementById("keyword_filter_1") != null )
				{
					document.getElementById("keyword_filter_1").selectedIndex = -1;
				}
				
				if ( document.getElementById("keyword_filter_2") != null )
				{
					document.getElementById("keyword_filter_2").selectedIndex = -1;
				}

				if ( document.getElementById("keyword_filter_3") != null )
				{
					document.getElementById("keyword_filter_3").selectedIndex = -1;
				}
			}
			
			function sortColumn(columnToSort)
			{
				document.getElementById("columnToSort").value = columnToSort;
					
				document.getElementById('searchHistoryForm').submit();
			}

			function noKeywordOnClick()
			{
				document.getElementById('searchHistoryForm').submit();
			}
		</script>
	</head>

	<body>
		<div id = "main">
	<div id="SiteNameText">
Disc Golf Collector Bin	
	</div>
	<div id = "Navigation">
		<a href="/index.php">Home</a> | <a href="/view-history/view-history.php">Switch to View History</a>
	
	</div>	
	
		<br>
			
		<form id="searchHistoryForm" method="post" action="search-history.php">
			<div id="customFieldsDiv">
				<table>
					<tr>
						<?php
							$columnIndex = 1;
							foreach ($columnHeaders as &$columnHeader)
							{
								echo "<td>" . eval("return CUSTOM_FIELD_HEADER_" . $columnIndex . ";") . "</td>";
								$columnIndex++;
							}
						?>
					</tr>
					
					<tr>
						<?php
							foreach ($columnHeaders as &$columnHeader)
							{
								$dataForDropdown = getCustomFieldData($link, $lastMonth, "custom_data_field_" . $columnHeader, $typesIndex, $customDataField[1], $customDataField[2], $customDataField[3], $customDataField[4], $customDataField[5]);
		
								echo "<td>";
								echo "<select class=\"customFieldDropdown\" id=\"custom_data_field_" . $columnHeader . "\" name=\"custom_data_field_" . $columnHeader . "\" size=\"8\" onchange=\"resetKeywordLists(); document.forms[0].submit();\">";
								echo "<option value=\"\">" . ALL_TEXT . "</option>";
	
								foreach ($dataForDropdown as &$option)
								{
									if ($option == $customDataField[$columnHeader])
									{
										echo "<option value=\"" . htmlspecialchars($option) . "\" selected>" . htmlspecialchars($option) . "</option>";
									}
									else
									{
										echo "<option value=\"" . htmlspecialchars($option) . "\">" . htmlspecialchars($option) . "</option>";
									}
								}
		
								echo "</select>";
								echo "</td>";
							}
						?>
					</tr>

					<tr>
						<?php
							echo "<td>";
	
							if ((strlen($customDataField[1]) > 0) || (strlen($customDataField[2]) > 0) || (strlen($customDataField[3]) > 0) || (strlen($customDataField[4]) > 0) || (strlen($customDataField[5]) > 0))
							{
								echo "<input type=\"submit\" value=\"Reset\" onclick=\"javascript:  resetCustomFieldLists();\">";
							}
							else
							{
								echo "&nbsp;";
							}
	
							echo "</td>";
						?>
					</tr>
				</table>
			</div>
						
			<br>
			
			<div id="keywordssDiv">
				<table>
					<tr>
						<?php
							echo "<td>";
							echo "<td><select class=\"keywordDropdown\" id=\"keyword_filter_1\" name=\"keyword_filter_1\" size=\"8\" onclick=\"javascript:  keywordFilter1OnClick();\">";

							$keywords1 = getMatchingKeywords($link, $lastMonth, $typesIndex, $customDataField[1], $customDataField[2], $customDataField[3], $customDataField[4], $customDataField[5]);
							foreach ($keywords1 as &$keyword)
							{
								if ($keyword == $selectedKeyword[1])
								{
									echo "<option value=\"" . htmlspecialchars($keyword) . "\" selected>" . htmlspecialchars($keyword) . "</option>";
								}
								else
								{
									echo "<option value=\"" . htmlspecialchars($keyword) . "\">" . htmlspecialchars($keyword) . "</option>";
								}

							}

							echo "</select></td>";
	
							echo "<td>";
							if (strlen($selectedKeyword[1]) > 0)
							{
								echo "<select class=\"keywordDropdown\" id=\"keyword_filter_2\" name=\"keyword_filter_2\" size=\"8\" onclick=\"javascript:  keywordFilter2OnClick();\">";
	
								$keywords2 = getMatchingKeywordsForKeyword($link, $lastMonth, $typesIndex, $selectedKeyword[1], "", $customDataField[1], $customDataField[2], $customDataField[3], $customDataField[4], $customDataField[5]);
								foreach ($keywords2 as &$keyword)
								{
									if ($keyword == $selectedKeyword[2])
									{
										echo "<option value=\"" . htmlspecialchars($keyword) . "\" selected>" . htmlspecialchars($keyword) . "</option>";
									}
									else
									{
										echo "<option value=\"" . htmlspecialchars($keyword) . "\">" . htmlspecialchars($keyword) . "</option>";
									}
	
								}
	
								echo "</select>";
							}
							else
							{
								echo "&nbsp;";
							}
							
							echo "</td>";
	
							echo "<td>";
							if (strlen($selectedKeyword[2]) > 0)
							{
								echo "<select class=\"keywordDropdown\" id=\"keyword_filter_3\" name=\"keyword_filter_3\" size=\"8\" onclick=\"javascript:  keywordFilter3OnClick();\">";
	
								$keywords3 = getMatchingKeywordsForKeyword($link, $lastMonth, $typesIndex, $selectedKeyword[1], $selectedKeyword[2], $customDataField[1], $customDataField[2], $customDataField[3], $customDataField[4], $customDataField[5]);
								foreach ($keywords3 as &$keyword)
								{
									if ($keyword == $selectedKeyword[3])
									{
										echo "<option value=\"" . htmlspecialchars($keyword) . "\" selected>" . htmlspecialchars($keyword) . "</option>";
									}
									else
									{
										echo "<option value=\"" . htmlspecialchars($keyword) . "\">" . htmlspecialchars($keyword) . "</option>";
									}
	
								}
	
								echo "</select>";
							}
							else
							{
								echo "&nbsp;";
							}
							
							echo "</td>";
						?>
					</tr>
	
					<tr>
						<?php
							echo "<td>";
							echo "<input id=\"resetForm\" name=\"resetForm\" type=\"submit\" value=\"Reset\" onclick=\"javascript:  resetKeywordLists();\">";
							echo "</td>";
						?>
					</tr>
				</table>
			</div>
						
			<br>
			
			<table>
				<tr>
					<td style="width:  230px;"><?php echo getMonth($lastMonth); ?>:&nbsp;&nbsp;<?php echo sprintf("$%01.2f", $thirtyDayAverage["average"]); ?></td>
					<td style="width:  230px;"><?php echo getMonth(getLastMonth($lastMonth)); ?>:&nbsp;&nbsp;<?php echo sprintf("$%01.2f", $sixtyDayAverage["average"]); ?></td>
					<td style="width:  230px;"><?php echo getMonth(getLastMonth($lastMonth-1)); ?>:&nbsp;&nbsp;<?php echo sprintf("$%01.2f", $ninetyDayAverage["average"]); ?></td>
				</tr>

				<tr>
					<td style="width:  230px;">Number of results found:&nbsp;&nbsp;<?php echo $thirtyDayAverage["number_of_records"]; ?></td>
					<td style="width:  230px;">Number of results found:&nbsp;&nbsp;<?php echo $sixtyDayAverage["number_of_records"]; ?></td>
					<td style="width:  230px;">Number of results found:&nbsp;&nbsp;<?php echo $ninetyDayAverage["number_of_records"]; ?></td>
				</tr>
			</table>
			
		<?php
			$items = getProcessedItemsForSearchHistory($link, $typesIndex, $sortColumn, $lastMonth, $selectedKeyword[1], $selectedKeyword[2], $selectedKeyword[3], $customDataField[1], $customDataField[2], $customDataField[3], $customDataField[4], $customDataField[5]);
			$columnHeaders = getColumnHeaders($link);
			
			$dropdownData = array();
			foreach ($columnHeaders as &$columnHeader)
			{
				$dropdownData[$columnHeader] = getColumnDropdownData($link, $columnHeader);
			}
		?>
			<table border="1" cellspacing="0">
				<tr>
					<td class="resultsHeader"><span class="resultsHeader">&nbsp;</span></td><td class="resultsHeader"><a class="resultsHeader" href="javascript:  sortColumn( 'ebay_data.ebay_data_sold_price' );">Sold Price</a></td><td class="resultsHeader"><a class="resultsHeader" href="javascript:  sortColumn( 'list.list_item_title' );">Item Title</a></td>
					<?php
						$columnIndex = 1;
						foreach ($columnHeaders as &$columnHeader)
						{
							echo "<td class=\"resultsHeader\"><a class=\"resultsHeader\" href=\"javascript:  sortColumn( 'cf" . $columnIndex . ".custom_field_cell' );\">" . eval("return CUSTOM_FIELD_HEADER_" . $columnIndex . ";") . "</a></td>";
							$columnIndex++;
						}
					?>
				</tr>
				
				<?php
					$rowNumber = 0;
					$stylesheetClass = "";
					
					foreach ($items as &$line)
					{
						if ($rowNumber % 2 == 0)
						{
							$stylesheetClass = "evenRow";
						}
						else
						{
							$stylesheetClass = "oddRow";
						}
							
						echo "<tr>";
	
						echo "<td class=\"" . $stylesheetClass . "\"><img src=\"/shared/get-image.php?publicImage=1&typesName=" . $line["types_name"] . "&itemNumber=" . $line["list_item_number"] . "\" alt=\"" . $line["list_item_number"] . "\"></img></td>";
	
						echo "<td class=\"" . $stylesheetClass . "\">" . $line["ebay_data_sold_price"] . "</td><td class=\"" . $stylesheetClass . "\"><a class=\"" . $stylesheetClass . "\" onclick=\"document.getElementById('listIndex').value = '" . $line["list_index"] . "'; document.getElementById('searchHistoryForm').action = '../shared/view-record.php'; document.getElementById('searchHistoryForm').submit();\" href=\"javascript:  void(0);\"><span>" . htmlspecialchars($line["list_item_title"]) . "</span></a></td>";
	
						foreach ($columnHeaders as &$columnHeader)
						{
							if (strlen($line["custom_data_field_$columnHeader"]) == 0)
							{
								echo "<td class=\"" . $stylesheetClass . "\">&nbsp;</td>";
							}
							else
							{							
								echo "<td class=\"" . $stylesheetClass . "\">" . htmlspecialchars($line["custom_data_field_$columnHeader"]) . "</td>";
							}
						}
	
						echo "</tr>";
						
						echo "<tr>";
		
						if (SHOW_KEYWORDS_ON_VIEW_HISTORY == true)
						{
							$keywords = getKeywords($link, $line["list_index"]);
							if (strlen($keywords) > 0)
							{
								echo "<td class=\"" . $stylesheetClass . "\" colspan=\"3\">" . htmlspecialchars(getKeywords($link, $line["list_index"])) . "</td>";
							}
							else
							{
								echo "<td class=\"" . $stylesheetClass . "\" colspan=\"3\">&nbsp;</td>";
							}
						}
						else
						{
							echo "<td class=\"" . $stylesheetClass . "\" colspan=\"3\">&nbsp;</td>";
						}
						
						echo "<td class=\"" . $stylesheetClass . "\" colspan=\"5\">" . htmlspecialchars($line["types_name"]) . "</td>";

						echo "</tr>";
						
						$rowNumber++;
					}
				?>
			</table>

			<input id="columnToSort" name="columnToSort" type="hidden" value="<?php echo htmlspecialchars($sortColumn); ?>">
			<input id="returnURL" name="returnURL" type="hidden" value="../search-history/search-history.php">
			<input id="listIndex" name="listIndex" type="hidden" value="">
		</form>
		</div>
	</body>	
</html>

<?php
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
?>