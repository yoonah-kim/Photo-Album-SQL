<?php
include 'php/config.php';
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
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
	$(document).ready(function() {
		$(".editForm").hide();
		$(".save").hide();

		$("button.edit").click(function(){
			$(".editForm").toggle(500);
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
		<?php
			if (isset($_GET['album'])) {
				$getter = $_GET['album'];
				$albumQ = $mysqli -> query ("SELECT name FROM album WHERE albumID = $getter");
				while($row=$albumQ -> fetch_assoc()) {
					$albumName = $row['name'];
					echo "<h3 class=\"albumViewHeader\">$albumName</h3>";
				}

				$result=$mysqli -> query("SELECT * FROM collection INNER JOIN photoalbum 
					ON collection.photoID = photoalbum.photoID WHERE photoalbum.albumID=$getter");

			if ($result && $result -> num_rows == 0) {
				print ("<p>There are no photos in this album</p>");
			}
			else {
				while($row=$result->fetch_assoc()) {
					$image = $row['source'];
					$caption = $row['caption'];
					$id=$row['photoID'];
					echo "<a href='photoview.php?getter=$id' class='imageView'><img src='$image' id='imageView'></a>";
				}
			}
				
			}
		?>
		
		
	</div>
</body>
</html>


