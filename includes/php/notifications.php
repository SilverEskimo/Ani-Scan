
<?php
	session_start();

	$servername = "slavadb.cj2mmopjp4b9.us-east-1.rds.amazonaws.com";
	$username = "admin";
	$password = "Slava1990!";
	$dbname = "slava_db_new";
	$connection = new mysqli($servername, $username, $password, $dbname);
	$connection->query("SET NAMES 'utf8'");

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		echo '1';
	}

?>