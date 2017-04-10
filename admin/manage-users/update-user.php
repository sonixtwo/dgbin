<?php
	include "../../shared/settings.php";
	include "../../shared/functions.php";
	include "../../shared/userdata-functions.php";
		
	session_start()
		or die("Could not start session.");
	
	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");

	$userDataIndex = $_REQUEST["userDataIndex"];
	$firstName = $_REQUEST["firstName"];
	$lastName = $_REQUEST["lastName"];
	$email = $_REQUEST["email"];
	$password = $_REQUEST["password"];

	$matchingUser = getUserByEmail($link, $email);
	if ((isset($matchingUser["user_data_index"]) == false) || ($matchingUser["user_data_index"] == $userDataIndex))
	{
		if ($password == "")
		{
			mysql_query("UPDATE user_data SET user_data_first_name = '" . mysql_real_escape_string($firstName, $link) . "', user_data_last_name = '" . mysql_real_escape_string($lastName, $link) . "', user_data_email = '" . mysql_real_escape_string($email, $link) . "' WHERE user_data_index = " . $userDataIndex)
				or die("Could not update collections table.  " . mysql_error());
		}
		else
		{
			mysql_query("UPDATE user_data SET user_data_first_name = '" . mysql_real_escape_string($firstName, $link) . "', user_data_last_name = '" . mysql_real_escape_string($lastName, $link) . "', user_data_email = '" . mysql_real_escape_string($email, $link) . "', user_data_password = '" . mysql_real_escape_string(md5($password), $link) . "' WHERE user_data_index = " . $userDataIndex)
				or die("Could not update collections table.  " . mysql_error());
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
		header("Location: view-user.php?userDataIndex=" . $userDataIndex);
	}
	else
	{
		header("Location: manage-users.php");
	}
?>