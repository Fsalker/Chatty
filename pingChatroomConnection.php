<?php
	require_once "validateSession.php";

	if(isset($_SESSION["chatroom_id"]))
		echo "1";
	else
		echo "-1";
?>