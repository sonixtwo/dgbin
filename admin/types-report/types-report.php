<?php
	include "../../shared/settings.php";
	include "../../shared/functions.php";

	session_start()
		or die("Could not start session.");

	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");
		
	$columnHeaders = getColumnHeaders($link);
	$typesReportData = getTypesReportData($link, $lastMonth); 
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../public.css" />
	</head>
	<body>
		<?php
			require("../menu.html");
		?>
		
		<table border="1" cellspacing="0">
			<tr>
				<td>Type</td>
				<?php
					$columnIndex = 1;
					foreach ($columnHeaders as &$columnHeader)
					{
						echo "<td>" . eval("return CUSTOM_FIELD_HEADER_" . $columnIndex . ";") . "</td>";
						$columnIndex++;
					}
				?>
				<td>Keywords</td>
				<td class="resultsHeader"><?php echo getMonth(getLastMonth($lastMonth-1)); ?></td>
				<td class="resultsHeader">&nbsp;</td>
				<td class="resultsHeader"><?php echo getMonth(getLastMonth($lastMonth)); ?></td>
				<td class="resultsHeader">&nbsp;</td>
				<td class="resultsHeader"><?php echo getMonth($lastMonth); ?></td>
				<td class="resultsHeader">&nbsp;</td>
			</tr>
			
			<tr>
				<td colspan="<?php echo count($columnHeaders)+2 ?>">&nbsp;</td>
				<td class="resultsHeader"># Sold</td>
				<td class="resultsHeader">Avg Price</td>
				<td class="resultsHeader"># Sold</td>
				<td class="resultsHeader">Avg Price</td>
				<td class="resultsHeader"># Sold</td>
				<td class="resultsHeader">Avg Price</td>
			</tr>

			<?php
				$rowNumber = 0;
				$stylesheetClass = "";

				$previousTypesName = "";
				$previousCustomDataField1 = "";
				$previousCustomDataField2 = "";
				$previousCustomDataField3 = "";
				$previousCustomDataField4 = "";
				$previousCustomDataField5 = "";
				$previousKeywords = "";
				
				foreach ($typesReportData as &$dataRow)
				{
					$keywords = getKeywords($link, $dataRow["list_index"]);

					if (($previousTypesName == $dataRow["types_name"])
						&& ($previousCustomDataField1 == $dataRow["custom_data_field_1"])
						&& ($previousCustomDataField2 == $dataRow["custom_data_field_2"])
						&& ($previousCustomDataField3 == $dataRow["custom_data_field_3"])
						&& ($previousCustomDataField4 == $dataRow["custom_data_field_4"])
						&& ($previousCustomDataField5 == $dataRow["custom_data_field_5"])
						&& ($previousKeywords == $keywords))
					{
						continue;
					}
					
					$previousTypesName = $dataRow["types_name"];
					$previousCustomDataField1 = $dataRow["custom_data_field_1"];
					$previousCustomDataField2 = $dataRow["custom_data_field_2"];
					$previousCustomDataField3 = $dataRow["custom_data_field_3"];
					$previousCustomDataField4 = $dataRow["custom_data_field_4"];
					$previousCustomDataField5 = $dataRow["custom_data_field_5"];
					$previousKeywords = $keywords;
					
					if ($rowNumber % 2 == 0)
					{
						$stylesheetClass = "evenRow";
					}
					else
					{
						$stylesheetClass = "oddRow";
					}

					echo "<tr>";
					echo "<td class=\"" . $stylesheetClass . "\">" . $dataRow["types_name"] . "</td>";	

					$columnIndex = 1;
					foreach ($columnHeaders as &$columnHeader)
					{
						echo "<td class=\"" . $stylesheetClass . "\">" . $dataRow["custom_data_field_" . $columnIndex ] . "</td>";
						$columnIndex++;
					}

					$explodedKeywords = explode(",", $keywords);
					if (count($explodedKeywords) == 0)
					{
						$explodedKeywords[0] = "";
						$explodedKeywords[1] = "";
						$explodedKeywords[2] = "";
					}
					else if (count($explodedKeywords) == 1)
					{
						$explodedKeywords[1] = "";
						$explodedKeywords[2] = "";
					}
					else if (count($explodedKeywords) == 2)
					{
						$explodedKeywords[2] = "";
					}

					if (strlen($keywords) == 0)
					{
						$keywords = "&nbsp;";
					}
										
					echo "<td class=\"" . $stylesheetClass . "\">" . $keywords . "</td>";				

					$thirtyDayAverage = getEbayDataHistory($link, $lastMonth, $dataRow["types_index"], $explodedKeywords[0], $explodedKeywords[1], $explodedKeywords[2], $dataRow["custom_data_field_1"], $dataRow["custom_data_field_2"], $dataRow["custom_data_field_3"], $dataRow["custom_data_field_4"], $dataRow["custom_data_field_5"]);
					$sixtyDayAverage = getEbayDataHistory($link, getLastMonth($lastMonth), $dataRow["types_index"], $explodedKeywords[0], $explodedKeywords[1], $explodedKeywords[2], $dataRow["custom_data_field_1"], $dataRow["custom_data_field_2"], $dataRow["custom_data_field_3"], $dataRow["custom_data_field_4"], $dataRow["custom_data_field_5"]);
					$ninetyDayAverage = getEbayDataHistory($link, getLastMonth($lastMonth-1), $dataRow["types_index"], $explodedKeywords[0], $explodedKeywords[1], $explodedKeywords[2], $dataRow["custom_data_field_1"], $dataRow["custom_data_field_2"], $dataRow["custom_data_field_3"], $dataRow["custom_data_field_4"], $dataRow["custom_data_field_5"]);

					echo "<td class=\"" . $stylesheetClass . "\">" . $ninetyDayAverage["number_of_records"] . "</td>";				
					echo "<td class=\"" . $stylesheetClass . "\">" . sprintf("$%01.2f", $ninetyDayAverage["average"]) . "</td>";				
					echo "<td class=\"" . $stylesheetClass . "\">" . $sixtyDayAverage["number_of_records"] . "</td>";				
					echo "<td class=\"" . $stylesheetClass . "\">" . sprintf("$%01.2f", $sixtyDayAverage["average"]) . "</td>";				
					echo "<td class=\"" . $stylesheetClass . "\">" . $thirtyDayAverage["number_of_records"] . "</td>";				
					echo "<td class=\"" . $stylesheetClass . "\">" . sprintf("$%01.2f", $thirtyDayAverage["average"]) . "</td>";				
					echo "</tr>";

					$rowNumber++;
				}
			?>
	</body>
</html>

<?php
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
?>
