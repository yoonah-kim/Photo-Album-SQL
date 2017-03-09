<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700%7cDancing+Script:400,700%7cPlayball%7cOpen+Sans:400,300,600,700%7cOpen+Sans+Condensed:700,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
	<title>login</title>
</head>
<body>
	<div class="wrapper"  id="loginPage">
		<div class="navigation.php">
			<?php include ('php/navigation.php'); ?>
		</div>
		<div class="header">
			<h3>Login</h3>
		</div>
		

		<?php
		if (!isset($_SESSION['logged_user_by_sql'])) {
			include ('php/loginfunction.php');
		}
		else {
			echo "<div class='search'><p>You are already logged in</p><br>
			<button class=\"logout\"><a href=\"logout.php\">logout</a></button></div>";

		}
		?>
	</div>
</body>
</html>
