<?php
	require_once "ValidateSession.php";
	require_once "connect.php";
?>

<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<style>
		h2
		{
			text-align: center;
		}
		input
		{
			width: 100%;
		}
	</style>
</head>
<body>
	<script src="javascriptGlobals.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<div class="container" style=" padding-top: 15px">
		<div class="jumbotron">	
			<form action="logout.php" style="text-align: right"><button type="submit">Log out</button></form>

			<?php 
				// createChatroom
				if(isset($_SESSION["createChatroom_feedback"])) {
					if($_SESSION["createChatroom_feedback"] == 1) // Success
						echo '<div class="alert alert-success" role="alert">Chatroom created successfully!</div>';
					else if($_SESSION["createChatroom_feedback"] == 0) // Failed - chatroom already exists
						echo '<div class="alert alert-danger" role="alert">Chatroom already exists!</div>';
					else if($_SESSION["createChatroom_feedback"] == -1) // Failed - chatroom already exists
						echo '<div class="alert alert-danger" role="alert">Invalid Chatroom Name or Password!</div>';
					unset($_SESSION["createChatroom_feedback"]);
				}

				// joinChatroom
				if(isset($_SESSION["joinChatroom_feedback"])) { // Unmatching credentials
					if($_SESSION["joinChatroom_feedback"] == 0)
						echo '<div class="alert alert-danger" role="alert">Wrong chatroom name or password!</div>'; 
					else if($_SESSION["joinChatroom_feedback"] == -1)
						echo '<div class="alert alert-warning" role="alert">Try rejoining the chatroom!</div>'; 
					unset($_SESSION["joinChatroom_feedback"]);
				}
			?>

			<div class="row">
				<div class="col-md-4">
					<?php
						$query = $connection->prepare("SELECT activeChatroom_id FROM users WHERE id=?");
						$query->bind_param("i", $_SESSION["user_id"]);
						$query->execute();
						$resultArr = $query->get_result();
						$row = $resultArr->fetch_assoc();

						if($row["activeChatroom_id"] == 0) // The user isn't currently in a Chatroom
						{
							echo '<h2>Join Chatroom</h2>
									<form action="joinChatroom.php" method="POST">
										<input id="joinChatroomNameInput" type="text" placeholder="Chatroom name" name="chatroomName" style="width: 100%;"><br>
										<input type="password" placeholder="Password" name="password" style="width: 100%;"><br>
										<input style="margin-top: 4px;" type="submit" value="Join">
									</form>';
						}
						else // The user is currently in a chatroom
						{
							echo '<h2 id = "chatroomReturnHeader">Chatroom in progress</h2>';
							echo '<form action="returnToChatroom.php" method="POST" style="margin-bottom: 0;">
										<input style="margin-top: 4px;" type="submit" value="Return to Chatroom">
								  </form>';
							echo '<form action="leaveChatroom.php" method="POST">
										<input style="margin-top: 4px;" type="submit" value="Leave Chatroom">
								  </form>';
						}
					?>

					<h2>Create Chatroom</h2>
					<form action="createChatroom.php" method="POST">
						<input type="text" placeholder="Chatroom name" name="chatroomName"><br>
						<input type="password" placeholder="Password" name="password"><br>
						<input style="margin-top: 4px;" type="submit" value="Create">
					</form>
				</div>
				<div class="col-md-8">
					<h2 style="text-align: center">Currently Active Chatrooms</h2>
					<div id="chatroomList">
						Loading Chatrooms...
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		// Fetch the chatrooms and display them nicely on the site!
		function fetchChatroomList()
		{
			$.post("fetchChatroomList.php").done(function(data){
				$("#chatroomList").html(data);
				$("#chatroomList").children().css("cursor", "pointer");
				$("#chatroomList").children().hover(function(){
					$(this).css("background-color", "0FF");
				}, function(){
					$(this).css("background-color", "rgb(223, 240, 216)");
				});
				$("#chatroomList").children().click(function(e){
					console.log(e.delegateTarget);
					var chatroomName = $(this).text();
					chatroomName = chatroomName.substr(0, chatroomName.indexOf("(") - 1);
					$("#joinChatroomNameInput").val(chatroomName);
				});
			});
		}
		fetchChatroomList();
		//setInterval(function(){fetchChatroomList();}, 100);
		
		if($("#chatroomReturnHeader").length > 0) // Currently showing the 'Return' button
			;
			//startPingingChatroomSession();
	</script>
</body>