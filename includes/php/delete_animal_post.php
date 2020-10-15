<!DOCTYPE html>

<html lang="he">
<head>
    <meta charset="utf-8">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<?php 
	session_start();
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
	$type = $_POST["type"];

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$delete_animal_post_query = "DELETE FROM animals WHERE id = $id";
		if (mysqli_query($connection, $delete_animal_post_query)){
		}
		
		if ($type == "my-reports"){
			$userId = $_SESSION["user-id"];
			$increase_saved_animals_query = "UPDATE users SET saved_animals=saved_animals+1 WHERE id=$userId";
			$connection->query($increase_saved_animals_query);
			$get_saved_animals_count_query = "SELECT * FROM users WHERE id=$userId";
			if ($get_saved_animals_result = $connection-> query($get_saved_animals_count_query)) {
				if ($get_saved_animals_result-> num_rows > 0) { 
					$user_row = $get_saved_animals_result->fetch_assoc();
					$savedAnimals = $user_row["saved_animals"];
					echo "$savedAnimals";
				}
			}
		}
	}
?>