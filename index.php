<?php
	/* 
		Known issues:
	  -Chatroom messages are unselectable due to the chat's refresh rate -> Fix this by appending the new messages instead of posting all messages at once!
	  -Chatroom names span on multiple lines. Bootstrap tables aren't suitable for chatrooms :(. Fix this by using normal tables, with proper styles :)

	*/

	require_once "ValidateSession.php";
	require_once "connect.php";
?>

<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
	<div class="container" style="text-align: center; padding-top: 15px">
		<div class="jumbotron">
		<?php 
			// Register
			if(isset($_SESSION["register_feedback"])) {
				if($_SESSION["register_feedback"] == 0) // Failed - username already exists
					echo '<div class="alert alert-danger" role="alert">Username already exists!</div>';
				else if($_SESSION["register_feedback"] == 1) // Success
					echo '<div class="alert alert-success" role="alert">Registered successfully!</div>';
				unset($_SESSION["register_feedback"]);
			}

			// Login
			if(isset($_SESSION["login_feedback"])) {
				echo '<div class="alert alert-danger" role="alert">Wrong username or password!</div>';
				unset($_SESSION["login_feedback"]);
			}

			// Session Validation
			if(isset($_SESSION["sessionValidation_feedback"]))
			{
				echo '<div class="alert alert-warning" role="alert">You must be logged in in order to be able to see that page.</div>';
				unset($_SESSION["sessionValidation_feedback"]);
			}
		?>
		<h2>Please log in</h2>
		<form action="login.php" method="POST">
			<input type="text" placeholder="Username" name="username"><br>
			<input type="password" placeholder="Password" name="password"><br>
			<input style="margin-top: 4px" type="submit" value="Log in">
		</form>

		<br>

		<h2>Create Account</h2>
		<form action="register.php" method="POST">
			<input type="text" placeholder="Username" name="username"><br>
			<input type="password" placeholder="Password" name="password"><br>
			<input style="margin-top: 4px" type="submit" value="Register">
		</form>
		</div>
	</div>
</body>