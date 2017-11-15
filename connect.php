<?php
	$connection = new mysqli("localhost", "root", "hello", "chattyDb");
	
	if($connection->connect_error)
		die($connection->connect_error);

	// Globals
	$MAX_USERS_IN_CHATROOM = 256;
	$SEPARATOR = "▲";
?>