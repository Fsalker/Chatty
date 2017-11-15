<?php
	require_once "validateSession.php";
	require_once "connect.php";

	$chatId = $_SESSION["chatroom_id"];

	$query = $connection->prepare("SELECT userList FROM chatrooms WHERE id=?");
	$query->bind_param("s", $_SESSION["chatroom_id"]);
	$query->execute();
	$resultArr = $query->get_result();
	while($row = $resultArr->fetch_assoc())
	{
		$userArr = unserialize($row["userList"]);
		if($userArr)
			foreach($userArr as $user)
			{
				echo $user."<br>";
			}
	}
?>
