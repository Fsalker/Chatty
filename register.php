<?php
	require_once "connect.php";
	session_start();

	$name = $_POST["username"];
	$pass = $_POST["password"];

	$query = $connection->prepare("SELECT id FROM users WHERE username=?");
	$query->bind_param("s", $name);
	$query->execute();
	$resultArr = $query->get_result();

	if($resultArr->num_rows > 0) // Username already exists
	{
		$_SESSION["register_feedback"] = 0; // Failed
		header("location: index.php");
		exit;
	}
	else
	{
		$query = $connection->prepare("INSERT INTO users(username, password) VALUES(?, ?)");
		$query->bind_param("ss", $name, $pass);
		$query->execute();

		$_SESSION["register_feedback"] = 1; // Succesful
		header("location: index.php");
		exit;
	}
?>