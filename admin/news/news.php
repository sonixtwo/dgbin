<?php /**/ ?><?php
	include "../../shared/settings.php";
	include "../../shared/functions.php";

	session_start()
		or die("Could not start session.");
  $newsTitle= $_REQUEST["newsTitle"];
        $newsContent = $_REQUEST["newsContent"];



        $link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
            or die("Could not connect: " . mysql_error());
        mysql_select_db(DATABASE_NAME, $link)
                or die("Could not select database");

if (isset($newsTitle)) {mysql_query("INSERT INTO news (news_title, news_content) VALUES ('$newsTitle', '$newsContent')");
}
unset($newsTitle);
unset($newsContent);
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../../public.css" />
		<script>
			function onLoad()
			{
				document.getElementById("listIndex").focus();	
			}
		</script>
	</head>
	<body onload="javascript: onLoad();">
		<?php
			require("../menu.html");
		?>
<?php
echo $newsTitle;
echo "<br>";
echo $newsContent;
?>

		<form action="news.php" method="post">
			<table>
				<tr>
					<td>News Title</td>
					<td><input type="text" id="newsTitle" name="newsTitle" size="65"></td>
				</tr><tr>	
					<td>News Content</td>
					<td><input type="text" id="newsContent" name="newsContent" size="65"></td>			

	</tr>
			</table>
			
			<br>
			
			<input type="submit" value="Save">
		</form>
	</body>	
</html>
