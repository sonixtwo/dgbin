<?php
	include "../shared/settings.php";
	include "../shared/functions.php";
	
	session_start()
		or die("Could not start session.");

	$link = mysql_connect(DATABASE_SERVER_NAME, DATABASE_USERNAME, DATABASE_PASSWORD)
	    or die("Could not connect: " . mysql_error());
	mysql_select_db(DATABASE_NAME, $link) 
		or die("Could not select database");
		
	$email = "";
	
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

				document.getElementById("email").focus();	
			}

			function validateForm()
			{
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
				
				return true;
			}

			function navigateToNextPage( clickedButton )
			{
				if ( clickedButton.id == "sendPassword" )
				{
					if ( validateForm() != true )
					{
						return false;
					}
					
					document.getElementById( "sendPasswordForm" ).action = "send-password.php";	
				}
				else if ( clickedButton.id == "cancel" )
				{
					document.getElementById( "sendPasswordForm" ).action = "../index.php";	
				}
				
				document.getElementById("sendPasswordForm").submit();	
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

	<form id="sendPasswordForm" method="post">
<div class="form-group">


				<input class="form-control" placeholder="Email Address" type="text" id="email" name="email" size="64" value="">
			</div><div class="form-group">
				<input class="btn btn-primary btn-block" id="sendPassword" name="sendPassword" type="button" value="Send Password" onclick="javascript:  navigateToNextPage( this );">
</div><div class="form-group">
				<input class="btn btn-primary btn-block" id="cancel" name="cancel" type="button" value="Cancel" onclick="javascript:  navigateToNextPage( this );">
</div>
			

		</form></div>	
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
