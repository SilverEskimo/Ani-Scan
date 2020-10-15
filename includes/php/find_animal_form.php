<!DOCTYPE html>

<html lang="he">
<head>
    <meta charset="utf-8">
    <title>Ani-Scan Find an animal form</title>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script src="https://code.jquery.com/jquery-1.10.1.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
	<link rel="stylesheet" type="text/css" href="/css/common.css">
	<link rel="stylesheet" type="text/css" href="/css/forms.css">
	<script src="/js/common.js"></script>
	<script src="/js/location.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjZHrFdJwtrfZjG9NC-2_cY3pCJqzva3M" defer></script>
	
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>


<?php

function sendSmsMsg($phone, $msg) {
	$ch1 = curl_init('https://textbelt.com/text');
	$data = array(
		'phone'=> '+972' .$phone,
		'message' => ''.$msg.'',
		'key' => '482b4939c8a51512debd9a8d8ff824c364217a06pbKKFdmoy5K9iPSVmutfzK6Tf'
	);

	curl_setopt($ch1, CURLOPT_POST, 1);
	curl_setopt($ch1, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch1);
	curl_close($ch1);
}

$servername = "slavadb.cj2mmopjp4b9.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "Slava1990!";
$dbname = "slava_db_new";
$connection = new mysqli($servername, $username, $password, $dbname);
$connection->query("SET NAMES 'utf8'");


session_start();
$address = trim($_POST["address"]);
$color = trim($_POST["color"]);
$animalType = trim($_POST["animalType"]);
$injured = isset($_POST["injured"]);
$writeSomething = trim($_POST["writeSomething"]);
$userId = $_SESSION["user-id"];
$error_message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
	
		if ($userId == null) {
			echo '<script>alert("עלייך להרשם/להתחבר על מנת לפרסם");</script>';
		}
		else if (empty($address)) {
			$error_message = "אנא בחר מיקום";
		}
		else {
			$file = $_FILES['fileToUpload']['tmp_name'];
                            
			if (is_uploaded_file($file)) {
				$check = getimagesize($file);
				if ($check !== false)
				{
                	$data = file_get_contents($file);
					$base64 = 'data:' . $file_type . ';base64,' . base64_encode($data);
                
                	$insert_new_animal_query = "INSERT INTO `animals`(`address`,`color`, `animalType`,`image_base64`, `injured`, `writeSomething`, `isFound`, `created_user_id`, `founder_user_id`) VALUES ('".$address."','".$color."','".$animalType."', '".$base64."' , '".$injured."', '".$writeSomething."', 1, '".$userId."', '".$userId."')";
    
               	 	if (!$connection->query($insert_new_animal_query)) {
                   	 $error_message = "בעיה בהכנסה לבסיס הנתונים: $connection->error";
					}
				} else {
				  $error_message = "הקובץ אינו תמונה";
				}

			} else {
				$insert_new_animal_query = "INSERT INTO `animals`(`address`,`color`, `animalType`, `image_base64`,`injured`, `writeSomething`, `isFound`, `created_user_id`, `founder_user_id`) VALUES ('".$address."','".$color."','".$animalType."', '".$base64."','".$injured."', '".$writeSomething."', 1, '".$userId."', '".$userId."')";

				if (!$connection->query($insert_new_animal_query)) {
					$error_message = "בעיה בהכנסה לבסיס הנתונים: $connection->error";
				}
			}
	
			$get_all_users_query = "SELECT id, firstName, lastName, hasChipScanner, agreeToMessage, latitude, longitude, phone FROM users WHERE id != '".$userId."'";
			if ($get_users_result = $connection->query($get_all_users_query)) {
				if ($get_users_result-> num_rows > 0) {
					while ($row = $get_users_result->fetch_assoc()) {
						$currUserId = $row["id"];
						$firstName = $row["firstName"];
						$lastName = $row["lastName"];
						$lat = $row["latitude"];
						$lng = $row["longitude"];
						$agreeToMessage = $row["agreeToMessage"];
						$hasChipScanner = $row["hasChipScanner"];
						$phone = $row["phone"];
						
						$addressWithoutSpaces = preg_replace('/\s+/', '+', $address);
						$url = "https://maps.google.com/maps/api/geocode/json?address=$addressWithoutSpaces&region=il&key=AIzaSyDjZHrFdJwtrfZjG9NC-2_cY3pCJqzva3M";
						$ch = curl_init($url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$lat_lng_data = '';
						if( ($lat_lng_data = curl_exec($ch) ) === false)
						{
							echo 'Curl error: ' . curl_error($ch);
						}
						// Close handle
						curl_close($ch);
						
						$json = json_decode($lat_lng_data);
						if ($json->status == 'OK') {
							$destLat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
							$destLng = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
							
							$update_lat_lng_of_lost_animal = "UPDATE animals SET latitude='".$destLat."', longitude='".$destLng."' WHERE address='".$address."' AND created_user_id=".$userId."";
							if (!mysqli_query($connection, $update_lat_lng_of_lost_animal)) {
									$error_message = "בעיה בעדכון בסיס הנתונים: $connection->error";
							}
							
							$animal_id = "";
							$get_animal_id_query = "SELECT id FROM animals WHERE address='".$address."' AND created_user_id=".$userId."";
							if ($get_animal_id_result = $connection-> query($get_animal_id_query)) {
								if ($get_animal_id_result-> num_rows > 0) { 
									$row = $get_animal_id_result->fetch_assoc();
									$animal_id = $row["id"];
								}
							}
							
							$url = "https://maps.googleapis.com/maps/api/distancematrix/json?&origins=$lat,$lng&destinations=$destLat,$destLng&key=AIzaSyDjZHrFdJwtrfZjG9NC-2_cY3pCJqzva3M";
							$ch = curl_init($url);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							$distance_data = '';
							if( ($distance_data = curl_exec($ch) ) === false)
							{
								echo 'Curl error: ' . curl_error($ch);
							}
							// Close handle
							curl_close($ch);

							$distance_json = json_decode($distance_data);
							if ($distance_json->status == 'OK') {
								//send notification + SMS in case user is near (5KM) the found animal
								$elements = $distance_json->rows[0]->elements;
								$distanceText = $elements[0]->distance->text;
								$distanceValue = $elements[0]->distance->value;
								
								if ($distanceValue < 5000 && $agreeToMessage == 1 && $hasChipScanner == 1) {
									$loggedInUserFirstName = $_SESSION["firstName"];
									$loggedInUserLastName = $_SESSION["lastName"];
									$text = "היי $firstName, יש במרחק $distanceText ממך בקשה לסיוע מ$loggedInUserFirstName $loggedInUserLastName! לחץ לפרטים נוספים";
									$insert_new_notification = "INSERT INTO notifications (`user_id`, `text`, `animal_id`) VALUES ('".$currUserId."', '".$text."', '".$animal_id."')";
									if (!$connection->query($insert_new_notification)) {
										$error_message = "בעיה בהכנסה לבסיס הנתונים: $connection->error";
									} else {
										$notifications = [];
										$animals = [];
										$get_user_notifications_query = "SELECT id, text FROM notifications WHERE user_id = $userId";
										if ($get_notifications_result = $connection-> query($get_user_notifications_query)) {
											if ($get_notifications_result-> num_rows > 0) { 
												while ($row = $get_notifications_result->fetch_assoc()) {
													$notifications[] = (object) [
														"id" => $row["id"],
														"text" => $row["text"]
													];
												}
											}
										}
										$notificationsJson = json_encode($notifications);
										echo "<script>sessionStorage.setItem('notifications', '$notificationsJson')</script>";
															
										$smsText = "היי $firstName, יש במרחק $distanceText ממך בקשה לסיוע מ$loggedInUserFirstName $loggedInUserLastName!";
										$phoneWithoutPref =  substr($phone,1);
										$persMsg = "בדוק את ההתראות בפרופיל האישי";
										$urlToAdd = " https://ani-scan.com" . PHP_EOL . $persMsg;
										$currText = $text . $urlToAdd;
										
										//send SMS message
										sendSmsMsg($phone, $currText);
									}
								}
							} else {
								echo 'could not decode distance json';
							}

							$get_all_lost_animals_of_user_query = "SELECT a.id as lostAnimalId, a.animalType as animalType, a.name as animalName, a.latitude as lat, a.longitude as lng FROM users u JOIN animals a ON (u.id = a.created_user_id) WHERE u.id = '".$currUserId."' AND a.isFound = 0";
							if ($get_all_lost_animals_of_user_result = $connection->query($get_all_lost_animals_of_user_query)) {
								if ($get_all_lost_animals_of_user_result-> num_rows > 0) {
									while ($row = $get_all_lost_animals_of_user_result->fetch_assoc()) {
										$lostAnimalId = $row["lostAnimalId"];
										$lostAnimalType = $row["animalType"];
										$lostAnimalName = $row["animalName"];
										$lostAnimalLat = $row["lat"];
										$lostAnimalLng = $row["lng"];
										
										$url = "https://maps.googleapis.com/maps/api/distancematrix/json?&origins=$lostAnimalLat,$lostAnimalLng&destinations=$destLat,$destLng&key=AIzaSyDjZHrFdJwtrfZjG9NC-2_cY3pCJqzva3M";
										$ch = curl_init($url);
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
										$distance_data = '';
										if( ($distance_data = curl_exec($ch) ) === false)
										{
											echo 'Curl error: ' . curl_error($ch);
										}
										// Close handle
										curl_close($ch);

										$distance_json = json_decode($distance_data);
										if ($distance_json->status == 'OK') {
											$elements = $distance_json->rows[0]->elements;
											$distanceText = $elements[0]->distance->text;
											$distanceValue = $elements[0]->distance->value;
											
											if ($distanceValue < 5000 && $agreeToMessage == 1 && $lostAnimalType == $animalType) {
												$loggedInUserFirstName = $_SESSION["firstName"];
												$loggedInUserLastName = $_SESSION["lastName"];
												$text = "היי $firstName, נמצא $animalType במרחק $distanceText ממקום הדיווח על האבידה של $lostAnimalName. לחץ לפרטים נוספים.";
												$insert_new_notification = "INSERT INTO notifications (`user_id`, `text`, `animal_id`) VALUES ('".$currUserId."', '".$text."', '".$animal_id."')";
												if (!$connection->query($insert_new_notification)) {
													$error_message = "בעיה בהכנסה לבסיס הנתונים: $connection->error";
												} else {
													$notifications = [];
													$animals = [];
													$get_user_notifications_query = "SELECT id, text FROM notifications WHERE user_id = $userId";
													if ($get_notifications_result = $connection-> query($get_user_notifications_query)) {
														if ($get_notifications_result-> num_rows > 0) { 
															while ($row = $get_notifications_result->fetch_assoc()) {
																$notifications[] = (object) [
																	"id" => $row["id"],
																	"text" => $row["text"]
																];
															}
														}
													}
													$notificationsJson = json_encode($notifications);
													echo "<script>sessionStorage.setItem('notifications', '$notificationsJson')</script>";
																					   
													$phoneWithoutPref =  substr($phone,1);
													$text = "היי $firstName, נמצא $animalType במרחק $distanceText ממקום הדיווח על האבידה של $lostAnimalName.";
													$persMsg = "בדוק את ההתראות בפרופיל האישי";
													$urlToAdd = " https://ani-scan.com" . PHP_EOL . $persMsg;
													$currText = $text . $urlToAdd;
													
													//send SMS message
													sendSmsMsg($phone, $currText);
												}
											}
										}
									}
								}
							}
							
						} else {
							echo 'could not decode lat-lng json';
						}
					}
				}
			}	 
		
			echo "<script>
					window.location.href='found_animal_board.php';
				</script>";
		}
  
		$connection -> close();
}
?> 




<body>
	<div class="header">
		<h1>מצאתי בעל חיים</h1>
		<div class="close-button"><button class="close-button-size" onclick="location.href = '/index.html';">&times;</button></div>
	</div>
    <div class="container">
        <div class="page-content">
               <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
					<div class="form-group">
					  <label for="address">מיקום</label>
					  <input type="address" class="form-control" id="address" placeholder="מיקום" name="address" tabindex="1" dir="rtl" value="">
					</div>
					
					<div class="form-group">
						<label for="animalType">סוג בעל החיים</label>
						<select class="select form-control" id="animalType" name="animalType" dir="rtl">
							<option>כלב</option>
							<option>חתול</option>
						</select>
					</div>
					<div class="form-group">
						<label for="color">צבע</label>
						<select class="form-control" id="color" name="color" dir="rtl">
							<option>שחור</option>
							<option>לבן</option>
							<option>חום</option>
							<option>בז'</option>
							<option>אפור</option>
						</select>
					</div>
  										
					<div class="form-group">
						<label for="fileToUpload">בחר תמונה</label>
						<input style="margin:auto;" type="file" name="fileToUpload" id="fileToUpload">
					</div>


					<div class="form-group">
                        <label for="writeSomething">כתוב משהו</label>
                        <textarea class="form-control" cols="50" id="writeSomething" name="writeSomething" dir="rtl" placeholder="כתוב משהו..." rows="5"></textarea>
                    </div>

			<button type="submit" class="pretty-button">פרסם</button>
				
            </form>	
            <br>
            <span style="color:red;"><?php echo $error_message; ?></span>
		</div>
    </div>
</body>
</html>






