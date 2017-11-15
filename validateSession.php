<?php
	// Redirect home if not logged in
	session_start();
	
	if($_SERVER['PHP_SELF'] == "/chatty/index.php") // Redirect from index.php to Dashboard.php if the user is currently logged in
	{
		if(isset($_SESSION["logged_in"]))
			header("location: dashboard.php");
	}
	else // Redirect from any other site to index.php if the user is not currently logged in
	{
		if(!isset($_SESSION["logged_in"]))
		{
			//$_SESSION["sessionValidation_feedback"] = 0; // ACHTUNG! Intruder!
			header("location: index.php");
			exit;
		}
	}
?>