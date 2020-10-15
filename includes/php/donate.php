
<!DOCTYPE html>

<html lang="he">
<head>
    <meta charset="utf-8">
    <title>Ani-Scan Donations</title>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.1.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <link rel="stylesheet" type="text/css" href="/css/common.css">
    <link rel="stylesheet" type="text/css" href="/css/animal_board.css">
    <script src="/js/common.js"></script>
    <script src="/js/selectAjax.js"></script>
    <script src="/js/location.js"></script>
    <script src="/js/inputAjax.js"></script>
    <script src="/js/hiddenText.js"></script>
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
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $city = trim($_POST['cities']);
    $newCity = trim($_POST['cityInputText']);
    $vet_name = trim($_POST['vets']);
    $newVet = trim($_POST['vetNameText']);
    $amount = trim($_POST['amount']);
    $phone = trim($_POST['phone']);
    $link = trim($_POST['link']);
    $writeSomething = trim($_POST['writeSomething']);
    $userId = trim($_SESSION['user-id']);
    $email = trim($_SESSION['email']);
    $date = new DateTime();
    $dateResult = $date->format('d-m-Y');
    $firstName = trim($_SESSION['firstName']);
    $lastName = trim($_SESSION['lastName']);
    $error_message = "";
    

    if ($userId == null) {
	echo '<script>alert("עלייך להרשם/להתחבר על מנת לפרסם");</script>';
    }    
    elseif(empty($newCity) and strcmp($city,"Other") == 0){
    	$error_message = "נא למלא את שם העיר";
	
    }
    elseif(empty($newVet) and strcmp($vet_name, "בחר") == 0){
        $error_message = "נא למלא את שם המרפאה";
    }
    elseif (empty($amount) or empty($phone) or empty($link))
    {
        $error_message = "נא למלא את כל הפרטים";
    }
    else
    {

        if (!empty($newCity))
        {

            $stmt = $connection->prepare("INSERT INTO slava_db_new.donations (vet_name, amount, comment, link, city, vet_phone, email, user_id, date, firstName, lastName) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssssssss", $newVet, $amount, $writeSomething, $link, $newCity, $phone, $email, $userId, $dateResult, $firstName, $lastName);

            if ($stmt->execute())
            {
                echo '<script>alert("הבקשה נוצרה בהצלחה!");</script>';
                echo '<script>window.location.href = "https://ani-scan.com/includes/php/donations_board.php";</script>';
            }

        }
        else
        {

            $stmt = $connection->prepare("INSERT INTO slava_db_new.donations (vet_name, amount, comment, link, city, vet_phone, email, user_id, date, firstName, lastName) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssssssss", $vet_name, $amount, $writeSomething, $link, $city, $phone, $email, $userId, $dateResult, $firstName, $lastName);
            if ($stmt->execute())
            {
                echo '<script>alert("הבקשה נוצרה בהצלחה!");</script>';
                echo '<script>window.location.href = "https://ani-scan.com/includes/php/donations_board.php";</script>';
            }
        }
    }
}

?>
<body>
	<div class="header">
		<h1>בקשת תרומה  </h1>
		<div class="close-button">
			<button class="close-button-size" onclick="location.href = '/index.html';">&times;</button>
		</div>
	</div>
	<div class="container">
		<div dir="rtl" class="page-content">
			<form method="post" enctype="multipart/form-data" id ="mainForm">
				<div class="form-group">
					<label for="ne">עיר המרפאה  </label>
					<select id ="sel_city" name = "cities" class ="form-control" style="text-align: center;">
						<option value="0"> בחר  </option>
						<?php  
       
                          			 $sql_city = "SELECT DISTINCT city FROM slava_db_new.donations";
			                         $city_data = mysqli_query($connection,$sql_city);
                        			 while($row = mysqli_fetch_assoc($city_data) ){
                              
			                            $city = $row['city'];
                        			    echo "<option value='".$city."' >".$city."</option>";     
                           			}
                       				?>
						<option value = "Other"> אחר  </option>
					</select>
					<input type="text" class="form-control" id="cityInputText" placeholder = "הכנס עיר" name="cityInputText" style="display:none; margin-top: 2%;">
						<label for="name">  שם המרפאה  </label>
						<select id="sel_vet" name = "vets" class = "form-control" style="text-align: center;">
							<option value="0" selected > בחר   </option>
						</select>
						<input type="text" class="form-control" id="vetNameText" placeholder = "הכנס שם מרפאה " name="vetNameText" style="display: none; margin-top: 2%;">
							<label for="name"> יעד כספי בש"ח  </label>
							<input type="number" pattern = "^[0-9]$" placeholder = "הכנס סכום בשקלים"  class="form-control" id="amount" name="amount" style="text-align: center;">
								<label for="name" >טלפון המרפאה </label>
								<input type="tel" pattern = "^[0-9]{0,10}$" class="form-control" id="phone" name="phone" readonly style="text-align: center;">
									<label for="name"> Paybox Link </label>
									<input class ="form-control" id="pbLink" name ="link" readonly>
									</div>
									
										<div class="form-group">
											<label for="writeSomething">כתוב משהו</label>
											<textarea class="form-control" cols="50" id="writeSomething" name="writeSomething" dir="rtl" placeholder="כתוב משהו..." rows="5"></textarea>
										</div>
										<button type="submit" class="pretty-button" >בקש  </button>
									</form>
									<br>
										<span style="color:red;">
											<?php echo $error_message?>
										</span>
									</div>
								</div>
							   
							</body>
						</html>









