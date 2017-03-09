<?php
include 'php/config.php';
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
?>

<?php session_start(); ?>

<?php 

//fetch album names to put in dropdown menu form
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
	<title>Image Album</title>
</head>
<body>
	<div class="wrapper" id="collectionPage">
		<div class="navigation.php">
			<?php include ('php/navigation.php'); ?>
		</div>
		<div class="header">
			<h3>Search</h3>
		</div>
		<div class="search">
			<form action="search.php" method="post">
				Album<br><input class="inputsearch" type="text" name="searchAlbum" placeholder="Search Album" autofocus><br><br>
				Caption<br><input class="inputsearch" type="text" name="searchCaption" placeholder="Search Caption" autofocus><br>
				<input class="input" type="submit" name="search" value="Search">
			</form>
		</div>

		<div class="albums">
			<?php
			if (isset($_POST['search'])) {
				if ((!empty($_POST['searchAlbum'])) || (!empty($_POST['searchCaption']))) {
					$albumInput = htmlentities($_POST['searchAlbum']);
					$captionInput = htmlentities($_POST['searchCaption']);
				}
				else if ((empty($_POST['searchAlbum'])) && (empty($_POST['searchCaption']))) {
					$albumInput = "null";
					$captionInput = "null";
					print("<p>You have not searched anything</p>");

				}

				$albumMatch = '%'.$albumInput.'%';
				$albumOutput = $mysqli -> query("SELECT * FROM album WHERE name LIKE '$albumMatch'");
				$captionMatch = '%'.$captionInput.'%';
				$captionOutput = $mysqli -> query("SELECT * FROM collection INNER JOIN photoalbum 
													ON collection.photoID = photoalbum.photoID
													WHERE caption LIKE '$captionMatch'");

				if ((!empty($_POST['searchAlbum']))) {
					while($row=$albumOutput->fetch_assoc()) {
						$album = $row['albumID'];
						$title = $row['name'];
						echo "<div class='albumView'><a href='albumview.php?album=$album'>$title</a></div>".'<br>';
						
					}

					if ((!empty($_POST['searchCaption']))) {
						while($row=$captionOutput->fetch_assoc()) {
							$image = $row['source'];
							$caption = $row['caption'];
							$id = $row['photoID'];
							$albumID = $row['albumID'];
							echo "<div class='imagesearch'><img src='$image'><br>";
							$album = $mysqli -> query("SELECT * FROM album WHERE albumID = $albumID");	
							while ($row=$album->fetch_assoc()) {
								$albumName = $row['name'];
								echo $caption.'<br>';
								echo "Album: ".$albumName.'</div>';
							}		
						}
					}

					//shows images in albums
					if (isset($_GET['album'])) {
						$getter = $_GET['album'];
						$result=$mysqli -> query("SELECT * FROM collection INNER JOIN photoalbum 
							ON collection.photoID = photoalbum.photoID WHERE photoalbum.albumID=$getter");
						while($row=$result->fetch_assoc()) {
							$image = $row['source'];
							$caption = $row['caption'];
							echo "<img src='$image' id='imageView'>";
						}
					}
				}
				if ((!empty($_POST['searchCaption']))) {
					while($row=$captionOutput->fetch_assoc()) {
						$image = $row['source'];
						$caption = $row['caption'];
						$id = $row['photoID'];
						$albumID = $row['albumID'];
						echo "<div class='imagesearch'><img src='$image'><br>";
						$album = $mysqli -> query("SELECT * FROM album WHERE albumID = $albumID");	
						while ($row=$album->fetch_assoc()) {
							$albumName = $row['name'];
							echo $caption.'<br>';
							echo "Album: ".$albumName.'</div>';
						}	
					}
					if ((!empty($_POST['searchAlbum']))) {
						while($row=$albumOutput->fetch_assoc()) {
							$album = $row['albumID'];
							$title = $row['name'];
							echo "<div class='albumView'><a href='albumview.php?album=$album'>$title</a></div>".'<br>';
						}

						//shows images in albums
						if (isset($_GET['album'])) {
							$getter = $_GET['album'];
							$result=$mysqli -> query("SELECT * FROM collection INNER JOIN photoalbum 
								ON collection.photoID = photoalbum.photoID WHERE photoalbum.albumID=$getter");
							while($row=$result->fetch_assoc()) {
								$image = $row['source'];
								$caption = $row['caption'];
								echo "<img src='$image' id='imageView'>";
							}
						}
					}
				}
			}
			?>
		</div>

	</div>
</body>
</html>
