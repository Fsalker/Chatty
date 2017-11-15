<?php
	require_once "validateSession.php";
	require_once "connect.php";

	$userId = $_SESSION["user_id"];
	$chatId = $_SESSION["chatroom_id"];
	$message = $_POST["message"];
	$date = date("H:i:s");

	$query = $connection->prepare("INSERT INTO messages(user_id, chatroom_id, text, date) VALUES (?, ?, ?, ?)");
	$query->bind_param("ssss", $userId, $chatId, $message, $date);
	$query->execute();
	echo $userId."|".$chatId."|".$message."|".$date;
	exit;
?>
