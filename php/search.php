<?php
include 'config.php';
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
?>

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
	<link rel="stylesheet" type="text/css" href="fancy.css">
	
	<script>
	$(document).ready(function() {
		$(".editForm").hide();
		$(".save").hide();

		$("button.edit").click(function(){
			$(".editForm").toggle(500);
		});
	});
	</script>
	<title>Image Album</title>
</head>
<body>
	<div class="wrapper" id="collectionPage">
		<div class="navigation.php">
			<?php include ('navigation.php'); ?>
		</div>
		


	</div>
</body>
</html>
