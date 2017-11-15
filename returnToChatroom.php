<?php
	require_once "validateSession.php";
	require_once "connect.php";

	$query = $connection->prepare("SELECT activeChatroom_id FROM users WHERE id=?");
	$query->bind_param("i", $_SESSION["user_id"]);
	$query->execute();
	$resultArr = $query->get_result();
	$result = $resultArr->fetch_assoc();

	if($result["activeChatroom_id"] > 0)
	{
		$_SESSION["chatroom_id"] = $result["activeChatroom_id"]; 

		// Get the chatroom's name
		$query = $connection->prepare("SELECT name FROM chatrooms WHERE id=?");
		$query->bind_param("i", $result["activeChatroom_id"]);
		$query->execute();
		$resultArr = $query->get_result();
		$result = $resultArr->fetch_assoc();
		$_SESSION["chatroom_name"] = $result["name"];

		header("location: chatroom.php");
	}
	else die("Some error occurred!");

	exit;
?>