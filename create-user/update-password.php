<?php
	include "../shared/settings.php";
	include "../shared/functions.php";
	include "../shared/userdata-functions.php";
	
	session_start()
		or die("Could not start session.");
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	// Update the list table.
	$email = $_REQUEST["email"];
	$passwordResetToken = $_REQUEST["passwordResetToken"];
	$password = md5($_REQUEST["password"]);
	
	if ((strlen($email) == 0) || (strlen($passwordResetToken) == 0) || (strlen($password) == 0))
	{
		mysql_close($link)
			or die("Could not close connection: " . mysql_error());
		
		header("Location: ../index.php");
		exit();
	}

	$matchingUser = getUserByEmailAndToken($link, $email, $passwordResetToken);
	if (isset($matchingUser["user_data_index"]) == false)
	{
		mysql_close($link)
			or die("Could not close connection: " . mysql_error());
		
		header("Location: ../index.php");
		exit();
	}
	
	mysql_query("UPDATE user_data SET user_data_password = '" . mysql_real_escape_string($password, $link) . "', user_data_password_reset_token = null, user_data_date_reset_requested = null WHERE user_data_index = " . $matchingUser["user_data_index"], $link)
		or die("Could not update list table.  " . mysql_error());
	
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
	
	header("Location: ../index.php");
?>