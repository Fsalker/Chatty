<?php
	require_once "validateSession.php";
	require_once "connect.php";

	if(!isset($_SESSION["chatroom_id"])) // Return if the user's not currently in a chatroom
	{
		echo "-1";
		exit;
	}

	$chatId = $_SESSION["chatroom_id"];

	$query_userIdToName = $connection->prepare("SELECT username FROM users WHERE id=?");
	$query = $connection->prepare("SELECT user_id, text, date FROM messages WHERE chatroom_id=?");
	$query->bind_param("s", $_SESSION["chatroom_id"]);
	$query->execute();
	$resultArr = $query->get_result();
	while($row = $resultArr->fetch_assoc())
	{
		$query_userIdToName->bind_param("s", $row["user_id"]);
		$query_userIdToName->execute();
		$userName = $query_userIdToName->get_result()->fetch_assoc()["username"];
		$date = $row["date"];
		$text = $row["text"];
		echo "<div class='row'>";
		echo "<div class='col-xs-4 col-sm-2' style=' text-align: left;'>$date</div>";
		echo "<div class='col-xs-2' style='text-align: right;'>$userName:</div>";
		echo "<div class='col-xs-6 col-sm-8' style=''>&nbsp$text</div>";
		echo "</div>";
	}
	$x = $resultArr->fetch_assoc();
	//var_dump();//
?>
