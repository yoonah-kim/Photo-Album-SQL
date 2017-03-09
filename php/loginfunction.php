<?php
		$post_username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
		$post_password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
		if (empty($post_username) || empty($post_password)) {
		?>
			<div class="search" id="loginform">
				<form action="login.php" method="post">
					Username<br><input class = "inputsearch" type="text" name="username" placeholder="Username" required autofocus><br><br>
					Password<br><input class="inputsearch" type="password" name="password" placeholder="Password" required autofocus><br>
					<input class="input" type="submit" value="Login">
				</form>
			</div>
		<?php
		}
		else {
		include 'config.php';
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

			$hashed_password = hash("sha256", $post_password);

			$query = "SELECT * FROM users WHERE username='$post_username' AND hashpassword='$hashed_password'";

			$result = $mysqli->query($query);

			if ($result && $result->num_rows == 1) {
				$row = $result->fetch_assoc();
				$db_username = $row['username'];
				print("<h2><strong>Welcome, $db_username!</strong><br>You can now browse and edit photos</h2>");
				$_SESSION['logged_user_by_sql'] = $db_username;
			}
			else {
				echo '<p>' .$mysqli->error .'<p>';
				?>
				<p>Invalid login information<br>
				Please try again.</p>
				<div class="search" id="loginform">
				<form action="login.php" method="post">
					Username<br><input class = "inputsearch" type="text" name="username" placeholder="Username" required autofocus><br><br>
					Password<br><input class="inputsearch" type="password" name="password" placeholder="Password" required autofocus><br>
					<input class="input" type="submit" value="Login">
				</form>
			</div>
				<?php
			}
			$mysqli->close();
		}

		?>

		