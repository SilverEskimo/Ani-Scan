<!DOCTYPE html>
<?php
$servername = "slavadb.cj2mmopjp4b9.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "Slava1990!";
$dbname = "slava_db_new";
$connection = new mysqli($servername, $username, $password, $dbname);
$connection->query("SET NAMES 'utf8'");

session_start();
$firstName = trim($_POST["first-name"]);
$lastName = trim($_POST["last-name"]);
$email = trim($_POST["email"]);
$street = trim($_POST["street"]);
$phone = trim($_POST["phone"]);
$hasChipScanner = $_POST['has-chip-scanner'] == 'on' ? 1 : 0;
$agreeToMessage = $_POST['agree-to-message'] == 'on' ? 1 : 0;
$userId = $_SESSION["user-id"];
$error_message = "";

$notifications = [];
$get_user_notifications_query = "SELECT id, text, animal_id FROM notifications WHERE user_id = $userId";
if ($get_notifications_result = $connection-> query($get_user_notifications_query)) {
	if ($get_notifications_result-> num_rows > 0) { 
		while ($row = $get_notifications_result->fetch_assoc()) {
			$notifications[] = (object) [
				"id" => $row["id"],
				"text" => $row["text"],
				"animal_id" => $row["animal_id"]
			];
		}
	}
}
$notificationsJson = json_encode($notifications);
echo "<script>sessionStorage.setItem('notifications', '$notificationsJson')</script>";



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone)) {
        $error_message = "אחד מהשדות או יותר ריקים";
    } else {
        $check_if_user_name_exist_query = "SELECT * FROM users WHERE `id` = '$userId'";
        if ($is_user_name_exist_result = $connection-> query($check_if_user_name_exist_query)) {
            if ($is_user_name_exist_result-> num_rows > 0) {
				
				$addressWithoutSpaces = preg_replace('/\s+/', '+', $street);
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
				$destLat = "";
				$destLng = "";
				if ($json->status == 'OK') {
					$destLat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
					$destLng = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
				}
				
                $update_query = "UPDATE users SET firstName='".$firstName."', lastName='".$lastName."', phone='".$phone."', street='".$street."', email='".$email."', hasChipScanner='".$hasChipScanner."', agreeToMessage='".$agreeToMessage."', latitude='".$destLat."', longitude='".$destLng."' WHERE id=".$userId."";
				if (mysqli_query($connection, $update_query)){
					
					echo "<script>
						sessionStorage.setItem('phone', '$phone');
						sessionStorage.setItem('street', '$street');
						sessionStorage.setItem('email', '$email');
						sessionStorage.setItem('firstName', '$firstName');
						sessionStorage.setItem('lastName', '$lastName');
						sessionStorage.setItem('hasChipScanner', '$hasChipScanner');
						sessionStorage.setItem('agreeToMessage', '$agreeToMessage');
						window.location.href='my-profile.php';
					</script>";
				}
            }
        }
		else {
			echo $connection->error;
		}
    }
    
    $connection -> close();
}
?>
<html lang="he">
<head>
    <meta charset="utf-8">
    <title>Ani-Scan My Profile</title>
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">	
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <link rel="stylesheet" type="text/css" href="/css/my-profile.css">
	<link rel="stylesheet" type="text/css" href="/css/common.css">

	<script src="/js/common.js"></script>
	<script src="/js/my-profile.js"></script>
	
</head>
<body>

	<div class="header">
		<h1>פרופיל אישי</h1>
		<div class="close-button"><button class="close-button-size" onclick="location.href = '/index.html';">&times;</button></div>
	</div>
    <div class="container">
        <div class="page-content" dir="rtl" style="flex-direction: column;">
			<div style="display: flex; flex-direction: column;">
				<div style="padding: 5px;font-weight: bolder; font-size: medium; color: #114d61;" id="firstName"></div>
				<div style="padding: 5px;font-weight: bolder; font-size: medium; color: #114d61;" id="animalsFound"></div>
			</div>
			<div class="profile-buttons"> 
				<button class="pretty-button" onClick=changeDivSelection('my-reports')>הדיווחים שלי</button>
				<div id="my-reports">
					<?php
						$get_all_related_animals_query = "SELECT * FROM animals WHERE created_user_id = $userId";
						if ($get_all_related_animals_result = $connection-> query($get_all_related_animals_query)) {
							if ($get_all_related_animals_result -> num_rows > 0) { 
								$firstName = $_SESSION["firstName"];
								$lastName = $_SESSION["lastName"];
								$phone = $_SESSION["phone"];
								while ($row = $get_all_related_animals_result -> fetch_assoc()) {
									$user_details = ''.$firstName.' '.$lastName.',  טלפון '.$phone.'';

									$id = $row["id"];
									$animalType = $row["animalType"];
									$color = $row["color"];
									$writeSomething = empty($row["writeSomething"]) ? ללא : $row["writeSomething"];
									$date_created = $row["date_created"];
									$newDate = date("H:i d/m/Y ", strtotime($date_created));
									$animalId = $row["id"];
									$injured= $row["injured"];
									$name= $row["name"];
									$address= $row["address"];
									$image = $row['image_base64'];
									
									echo '<div id="animals">
											<div id="'.$animalType.'" class="animal-post">
												<div style="display:flex; flex-direction:row;">
													<div>
														<span id="'.$id.'" class="delete-animal-post">&times;</span>
														<span class="user-details">'.$user_details.'</span>
														<br>
														<span>'.$newDate.'</span>
														<br>
														<a href="https://www.google.com/maps/place/'.$address.'/">'.$address.'</a>
														<br>
														<span>סוג: '.$animalType.'</span>
														<br>
														<span>שם: '.$name.'</span>
														<br>
														<span>צבע: '.$color.'</span>
														<br>
														<span dir="rtl">תיאור: '.$writeSomething.' </span>
														<br>
														<div>
															<br>
															<label for="post-status">סטטוס</label>
															<select class="select post-status" id="'.$id.'" name="post-status" dir="rtl">
																<option>משוטט</option>
																<option>חזר לבעלים</option>
															</select>
														</div>
													</div>
													<div>
														<img id="image" src="'.$image.'" style="max-width:180px; max-height:150px;" />
													</div>
												</div>
											</div>
										</div>';
								}
							} else {
								echo '<span>לא נמצאו דיווחים</span>';
							}
						}
					?>
				</div>
				<button class="pretty-button" style="margin-top: 5px;" onClick=changeDivSelection('my-notifications')>ההתראות שלי</button>
				<div id="my-notifications">
					<table id="notifications-table">
					</table>
				</div>
				<button class="pretty-button" style="margin-top: 5px;" onClick=changeDivSelection('my-details')>פרטים אישיים</button>
				<div id="my-details">
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<div class="form-group">
							<label for="first-name">* שם פרטי</label>
							<input type="first-name" class="form-control" id="first-name" placeholder="שם פרטי" name="first-name">
						</div>
						<div class="form-group">
							<label for="last-name">* שם משפחה</label>
							<input type="last-name" class="form-control" id="last-name" placeholder="שם משפחה" name="last-name">
						</div>
							<div class="form-group">
							<label for="email">* דואר אלקטרוני</label>
							<input type="email" class="form-control" id="email" placeholder="דואר אלקטרוני" name="email">
						</div>
						<div class="form-group">
							<label for="phone">טלפון נייד</label>
							<input type="tel" placeholder="0501234567" pattern="[0][5][0-9][0-9]{7}" class="form-control" id="phone" name="phone">
						</div>
						<div class="form-group">
							<label for="street">כתובת מגורים</label>
							<input type="street" class="form-control" id="street" placeholder="רחוב" name="street">
						</div>

						<div class="form-group">
							<label for="password">סיסמא</label>
							<input type="password" class="form-control" id="password" placeholder="סיסמא" name="password" >
						</div>
						
						<div class="form-check">
							<label for="has-chip-scanner">
							<input type="checkbox" class="form-check-input" id="has-chip-scanner" name="has-chip-scanner"> יש בבעלותי סורק צ'יפים   </label>           						
						</div>

						<div class="form-check">
							<label for="agree-to-message">
							<input type="checkbox" class="form-check-input" id="agree-to-message" name="agree-to-message"> אני מאשר/ת קבלת הודעות באשר לבעלי חיים שזקוקים לעזרה  </label>           						
						</div>

						<button type="submit" class="btn btn-default">עדכן</button>
					</form>
				</div>
			</div>
        </div>
    </div>

	<div id="footer-nav"></div>
</body>

</html>
