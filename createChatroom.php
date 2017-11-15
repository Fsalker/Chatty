<?php
	require_once "ValidateSession.php";
	require_once "connect.php";

	$name = $_POST["chatroomName"];
	$pass = $_POST["password"];
	$userList = serialize([]);
	$userCount = 0;

	if(!$name)
	{
		$_SESSION["createChatroom_feedback"] = -1; // Invalid input!
		header("location: dashboard.php");
		exit;
	}

	if(!$pass) $pass = "";

	$query = $connection->prepare("SELECT id FROM chatrooms WHERE name=?");
	$query->bind_param("s", $name);
	$query->execute();
	$resultArr = $query->get_result();
	if($resultArr->num_rows > 0) // Chatroom Name already taken
	{
		$_SESSION["createChatroom_feedback"] = 0;
		header("location: dashboard.php");
		exit;
	}
	{
		$query = $connection->prepare("INSERT INTO chatrooms(name, password, userList, userCount) VALUES (?, ?, ?, ?)");
		$query->bind_param("sssi", $name, $pass, $userList, $userCount);
		$query->execute();
		$_SESSION["createChatroom_feedback"] = 1;
		header("location: dashboard.php");
		exit;
	}
?>