<!DOCTYPE html>

<html lang="he">
<head>
    <meta charset="utf-8">
    <title>Ani-Scan - Lost an animal form</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
	<link rel="stylesheet" type="text/css" href="/css/common.css">
	<link rel="stylesheet" type="text/css" href="/css/forms.css">
	<script src="/js/common.js"></script>
	<script src="/js/location.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjZHrFdJwtrfZjG9NC-2_cY3pCJqzva3M" defer></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>


<?php
$servername = "slavadb.cj2mmopjp4b9.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "Slava1990!";
$dbname = "slava_db_new";
$connection = new mysqli($servername, $username, $password, $dbname);
$connection->query("SET NAMES 'utf8'");

session_start();
$address = trim($_POST["address"]);
$color = trim($_POST["color"]);
$name = trim($_POST["name"]);
$animalType = trim($_POST["animalType"]);
$writeSomething = trim($_POST["writeSomething"]);
$userId = $_SESSION["user-id"];
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

		if ($userId == null) {
			echo '<script>alert("עלייך להרשם/להתחבר על מנת לפרסם");</script>';
		}
		else if (empty($address) || empty($name)) {
			$error_message = "אחד מהפרטים חסרים.";
		}
		else {
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
			$destLat = "";
			$destLng = "";
			if ($json->status == 'OK') {
				$destLat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
				$destLng = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
			}
			
			$name = addslashes($name);
			$file = $_FILES['fileToUpload']['tmp_name'];
								
			if (is_uploaded_file($file)) {
				$check = getimagesize($file);
				if ($check !== false)
				{
					$data = file_get_contents($file);
					$base64 = 'data:' . $file_type . ';base64,' . base64_encode($data);
					
					$insert_new_animal_query = "INSERT INTO `animals`(`address`,`color`, `animalType`,`image_base64`,  `injured`, `writeSomething`, `name`, `created_user_id`, `latitude`, `longitude`) VALUES ('".$address."','".$color."','".$animalType."', '".$base64."' , '".$injured."', '".$writeSomething."', '".$name."', '".$userId."', '".$destLat."', '".$destLng."')";
		
					if (!$connection->query($insert_new_animal_query)) {
						$error_message = "בעיה בהכנסה לבסיס הנתונים: $connection->error";
					} else {
					   $error_message = "הנתונים הוכנסו בהצלחה";
					}
				} else {
					$error_message = "הקובץ אינו תמונה";
				}
			} else {
				
				$insert_new_animal_query = "INSERT INTO `animals`(`address`,`color`, `animalType`, `image_base64`,`injured`, `writeSomething`, `name`, `created_user_id`, `latitude`, `longitude`) VALUES ('".$address."','".$color."','".$animalType."', '".$base64."','".$injured."', '".$writeSomething."', '".$name."', '".$userId."', '".$destLat."', '".$destLng."')";
				
				if (!$connection->query($insert_new_animal_query)) {
						$error_message = "בעיה בהכנסה לבסיס הנתונים: $connection->error";

				}		
			}
			
			echo "<script>window.location.href='lost_animal_board.php';</script>";		
		}
    
	$connection -> close();
}

?> 




<body>
	<div class="header">
		<h1>אבד לי בעל חיים</h1>
		<div class="close-button"><button class="close-button-size" onclick="location.href = '/index.html';">X</button></div>
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
					<a> * </a>

					  <label for="name">שם</label>
					  <input type="name" class="form-control" id="name" placeholder="שם" name="name"  dir="rtl">
					</div>
					
					<div class="form-group">
						<label for="color">צבע</label>
						<select class="select form-control" id="color" name="color" dir="rtl">
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



