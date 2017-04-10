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

	$userData = getUser($link, $userDataIndex);

	$errorText = "";
	if ((isset($_SESSION["error_text"]) == true) && (strlen($_SESSION["error_text"]) > 0))
	{
		$errorText = $_SESSION["error_text"];
		unset($_SESSION["error_text"]);
	}
?>

<html>
	<head>
		<script type="text/javascript">
			function onLoad()
			{
				var errorText = <?php echo json_encode($errorText); ?>;
				if ( errorText.length > 0 )
				{
					alert( errorText );
				}

				document.getElementById("collectionName").focus();	
			}

			function validateForm()
			{
				if ( document.getElementById( "firstName" ).value == "" )
				{
					alert( "Please enter your first name." );

					document.getElementById( "firstName" ).focus();

					return false;
				}
				
				if ( document.getElementById( "lastName" ).value == "" )
				{
					alert( "Please enter your last name." );

					document.getElementById( "lastName" ).focus();

					return false;
				}

				if ( document.getElementById( "email" ).value == "" )
				{
					alert( "Please enter your email address." );

					document.getElementById( "email" ).focus();

					return false;
				}

				if ( /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.getElementById( "email" ).value) == false )
				{
					alert( "Please enter a valid email address." );

					document.getElementById( "email" ).focus();

					return false;
				}
				
				if ( document.getElementById( "password" ).value != "" )
				{
					if ( document.getElementById( "password" ).value != document.getElementById( "confirmPassword" ).value )
					{
						alert( "The values entered into the Password and Confirm Password fields do not match." );
						
						document.getElementById( "password" ).value = "";
						document.getElementById( "confirmPassword" ).value = "";
						
						document.getElementById( "password" ).focus();
	
						return false;
					}
				}
								
				return true;
			}
		</script>
	</head>

	<body onload="javascript: onLoad();">
		<?php
			require("../menu.html");
		?>

		<form id="viewProfileForm" method="post" action="update-user.php" onsubmit="return validateForm();">
			<table>
				<tr>
					<td class="SmallCaps"><?php echo USER_DATA_FIRST_NAME; ?></td>
					<td><input class="StandardTextBox" type="text" id="firstName" name="firstName" size="64" value="<?php echo htmlspecialchars($userData["user_data_first_name"]); ?>"></td>
				</tr>

				<tr>
					<td class="SmallCaps"><?php echo USER_DATA_LAST_NAME; ?></td>
					<td><input class="StandardTextBox" type="text" id="lastName" name="lastName" size="64" value="<?php echo htmlspecialchars($userData["user_data_last_name"]); ?>"></td>
				</tr>

				<tr>
					<td class="SmallCaps"><?php echo USER_DATA_EMAIL_ADDRESS; ?></td>
					<td><input class="StandardTextBox" type="text" id="email" name="email" size="64" value="<?php echo htmlspecialchars($userData["user_data_email"]); ?>"></td>
				</tr>

				<tr>
					<td class="SmallCaps"><?php echo USER_DATA_PASSWORD; ?></td>
					<td><input class="StandardTextBox" type="password" id="password" name="password" size="64" value=""></td>
				</tr>

				<tr>
					<td class="SmallCaps"><?php echo USER_DATA_CONFIRM_PASSWORD; ?></td>
					<td><input class="StandardTextBox" type="password" id="confirmPassword" name="confirmPassword" size="64" value=""></td>
				</tr>
			</table>
			
			<table>
				<tr>
					<td><input id="updateRecord" name="updateRecord" type="submit" value="Update"></td>
					<td><input id="cancel" name="cancel" type="button" value="Cancel" onclick="window.location.href = 'manage-users.php';"></td>
				</tr>
			</table>

			<input type="hidden" id="userDataIndex" name="userDataIndex" value="<?php echo htmlspecialchars($userDataIndex); ?>">		
		</form>
	</body>	
</html>

<?php
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
?>