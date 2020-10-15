<!DOCTYPE html>

<html lang="he">
<head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<?php 
	$servername = "slavadb.cj2mmopjp4b9.us-east-1.rds.amazonaws.com";
	$username = "admin";
	$password = "Slava1990!";
	$dbname = "slava_db_new";
	$connection = new mysqli($servername, $username, $password, $dbname);
	$connection->query("SET NAMES 'utf8'");

	// Check connection
	if ($connection->connect_error) {
		echo "error: $connection->connect_error";
		die("Connection failed: " . $conn->connect_error);
	}

	$id = $_POST["id"];

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$delete_notification_query = "DELETE FROM notifications WHERE id = $id";
		if (mysqli_query($connection, $delete_notification_query)){
		}
	}
?>