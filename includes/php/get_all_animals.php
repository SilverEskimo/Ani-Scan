<?php

$servername = "slavadb.cj2mmopjp4b9.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "Slava1990!";
$dbname = "slava_db_new";
$connection = new mysqli($servername, $username, $password, $dbname);
$connection->query("SET NAMES 'utf8'");


session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$animalsArr = [];
	$query = "SELECT * FROM animals WHERE isFound = 0";
	if ($get_animals_result = $connection->query($query)) {
		if ($get_animals_result-> num_rows > 0) {
			while ($row = $get_animals_result->fetch_assoc()) {
				$animalsArr[] = (object) [
						"lat" => $row["latitude"],
						"lng" => $row["longitude"]
					  ];
			}
		}
	}
	
	echo json_encode($animalsArr);
}

?>