<!DOCTYPE html>

<html lang="he">
<head>
    <meta charset="utf-8">
    <title>Ani Care - Sign in</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
	<link rel="stylesheet" type="text/css" href="/css/common.css">
	<link rel="stylesheet" type="text/css" href="/css/sign-in.css">
    <script src="/js/common.js"></script>
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

$email = trim($_POST["email"]);
$user_password = trim($_POST["password"]);
$error_message = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($email) || empty($user_password)) {
        $error_message = "דואר אלקטרוני או סיסמא ריקים";
    } else {
        $get_user_query = "SELECT * FROM users WHERE `email` = '$email'";
        if ($get_user_result = $connection-> query($get_user_query)) {
            if ($get_user_result-> num_rows > 0) { //user-name exist in users table
                $user_row = $get_user_result->fetch_assoc();
                $encrypted_password = $user_row["password"];
                $userId = $user_row["id"];
                $image = $user_row["image_base64"];
				$phone = $user_row["phone"];
				$street = $user_row["street"];
				$email = $user_row["email"];
				$firstName = $user_row["firstName"];
				$lastName = $user_row["lastName"];
				$hasChipScanner = $user_row["hasChipScanner"];
				$agreeToMessage = $user_row["agreeToMessage"];
				$lat = $user_row["latitude"];
				$lng = $user_row["longitude"];
				$savedAnimals = $user_row["saved_animals"];

                if (password_verify($user_password, $encrypted_password)) {
                    session_start();
                    $_SESSION["email"] = $email;
                    $_SESSION["firstName"] = $firstName;
					$_SESSION["lastName"] = $lastName;
                    $_SESSION["user-id"] = $userId;
					$_SESSION["phone"] = $phone;
					$_SESSION["lat"] = $lat;
					$_SESSION["lng"] = $lng;
					
                    echo "<script>sessionStorage.setItem('firstName', '$firstName');
                          sessionStorage.setItem('userId', '$userId');
						  sessionStorage.setItem('phone', '$phone');
						  sessionStorage.setItem('street', '$street');
						  sessionStorage.setItem('email', '$email');
						  sessionStorage.setItem('lastName', '$lastName');
						  sessionStorage.setItem('hasChipScanner', '$hasChipScanner');
						  sessionStorage.setItem('agreeToMessage', '$agreeToMessage');
						  sessionStorage.setItem('lat', '$lat');
						  sessionStorage.setItem('lng', '$lng');
						  sessionStorage.setItem('savedAnimals', '$savedAnimals');
                          window.location.href='/index.html';</script>";
                } else {
                    $error_message = "סיסמא שגויה";
                }
            } else {
                    $error_message = "משתמש לא קיים במערכת";
            }
        }
    }
}

$connection -> close();
?>


<body>
	<div class="header">
		<h1>התחברות</h1>
		<div class="close-button"><button class="close-button-size" onclick="location.href = '/index.html';">&times;</button></div>
	</div>
    <div class="container">
        <div class="page-content" dir="rtl">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                  <label for="email">דואר אלקטרוני</label>
                  <input type="text" class="form-control" id="email" placeholder="דואר אלקטרוני" name="email">
                </div>
                <div class="form-group">
                  <label for="password">סיסמא</label>
                  <input type="password" class="form-control" id="password" placeholder="סיסמא" name="password">
                </div>
                <button type="submit" class="pretty-button">התחבר</button>
                <div id="lastLogin"></div>
            </form>
            <br>
            <span style="color:red;"><?php echo $error_message; ?></span>
        </div>
    </div>
</body>
</html>
