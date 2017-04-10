<?php
	include "../shared/settings.php";

	session_start()
		or die("Could not start session.");

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
    </head>

<body>
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
<form action="../shared/contact.php" method="post">
<div class="form-group">

    <input class="form-control" placeholder="Your Name" type="text" name="cf_name">
    </div>
    <div class="form-group">
    

<input class="form-control" placeholder="Your Email Address" type="text" name="cf_email">
    </div>
    <div class="form-group">

    <textarea class="form-control" placeholder="Message" name="cf_message"></textarea>
    </div>
    <div class="form-group">
<input class="btn btn-primary btn-block" type="submit" value="Send">
	</div>
	<div class="form-group">
	<input class="btn btn-primary btn-block" type="reset" value="Clear">
	</div>
		<div class="form-group">
	<a class="btn btn-primary btn-block" href="/index.php">Cancel</a>
	</div>


</form>
</div>	
		
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
</body>

</html>
