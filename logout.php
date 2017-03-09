<?php
	//Need to start a session in order to access it to be able to end it
	session_start();
	if (isset($_SESSION['logged_user_by_sql'])) {
		$olduser = $_SESSION['logged_user_by_sql'];
		unset($_SESSION['logged_user_by_sql']);
	} else {
		$olduser = false;
	}
	session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
	<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700%7cDancing+Script:400,700%7cPlayball%7cOpen+Sans:400,300,600,700%7cOpen+Sans+Condensed:700,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
	<title>Image Album</title>
</head>
<body>
	<div class="wrapper" id="homepage">
		<div class="home_content">
			<h1>Create a Collection<br>of Memories</h1>
			<h3>then go out and capture some more</h3>
			<div class="all_links">
				<div class="link" id="albums">
					<a href="albums.php">albums</a>
				</div>
				<div class="link" id="collection">
					<a href="collection.php">collection</a>
				</div>
				<div class="link" id="login">
					<a href="login.php">login</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
