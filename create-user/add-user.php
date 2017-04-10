<?php
	include "../shared/settings.php";
	include "../shared/functions.php";
	include "../shared/userdata-functions.php";
	include "../shared/email-functions.php";
	
	require("../PHPMailer_5.2.4/class.phpmailer.php");

	session_start()
		or die("Could not start session.");
	
	include "../securimage/securimage.php"; 
	$securimage = new Securimage();
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	// Update the list table.
	$userName = $_REQUEST["userName"];
	$firstName = $_REQUEST["firstName"];
	$lastName = $_REQUEST["lastName"];
	$email = $_REQUEST["email"];
	$password = md5($_REQUEST["password"]);
	
	$matchingUser = getUserByEmail($link, $email);
	if ((isset($matchingUser["user_data_index"]) == false) || ($matchingUser["user_data_index"] == $userDataIndex))
	{
		if ($securimage->check($_POST['captcha_code']) == false) 
		{ 
			$_SESSION["error_text"] = "The security code entered was incorrect.";
		}
		else
		{ 
			mysql_query("INSERT INTO user_data ( user_data_first_name, user_data_last_name, user_data_email, user_data_password, user_data_date_created, user_data_user_name ) VALUES ( '" . mysql_real_escape_string($firstName, $link) . "', '" . mysql_real_escape_string($lastName, $link) . "', '" . mysql_real_escape_string($email, $link) . "', '" . mysql_real_escape_string($password, $link) . "', CURDATE(), '" . mysql_real_escape_string($userName, $link) . "' )")
				or die("Could not update list table.  " . mysql_error());

			sendNewUserNotification($email, $firstName, $lastName);
		}
	}
	else
	{
		$_SESSION["error_text"] = "The  email address '" . $email . "' is already in use.  Please enter a different email address.";
	}
	
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
	
	if (isset($_SESSION["error_text"]) == true)
	{
		header("Location: create-user.php?firstName=" . urlencode($firstName) . "&lastName=" . urlencode($lastName) . "&email=" . urlencode($email) . "&userName=" . urlencode($userName) );
	}
	else
	{
		header("Location: ../index.php");
	}
?>