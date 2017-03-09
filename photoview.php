<?php
include 'php/config.php';
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
?>

<?php session_start(); ?>

<?php
$select = $mysqli  -> query('SELECT * FROM album');
$options = array();
while ($rows=$select->fetch_assoc()) {
	$name = $rows['name'];
	array_push($options, $name);
}
?>

<!DOCTYPE html>
<html>
<head>
	<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700%7cDancing+Script:400,700%7cPlayball%7cOpen+Sans:400,300,600,700%7cOpen+Sans+Condensed:700,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
	<script src='http://code.jquery.com/jquery-1.11.0.min.js'></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script>
	$(document).ready(function() {
		$(".editForm").hide();
		$("#save").hide();

		$("button.edit").click(function(){
			$(".editForm").toggle(500);
			$("#save").toggle(500);
		});
	});

	$(document).ready(function() {
		$(".imageView").click(function(){
			$("#notclickimage").hide(1000);
		});
	});
	</script>
	<title>Image Album</title>
</head>
<body>
	<div class="wrapper" id="collectionPage">
		<div class="navigation.php">
			<?php include ('php/navigation.php') ?>
		</div>
		<div class="button">

			<?php
				if (isset($_SESSION['logged_user_by_sql'])) {
					echo "<button class=\"edit\">Edit Photo</button>";
				}
				else {

				}
			?>
			
		</div>
		<div>
			<?php
			//saving deleted photos
			$ID = $_GET['getter'];
			if (isset($_POST['save'])) {
				if (isset($_POST['deletePhoto'])) {
					$delete = $_POST['deletePhoto'];
					foreach ($delete as $albumname) {
						$mysqli -> query("DELETE FROM collection WHERE photoID=$ID");
						print ("<p class=\"message\">your photo has successfully been deleted</p>");
					}
				}
				//updating caption
				$update = $_POST['updatecaption'];
				$mysqli -> query("UPDATE collection SET caption='$update' WHERE photoID='$ID'");
				$mysqli -> query ("UPDATE album SET dateModified=NOW()");
				
				$aTitle = $_POST['addToAlbum'];
				$albumquery = $mysqli -> query("SELECT * FROM album WHERE name = '$aTitle'");
				while ($row=$albumquery -> fetch_assoc()) {
					$aID = $row['albumID'];
				}

				//adding photo to album but cannot put photo in another album if its alreadsy in an album
				$existquery = $mysqli -> query("SELECT * FROM photoalbum INNER JOIN album ON 
											photoalbum.albumID = album.albumID WHERE photoID = '$ID'");
				while ($row=$existquery -> fetch_assoc()) {
					$imageID = $row['photoID'];
					$albumID = $row['albumID'];
				}
				if (empty($albumID)) {
					$relationquery = "INSERT INTO photoalbum (albumID, photoID) VALUES ($aID, $ID)";
					$mysqli -> query($relationquery);
					$mysqli -> query ("UPDATE album SET dateModified=NOW()");
					echo "<p class=\"message\">Your photo has successfully been added to the album, ".$aTitle."</p>";
				}
				else {
					if ($_POST['addToAlbum']!="none") {
					echo "<p class=\"message\">This photo already exists in another album<br>Upload again to add to album</p>";
					}
				}
				
			}

			$existquery = $mysqli -> query("SELECT * FROM photoalbum INNER JOIN album ON 
											photoalbum.albumID = album.albumID WHERE photoID = '$ID'");
			while ($row=$existquery -> fetch_assoc()) {
				$imageID = $row['photoID'];
				$albumID = $row['albumID'];
			}

			$pID = $_GET['getter'];
			$result = $mysqli -> query("SELECT * FROM collection WHERE photoID = $pID");
			echo "<form method=\"post\" action=\"photoview.php?getter=$pID\">";
			while ($row=$result-> fetch_assoc()) {
				$image = $row['source'];
				$caption = $row['caption'];
				$date = $row['uploadDate'];
				$id = $row['photoID'];
				echo "<img src='$image'><br>";

				// $mysqli->query("SELECT * FROM album INNER JOIN photoalbum ON album.albumID = photoalbum.albumID WHERE photoalbum.photoID=$id");
				// while ($row=$result-> fetch_assoc()) {
				// 	$albumname=$row['name'];
				// }
				// if (!empty($albumID)) {
				// 	echo "Album: ".$albumname."<br>";
				// }
				echo $caption."<br>";
				echo $date.'<br>';
				echo "<div class=\"editForm\">
				<input type=\"checkbox\" name=\"deletePhoto[]\" value=\"$id\">Delete<br>
					<input class=\"inputcaption\" type=\"text\" name=\"updatecaption\" id=\"updatecaption\" value=\"$caption\" placeholder=\"Photo Caption\" autofocus>
					<br><select class=\"option\" name=\"addToAlbum\">
				<option class=\"option\" value=\"none\">Add to Album</option>";
				foreach($options as $item) {
					print ('<option value = "'.$item.'">'.$item.'</option>');
				}
				echo "</select>";
				echo "<input class='input' type=\"submit\" id=\"save\" value=\"Save Changes\" name=\"save\"></div></form>";

			}
			
			?>
		</div>
	</div>
</body>
</html>