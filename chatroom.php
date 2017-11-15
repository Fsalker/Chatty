<?php
	require_once "validateSession.php";

	if(!isset($_SESSION["chatroom_id"])) // Let clients see the page only if they're currently in a chatroom
	{
		header("location: dashboard.php");
		exit;
	}
?>

<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<style>
		p
		{
			margin: 0;
		}
	</style>
	<title><?php echo $_SESSION['chatroom_name']?></title>
</head>
<body>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<div class="col-xs-1"></div>
	<div id="chatBox" class="col-xs-10" style="margin-top: 1%; border: 0px solid black; border-radius: 4px; box-sizing: border-box; user-select: none; cursor: default">
		<div class="row">
			<div id="leftColumn" class="col-xs-10">
				<div id="messagesDiv" class="row" style="background-color: #FFC; overflow-y: scroll; overflow-x: hidden; word-break: break-all;">
					Loading the chat...
				</div>
				<div class="row">
					<input id="inputBox" type="text" style="width: 100%">
				</div>
			</div>

			<div id="userListDiv" class="col-xs-2" style="height: 100%; background-color: #CCF;">test3</div>
		</div>

	</div>	
	<div class="col-xs-1"></div>
	<script>
		// Dynamically set the ChatBox's height
		function updateChatboxHeight()
		{
			var desiredHeight = window.innerHeight - parseInt($("#chatBox").css("margin-top"))*2 - parseInt($("#chatBox").css("border-top-width"))*2 - 1;
			$("#chatBox").height(desiredHeight); // Resize the ChatBox

			var desiredMessagesDivHeight = parseInt($("#chatBox").css("height")) - parseInt($("#inputBox").css("height")) - parseInt($("#chatBox").css("border-top-width"))*2 ;
			$("#messagesDiv").css("height", desiredMessagesDivHeight); // Resize the messageDiv
		}
		updateChatboxHeight();
		$(window).resize(function(){updateChatboxHeight();});


		// Update the chat!
		var CHAT_UPDATE_RATE = 100; // miliseconds
		var number_of_messages = 0; // The amount of messages in the chatroom
		function updateChat()
		{
			// Get the messages
			$.post("fetchChatroomMessages.php").done(function(data){
				if(data == "-1") // Forbidden - probably a bad session! Redirect the client to the Dashboard.
				{
					window.location.href = "dashboard.php";
					return;
				}
				//console.log(data);
				$("#messagesDiv").html(data); // Display all messages
				var new_number_of_messages = $("#messagesDiv").children().length;
				if(new_number_of_messages > number_of_messages)
					scrollToBottom();
				number_of_messages = new_number_of_messages;
			});

			// Get the user list
			$.post("fetchChatroomUserlist.php").done(function(data){
				$("#userListDiv").html(data);
			});
		}

		function sendMessage(message) // Sends message to the server
		{
			if(message != "")
				$.post("sendChatroomMessage.php", {message: message}).done(function(){
					//scrollToBottom();
				});
		}
		
		function scrollToBottom() // Scrolls the message list to the bottom (automatically)
		{
			$("#messagesDiv").scrollTop($("#messagesDiv")[0].scrollHeight); // Scroll down to bottom
		}

		$("#inputBox").keypress(function (event){
			if(event.which == 13) { // Enter
				sendMessage($("#inputBox")[0].value);
				$("#inputBox")[0].value = "";
			}
		});

		updateChat();
		setInterval(updateChat, CHAT_UPDATE_RATE);
		scrollToBottom();

		// Leave the chat when closing the Browser Tab
		function leaveChat()
		{
			console.log("Asd");
			$.ajax({url:"leaveChatroom.php", type:"POST", async: false}).done(function(a)
				{
					console.log(a);
					var x = <?php if(isset($_SESSION["has"])) echo "'".$_SESSION["has"]."'"; else echo "'None'";?>;
					console.log("Done! "+x);
					/// What's this? I forgot what 4_SESSION["has"] means...
				});
		}
		// $( window ).on("unload", function(){leaveChat();}); // Automatically disconnect the user from the chat when they leave the page
	</script>
</body>