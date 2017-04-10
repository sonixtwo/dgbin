<?php
	include "../shared/settings.php";
	include "../shared/functions.php";
	include "../shared/userdata-functions.php";
	include "../shared/email-functions.php";
	
	require("../PHPMailer_5.2.4/class.phpmailer.php");

	session_start()
		or die("Could not start session.");
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	// Update the list table.
	$email = $_REQUEST["email"];
	
	$matchingUser = getUserByEmail($link, $email);
	if (isset($matchingUser["user_data_index"]) == true)
	{
		$passwordResetToken = bin2hex(openssl_random_pseudo_bytes(32));
		
		mysql_query("UPDATE user_data SET user_data_password_reset_token = '" . mysql_real_escape_string($passwordResetToken, $link) . "', user_data_date_reset_requested = NOW() WHERE user_data_index = " . $matchingUser["user_data_index"], $link)
			or die("Could not update user_data table.  " . mysql_error());
		
		sendPasswordNotification($email, $matchingUser["user_data_first_name"], $matchingUser["user_data_last_name"], $passwordResetToken);
	}
	
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
	
	header("Location: ../index.php");
?>