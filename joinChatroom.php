<?php
	require_once "validateSession.php";
	require_once "connect.php";

	if(isset($_SESSION["chatroom_id"])) // Don't let the clients join a chatroom twice!
	{
		header("location: chatroom.php");
		exit;
	}

	$name = $_POST["chatroomName"];
	$pass = $_POST["password"];

	$query = $connection->prepare("SELECT id, name FROM chatrooms WHERE name=? AND password=?");
	$query->bind_param("ss", $name, $pass);
	$query->execute();
	$resultArr = $query->get_result();

	if($resultArr->num_rows == 0) // No results
	{
		$_SESSION["joinChatroom_feedback"] = 0;
		header("location: dashboard.php");
	}
	else if($resultArr->num_rows == 1) // Joined chatroom, 1 result
	{
		$result = $resultArr->fetch_assoc();

		// Update the Client's active chatroom
		$query = $connection->prepare("UPDATE users SET activeChatroom_id = ? WHERE id = ?");
		$query->bind_param("ii", $result["id"], $_SESSION["user_id"]);
		$query->execute();
		echo $result["id"]." ".$_SESSION["user_id"];

		// Increase the userCount, but only once!
		$query = $connection->prepare("UPDATE chatrooms SET userCount = userCount + 1 WHERE id = ?");
		$query->bind_param("i", $result["id"]);
		$query->execute();

		// Fetch the userList
		$query = $connection->prepare("SELECT userList FROM chatrooms WHERE id = ?");
		$query->bind_param("i", $result["id"]);
		$query->execute();
		$results = $query->get_result();
		$userArr = unserialize($results->fetch_assoc()["userList"]);
		if(!$userArr) $userArr = [];
		array_push($userArr, $_SESSION["user_name"]); // Push the client in the userList

		// Update the userList, with the current user pushed into the array
		$userList = serialize($userArr);
		$query = $connection->prepare("UPDATE chatrooms SET userList=? WHERE id=?");
		$query->bind_param("si", $userList, $result["id"]);
		$query->execute();


		$_SESSION["chatroom_id"] = $result["id"]; // One single active chatroom at a time :)
		$_SESSION["chatroom_name"] = $result["name"];

		// Update the Chatroom's amount of users

		header("location: chatroom.php");
	}
	else die("Multiple matching chatrooms! Oops!");
	exit;
?>