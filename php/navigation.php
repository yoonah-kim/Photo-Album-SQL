<ul id="navlist">

<li><a href="index.php">home</a></li>
<li><a href="albums.php">albums</a></li>
<li><a href="collection.php">collection</a></li>

<?php
if (isset($_SESSION['logged_user_by_sql'])) {
	echo "<li><a href=\"logout.php\">logout</a></li>";
}
else {
	echo "<li><a href=\"login.php\">login</a></li>";
}

?>

<li><a href="search.php">search</a></li>
</ul>