<?php 
//get the q parameter from URL
$reqget=$_GET['req'];
$reqpost=$_POST['req'];

echo "<p>This the response:</p>";

if (isset($_GET['req'])) {
	echo "<p>GET: ".strtoupper($reqget)."</p>";
} else {
	echo "<p>No get request...</p>";
}

if (isset($_POST['req'])) {
	echo "<p>POST: ".strtoupper($reqpost)."</p>";
} else {
	echo "<p>No post request...</p>";
}

?>