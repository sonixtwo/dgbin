<?php
	include "../shared/settings.php";
	include "../shared/functions.php";
	
	session_start()
		or die("Could not start session.");

	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");
		
	$userName = "";
	$firstName = "";
	$lastName = "";
	$email = "";
	
	if (isset($_REQUEST["userName"]) == true)
	{
		$userName = $_REQUEST["userName"];
	}

	if (isset($_REQUEST["firstName"]) == true)
	{
		$firstName = $_REQUEST["firstName"];
	}
	
	if (isset($_REQUEST["lastName"]) == true)
	{
		$lastName = $_REQUEST["lastName"];
	}

	if (isset($_REQUEST["email"]) == true)
	{
		$email = $_REQUEST["email"];
	}

	$errorText = "";
	if ((isset($_SESSION["error_text"]) == true) && (strlen($_SESSION["error_text"]) > 0))
	{
		$errorText = $_SESSION["error_text"];
		unset($_SESSION["error_text"]);
	}
?>

<html>
	<head>
	  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>dgbin</title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

		<script type="text/javascript">
			function onLoad()
			{
				var errorText = <?php echo json_encode($errorText); ?>;
				if ( errorText.length > 0 )
				{
					alert( errorText );
				}

				document.getElementById("userName").focus();	
			}

			function validateForm()
			{
				if ( document.getElementById( "userName" ).value == "" )
				{
					alert( "Please enter your user name." );

					document.getElementById( "userName" ).focus();

					return false;
				}

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
				
				if ( document.getElementById( "password" ).value == "" )
				{
					alert( "Please enter a password." );

					document.getElementById( "password" ).focus();

					return false;
				}

				if ( document.getElementById( "password" ).value != document.getElementById( "confirmPassword" ).value )
				{
					alert( "The values entered into the Password and Confirm Password fields do not match." );
					
					document.getElementById( "password" ).value = "";
					document.getElementById( "confirmPassword" ).value = "";
					
					document.getElementById( "password" ).focus();

					return false;
				}
				
				return true;
			}

			function navigateToNextPage( clickedButton )
			{
				if ( clickedButton.id == "addUser" )
				{
					if ( validateForm() != true )
					{
						return false;
					}
					
					document.getElementById( "createUserForm" ).action = "add-user.php";	
				}
				else if ( clickedButton.id == "cancel" )
				{
					document.getElementById( "createUserForm" ).action = "../index.php";	
				}
				
				document.getElementById("createUserForm").submit();	
			}
		</script>
	</head>

	<body onload="javascript: onLoad();">
  <nav class="navbar navbar-default">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="http://dgbin.com">dgbin</a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="/index.php">Home</a></li>
             <li>	<a href="/private/manage-game/manage-game.php">Fantasy</a></li>
<li>	<a href="/private/manage-collection/manage-collection.php">Manage Collection</a></li>
<li>	<a href="/private/search-collection/search-collection.php">Search Collection</a> </li>
<li>	<a href="/private/messaging/list_pm.php">My Messages</a> </li>
<li>	<a href="/private/manage-profile/view-profile.php">My Profile</a> </li>
<li>	<a href="/private/logout.php">Logout</a> </li>
             
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </nav>
<div class="container">


		<form id="createUserForm" method="post">
<div class="form-group">

					<input class="form-control" placeholder="Username" type="text" id="userName" name="userName" size="64" value="">
</div><div class="form-group">
						<input class="form-control" placeholder="First Name" type="text" id="firstName" name="firstName" size="64" value="">
</div><div class="form-group">

				<input class="form-control" placeholder="Last Name" type="text" id="lastName" name="lastName" size="64" value="">
</div><div class="form-group">
			
				<input class="form-control" placeholder="Email Address" type="text" id="email" name="email" size="64" value="">
</div><div class="form-group">
		
				<input class="form-control"  placeholder="Password" type="password" id="password" name="password" size="64" value="">
</div><div class="form-group">
		
				<input class="form-control" placeholder="Confirm Password" type="password" id="confirmPassword" name="confirmPassword" size="64" value="">
</div><div class="form-group">
				<img id="captcha" src="../securimage/securimage_show.php" alt="CAPTCHA Image"></img>
				<input type="text" class="form-control" placeholder="Captcha" name="captcha_code" size="10" maxlength="6">
						<a href="#" onclick="document.getElementById('captcha').src = '../securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a> 
			</div><div class="form-group">
			
				<input class="btn btn-primary btn-block" id="addUser" name="addUser" type="button" value="Create User" onclick="javascript:  navigateToNextPage( this );">
</div><div class="form-group">
			
				<input class="btn btn-primary btn-block" id="cancel" name="cancel" type="button" value="Cancel" onclick="javascript:  navigateToNextPage( this );">
			
</div>

		</form>
</div>	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
	</body>	
</html>

<?php
	mysql_close($link)
		or die("Could not close connection: " . mysql_error());
?>