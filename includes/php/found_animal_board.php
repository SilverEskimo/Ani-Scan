<!DOCTYPE html>

<?php

	session_start();

	$servername = "slavadb.cj2mmopjp4b9.us-east-1.rds.amazonaws.com";
	$username = "admin";
	$password = "Slava1990!";
	$dbname = "slava_db_new";
	$connection = new mysqli($servername, $username, $password, $dbname);
	$connection->query("SET NAMES 'utf8'");

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		session_start();
		$userId = $_SESSION["user-id"];
		
		if ($userId == null) {
			echo '<script>alert("עלייך להרשם/להתחבר על מנת להגיב");</script>';
		}
		else {

			$answer = trim($_POST["answer"]);
			$animalId= trim($_POST["animalId"]);
		
			if (empty($answer))
			{
				echo '<script>alert("הקלד תגובה ");</script>';
			}
			else {
				$servername = "slavadb.cj2mmopjp4b9.us-east-1.rds.amazonaws.com";
				$username = "admin";
				$password = "Slava1990!";
				$dbname = "slava_db_new";
				
				$connection = new mysqli($servername, $username, $password, $dbname);
				$connection->query("SET NAMES 'utf8'");

				if ($connection->connect_error)
				{
					die("Connection failed: " . $connection->connect_error);
				}

				$answer = addslashes($answer); 

				$sql= 'INSERT INTO answers(`user-id`, `animal-id`, `answer`) VALUES ("'.$userId.'", "'.$animalId.'", "'.$answer.'")';
				if (mysqli_query($connection, $sql)) {
					echo '<script>alert("התגובה שלך נשלחה בהצלחה! תודה!");</script>';
				}
				else
				{
					echo "שגיאה בעת שליחת תגובה" . mysqli_error($connection);
				}   
			}
		}
    }

?>


<html lang="he">
<head>
    <meta charset="utf-8">
    <title>Ani-Scan - Found Animals Board</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <script src="https://code.jquery.com/jquery-1.10.1.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/animal_board.css">
    <link rel="stylesheet" type="text/css" href="/css/common.css">
    <script src="/js/common.js"></script>
    <script src="/js/animal_board.js"></script>
    <meta property="og:url"                content="https://ani-scan.com" />
    <meta property="og:type"               content="article" />
    <meta property="og:title"              content="Please help!" />
    <meta property="og:description"        content="We just found a poor animal" />
    <meta property="og:image"              content="https://ani-scan.com/includes/php/upload_images/ani-scan-logo.PNG" />
</head>

<body>
	<div id="fb-root"></div>
	<script async defer crossorigin="anonymous" src="https://connect.facebook.net/he_IL/sdk.js#xfbml=1&version=v8.0" nonce="YfrdFSDt"></script>
	<div class="header">
		<h1>כל המציאות</h1>
		<div class="close-button"><button class="close-button-size" onclick="location.href = '/index.html';">&times;</button></div>
	</div>
	<br>
	<div>
		<button class="pretty-button" onclick="location.href = '/includes/php/find_animal_form.php';">הוספת מציאה</button>
	</div>
	<br>
	<div class="search-found-animals">
		<div class="search-input">
			<input id="searchInput" type="text" placeholder="חיפוש" dir="rtl" style="border-radius:13px;">
		</div>
		
		<div class="filter-animal-type" >
			<button class="btn btn-default selectAnimal" onclick="changeSelectedAnimalType('כלב')">כלב</button>
			<button class="btn btn-default selectAnimal" onclick="changeSelectedAnimalType('חתול')">חתול</button>		
		</div>
	</div>

	<div class="container">
        <div class="board-page-content">
			<?php
				if ($connection->connect_error)
				{
					die("Connection failed: " . $connection->connect_error);
				}
				
				if (!$connection->set_charset("utf8")) {
					printf("Error loading character set utf8: %s\n", $connection->error); exit();
				}
				
				$animals_query = "";
				if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["id"]) {
					$animal_id = $_GET["id"];

					$animals_query = "SELECT a.*, u.firstName as firstName, u.lastName as lastName, u.phone as phone FROM animals as a JOIN users as u on (a.created_user_id = u.id) WHERE a.id = '$animal_id'";
				} else {
					$animals_query = "SELECT a.*, u.firstName as firstName, u.lastName as lastName, u.phone as phone FROM animals as a JOIN users as u on (a.created_user_id = u.id) WHERE isFound = 1 ORDER BY date_created DESC";
				}

				
				$answers_query = "SELECT a.*, u.`firstName`, u.`lastName` FROM `answers` as a JOIN `users` as u on (a.`user-id` = u.id)  ORDER BY a.id ASC";

				$animals_result = $connection->query($animals_query);
				$answers_result = $connection->query($answers_query);
				
				$url = "https://";
				$url .= $_SERVER['HTTP_HOST'];
				$url .= $_SERVER['REQUEST_URI'];

				if ($animals_result->num_rows > 0) {
					while ($row = $animals_result->fetch_assoc()) {
						$user_details = ''.$row["firstName"].' '.$row["lastName"].',  טלפון '.$row["phone"].'';

						$animalType = $row["animalType"];
						$color = $row["color"];
						$writeSomething = empty($row["writeSomething"]) ? ללא : $row["writeSomething"];
						$date_created = $row["date_created"];
						$newDate = date("H:i d/m/Y ", strtotime($date_created));
						$animalId = $row["id"];
						$injured= $row["injured"];
						$address= $row["address"];
						$image = $row['image_base64'];
						
						echo '<div id="animals">
								<div id="'.$animalType.'" class="animal-post">
									<div style="display:flex; flex-direction:row-reverse;">
										<div>
											<span class="user-details">'.$user_details.'</span>
											<br>
											<span>'.$newDate.'</span>
											<br>
											<span> נמצא ב</span>
											<a class="user-address" href="https://www.google.com/maps/place/'.$address.'/"> '.$address.' </a>
											<br>
											<span>סוג: '.$animalType.'</span>
											<br>
											<span>צבע: '.$color.'</span>
											<br>
											<span dir="rtl">תיאור: '.$writeSomething.' </span>
											<br>
											<div class="fb-share-button" data-href="https://ani-scan.com/includes/php/found_animal_board.php?id='.$animalId.'" data-layout="button_count" data-size="small"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fani-scan.com%2Fincludes%2Fphp%2Ffound_animal_board.php%3Fid%3D&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a></div></div>
											<br>
										<div>
											<img id="image" src="'.$image.'" style="max-width:200px; max-height:150px;" />
										</div>
									</div>';

						echo '<div class="all-comments">';
							
						if ($answers_result->num_rows > 0) {
							while ($answers_result_row = $answers_result-> fetch_assoc()) {
								if ($answers_result_row["animal-id"] == $animalId) {
									$full_name_answer_writer = $answers_result_row["firstName"].' '.$answers_result_row["lastName"];
									$answer = $answers_result_row["answer"];
									echo '<div class="answer-content" dir="rtl">
											<b>'.$full_name_answer_writer.'</b>
											'.$answer.'
										</div>';
								}
							}
							$answers_result->data_seek(0);

						}
							
							
						echo '</div>
								<form action="found_animal_board.php" method="post">
									<textarea style="min-width: 310px" class="form-control form-group answer" name="answer"  placeholder="הקלד תגובה" dir="rtl"></textarea>
									<input type="hidden" value="'.$animalId.'" name="animalId" />
									<input type="submit" class="btn btn-primary" value="הגב" />
								</form>
							  </div></div>';
					}
				}	
			?>
		</div>
	</div>
	<div id="footer-nav"></div>
</body>
</html>



