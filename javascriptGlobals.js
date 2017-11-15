var CHAT_UPDATE_RATE = 100; // miliseconds

function pingChatroomSession()
{
	$.post("pingChatroomConnection.php").done(function(data) {
		if(data == "-1") // Connection has suddenly been closed!
			location.reload();
		console.log(data);
	});
}

function startPingingChatroomSession()
{
	setInterval(function(){
		pingChatroomSession();
	}, CHAT_UPDATE_RATE)
}