<?php
include 'php/config.php';
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (mysqli_connect_error()) {
	die("Can't connect to database: " . $mysqli->error);
}
?>

<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
	<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700%7cDancing+Script:400,700%7cPlayball%7cOpen+Sans:400,300,600,700%7cOpen+Sans+Condensed:700,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
	<script src='http://code.jquery.com/jquery-1.11.0.min.js'></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script>
	$(document).ready(function(){
		$(".createAlbum").hide();

		$("button.create").click(function(){
			$(".createAlbum").toggle(800);
		});
	});
	$(document).ready(function(){
		$(".uploadPhoto").hide();

		$("button.add").click(function(){
			$(".uploadPhoto").toggle(800);
		});
	});
	$(document).ready(function(){
		$(".editForm").hide();
		$(".save").hide();
		$("#save").hide();

		$("button.edit").click(function(){
			$(".editForm").toggle(500);
			$("#save").toggle(500);
		});
	});

	</script>
	<title>Image Album</title>
</head>
<body>
	
	<div class="wrapper" id="albumsPage">
		<div class="navigation.php">
			<?php include ('php/navigation.php'); ?>
		</div>
		<div class="header">
			<h3>Albums</h3>
		</div>

<?php 

//fetch album names to put in dropdown menu form
if (isset($_POST['createAlbum'])){
	$name =$_POST["name"];
	$sql = "INSERT INTO album (name, dateCreated, dateModified) VALUES ('$name', NOW(), NOW());";
	if($mysqli->query($sql)){
		$new_id = $mysqli->insert_id;
	}
}

if(isset($_POST['addPhoto'])){
	$caption = $_POST['caption'];
	if(!empty($_FILES['photo'])){
		//this part adds photo to ur photo/collection table

		$newPhoto = $_FILES['photo'];
		$url = $newPhoto['name'];
		$type = $newPhoto['type'];
		$location = "uploads/$url";

		}
		if($type != "image/jpg" && $type != "image/png" && $type != "image/jpeg" && $type != "image/gif" ) {
			echo 'Please upload a JPG, PNG, JPEG or GIF file';
		}
		else {
			if($newPhoto['error']==0){
				if (file_exists("uploads/$url")) {
					$n = 12301;
					$cut1 = substr($url, 0, -4);
					$cut2 = substr($url, -4);
					while(file_exists("uploads/$url")) {
						$n = $n + 1;
						$url = $cut1.$n.$cut2;
						$location = "uploads/".$url;

					}
				}

				$tempName = $newPhoto['tmp_name'];
				move_uploaded_file($tempName, $location);
				$albumID =0;
				//\"".$album_title."\""
				//here im checking which album you added to in the form above
				//then i get the ID for that album. 
				if ($_POST['addToAlbum']!="none"){
					$album_title = $_POST['addToAlbum'];
					$queryString = "SELECT albumID FROM album WHERE name='$album_title'";
					$albumquery = $mysqli -> query($queryString);
					$albumArr = $albumquery->fetch_row();
					$albumID = intval($albumArr[0]);
				}
					// echo $albumArr;
					// echo $albumID;

					$last_photo = 0;
					//here i put the last photo in the albumID i got right above
					//and insert it in the relation photo/album table

				$query = "INSERT INTO collection (source,caption,uploadDate) VALUES ('uploads/$url','$caption',NOW());";
				if($mysqli->query($query)){
					$last_photo = intval(mysqli_insert_id($mysqli));
					$relationquery = "INSERT INTO photoalbum (albumID, photoID) VALUES ($albumID, $last_photo);";
					$mysqli->query($relationquery);
					$mysqli -> query ("UPDATE album SET dateModified=NOW()");
				}	
				
				print("<p>Your photo has successfully been uploaded.<br></p>");
			}
			else{
				print("<p>There was an error uploading your photo. Please try again.<br></p>");
			}
		}		
		
	}


$select = $mysqli  -> query('SELECT * FROM album');
$options = array();
while ($rows=$select->fetch_assoc()) {
	$name = $rows['name'];
	array_push($options, $name);
}

?>
		
			<div class="button">
				<?php
				if (isset($_SESSION['logged_user_by_sql'])) {
					echo "<button class=\"create\">Create New Album</button>
				<button class=\"add\">Add New Photo</button> 
				<button class=\"edit\">Edit Albums</button>";
				}

				else {
					print("<p>Login to edit albums</p>");
				}
				?>
		
			</div>
		<div class="allAlbumForms">
			<div class="createAlbum">
				<form action="albums.php" method="post">
					<input class="inputAlbumName" type="text" name="name" placeholder="Album Name" required autofocus>
					<input class="input" type="submit" value="Create Album" name="createAlbum">
				</form>
			</div>
			<div class="uploadPhoto">
				<form action="albums.php" method="post" enctype="multipart/form-data">
					<input class="inputimage" type="file" name="photo"><br>
					<div class="allcapop"><div class="capop"><input class="inputcaption" type="text" name="caption" placeholder="Photo Caption" autofocus></div>
					<div class="capop"><select class="option" name="addToAlbum">
					<option class="option" value="none">Album Name</option>
					<?php foreach($options as $item) {
						print ('<option value = "'.$item.'">'.$item.'</option>');}
					?>
					</select></div></div><br>
					<input class="input" type="submit" name="addPhoto" value="Upload Photo">
				</form>
			</div>
		</div>
		<div class="albums">
			<?php
				

			//shows albums
			$result = $mysqli->query('SELECT * FROM album');
			echo "<form method=\"post\" action=\"albums.php\">";
			while($row=$result->fetch_assoc()) {
				$album = $row['albumID'];
				$title = $row['name'];
				echo "<a href='albumview.php?album=$album' class='albumView' >$title</a>".'<br>';
				echo "<div class=\"editForm\"><input type=\"checkbox\" name=\"deleteAlbum[]\" value=\"$album\">Delete Album</div>";
			}
			echo "<input class='input' type=\"submit\" id=\"save\" value=\"Save Changes\" name=\"save\">
			</form>";

			//shows images in albums
			

			//saving changes when deleting album
			if (isset($_POST['save'])) {
				if (isset($_POST['deleteAlbum'])) {
					$delete = $_POST['deleteAlbum'];
					foreach ($delete as $albumname) {
						$mysqli -> query("DELETE FROM album WHERE albumID=$albumname");
					}
				}
			}

			$mysqli->close();			
			?>
		</div>
	</div>
</body>
</html>
