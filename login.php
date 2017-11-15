<?php
	require_once "connect.php";
	session_start();

	$name = $_POST["username"];
	$pass = $_POST["password"];

	$query = $connection->prepare("SELECT id,username FROM users WHERE username=? AND password=?");
	$query->bind_param("ss", $name, $pass);
	$query->execute();
	$resultArr = $query->get_result();

	if($resultArr->num_rows == 0) // No results
	{
		$_SESSION["login_feedback"] = 0; // Failed
		header("location: index.php");
		exit;
	}
	else // Logged in
	{
		$row = $resultArr->fetch_assoc();
		$userId = $row["id"];
		$name = $row["username"];
		$_SESSION["logged_in"] = 1;
		$_SESSION["user_id"] = $userId;
		$_SESSION["user_name"] = $name;
		header("location: dashboard.php");
		exit;
	}
?>