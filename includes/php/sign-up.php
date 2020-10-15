<!DOCTYPE html>
<?php
$servername = "slavadb.cj2mmopjp4b9.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "Slava1990!";
$dbname = "slava_db_new";
$connection = new mysqli($servername, $username, $password, $dbname);
$connection->query("SET NAMES 'utf8'");

$firstName = trim($_POST["first-name"]);
$lastName = trim($_POST["last-name"]);
$email = trim($_POST["email"]);
$street = trim($_POST["street"]);
$phone = trim($_POST["phone"]);
$hasChipScanner = $_POST['has-chip-scanner'] === 'on' ? 1 : 0;
$agreeToMessage = $_POST['agree-to-message'] === 'on' ? 1 : 0;
$user_password = trim($_POST["password"]);
$lat = trim($_POST["lat"]);
$lng = trim($_POST["lng"]);
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($firstName) || empty($lastName) || empty($email)|| empty($street) || empty($user_password) || empty($phone)) {
		$error_message = "אחד מהשדות או יותר ריקים";
	} else {
		$check_if_email_exist_query = "SELECT * FROM users WHERE `email` = '$email'";
		if ($is_email_exist_result = $connection-> query($check_if_email_exist_query)) {
			if ($is_email_exist_result-> num_rows > 0) {
				$error_message = "אימייל קיים במערכת";
			} else {
				if (strlen($user_password) < 6 || strlen($user_password) > 10) {
					$error_message = "הסיסמא צריכה להכיל בין 6 ל 10 תווים";
				} else {
					$encrypted_password = password_hash($user_password, PASSWORD_DEFAULT);
					
					$insert_new_user_query = "INSERT INTO users (`password`, `firstName`, `lastName`, `email`, `phone`, `street`, `hasChipScanner`, `agreeToMessage`, `latitude`, `longitude`) VALUES ( '".$encrypted_password."', '".$firstName."', '".$lastName."', '".$email."', '".$phone."', '".$street."', '".$hasChipScanner."', '".$agreeToMessage."', '".$lat."', '".$lng."')";
					
					if (!$connection->query($insert_new_user_query)) {
						$error_message = "בעיה בהכנסה לבסיס הנתונים";
					} else {
						echo "<script>window.location.href='/includes/php/sign-in.php';</script>";
					}
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
    <title>Ani-Scan - Register</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
	<link rel="stylesheet" type="text/css" href="/css/common.css">
	<link rel="stylesheet" type="text/css" href="/css/sign-up.css">
	<script src="/js/location.js"></script>
	<script src="/js/sign-up.js"></script>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjZHrFdJwtrfZjG9NC-2_cY3pCJqzva3M&callback=initAutocomplete&libraries=places&v=weekly&region=il&language=iw"
      defer
    ></script>


	<link rel="stylesheet" type="text/css" href="/css/common.css">
<meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>

	<div class="header">
		<h1>רישום</h1>
		<div class="close-button"><button class="close-button-size" onclick="location.href = '/index.html';">X</button></div>
	</div>

    <div class="container">
        <div class="page-content" dir="rtl">
			<span style="color:red; display: block;"><?php echo $error_message; ?></span>

            <form id="create-new-user" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
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
					<label for="phone">* טלפון נייד</label>
					<input type="tel" placeholder="0501234567" pattern="[0][5][0-9][0-9]{7}" class="form-control" id="phone" name="phone">
                </div>
				
				<div class="form-group">
					<label for="street">* כתובת מגורים</label>
					<input type="street" class="form-control" id="street" placeholder="רחוב" name="street">
                </div>
				
				<div class="form-group">
					<input type="lat" class="form-control" id="lat" name="lat" style="display:none;">
                </div>
				
				<div class="form-group">
					<input type="lng" class="form-control" id="lng" name="lng" style="display:none;">
                </div>

				<div class="form-group">
					<label for="password">* סיסמא</label>
					<input type="password" class="form-control" id="password" placeholder="סיסמא" name="password" >
                </div>
                
				<div class="form-check">
					<label for="has-chip-scanner">
					<input type="checkbox" class="form-check-input" id="has-chip-scanner" name="has-chip-scanner"> יש בבעלותי סורק צ'יפים   </label>           						
				</div>

				<div class="form-check">
					<label for="has-chip-scanner">
					<input type="checkbox" class="form-check-input" id="agree-to-message" name="agree-to-message"> אני מאשר/ת קבלת הודעות באשר לבעלי חיים שזקוקים לעזרה  </label>           						
				</div>

			<br>
                <button type="submit" id="submit-btn" class="pretty-button">הרשם</button>
            </form>
            <br>

        </div>
    </div>

	<div id="footer-nav"></div>
</body>

</html>









