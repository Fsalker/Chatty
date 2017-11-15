<?php
	require_once "validateSession.php";
	require_once "connect.php";

	if(!isset($_SESSION["chatroom_id"])) // Don't let clients leave the chatroom if they're not in one :)
	{
		header("location: dashboard.php");
	}

	$chatId = $_SESSION["chatroom_id"];
	unset($_SESSION["chatroom_id"]);
	unset($_SESSION["chatroom_name"]);

	// Empty the Client's active chatroom
	$query = $connection->prepare("UPDATE users SET activeChatroom_id = 0 WHERE id = ?");
	$query->bind_param("i", $_SESSION["user_id"]);
	$query->execute();

	// Update the chatroom's User Count
	$query = $connection->prepare("UPDATE chatrooms SET userCount = userCount - 1 WHERE id = ?");
	$query->bind_param("s", $chatId);
	$query->execute();

	// Fetch the userList
	$query = $connection->prepare("SELECT userList FROM chatrooms WHERE id = ?");
	$query->bind_param("i", $chatId);
	$query->execute();
	$results = $query->get_result();
	$userArr = unserialize($results->fetch_assoc()["userList"]);

	$key = array_search($_SESSION["user_name"], $userArr); // Pop the client from the userList
	unset($userArr[$key]);

	// Update the userList, with the current user popped from the array
	$userList = serialize($userArr);
	$query = $connection->prepare("UPDATE chatrooms SET userList=? WHERE id=?");
	$query->bind_param("si", $userList, $chatId);
	$query->execute();

	header("location: dashboard.php");
	exit;
?>