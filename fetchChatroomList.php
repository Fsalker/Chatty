<?php
	require_once "validateSession.php";
	require_once "connect.php";

	$query = $connection->prepare("SELECT name, password, userList, userCount FROM chatrooms ORDER BY userCount DESC");
	$query->execute();
	$results = $query->get_result();
	while($row = $results->fetch_assoc())
	{
		/*$userList = unserialize($row["userList"]);
		if(!$userList) $userList = []; // No users = empty userList array
		$user_count = count($userList);*/

		$userCount = $row["userCount"];
		echo "<div class='alert alert-success' style='text-align: center;'>";
		if($row["password"] != "") // Room isn't public
			echo "<span class='glyphicon glyphicon-lock' style='float: left'></span>";
		echo $row["name"] . " ($userCount / $MAX_USERS_IN_CHATROOM)";
		echo "</div>";
	}
?>