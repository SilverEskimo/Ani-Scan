<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
      <title>Ani-Scan - Donations Board</title>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <script src="https://code.jquery.com/jquery-1.10.1.js"></script>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" type="text/css" href="/css/donations_board.css">
      <link rel="stylesheet" type="text/css" href="/css/common.css">
      <script src="/js/common.js"></script>
      <script src="/js/animal_board.js"></script>
  </head>
  <body>
    <?php
      session_start();
      $userId = $_SESSION["user-id"];
      $servername = "slavadb.cj2mmopjp4b9.us-east-1.rds.amazonaws.com";
      $username = "admin";
      $password = "Slava1990!";
      $dbname = "slava_db_new";
      $connection = new mysqli($servername, $username, $password, $dbname);
      $connection->query("SET NAMES 'utf8'");

    ?>
    <div class="header">
      <h1>בקשות לתרומה</h1>
        <div class="close-button">
          <button class="close-button-size" onclick="location.href = '/index.html';">X</button>
        </div>
    </div>
    <div>
      <button class="pretty-button" style = "margin-top: 1%;" onclick="location.href = '/includes/php/donate.php';">בקשת תרומה חדשה </button>
    </div>
    <div class="container">
      <div class="board-page-content">
      <?php
        if ($connection->connect_error)
        {
            die("Connection failed: " . $connection->connect_error);
        }

        if (!$connection->set_charset("utf8"))
        {
            printf("Error loading character set utf8: %s\n", $connection->error);
            exit();
        }
        $donation_query = "SELECT u.phone, u.lastName, u.firstName, vet_name, amount, comment, link, date, city, created_at FROM slava_db_new.donations as d JOIN slava_db_new.users as u ON (u.id = d.user_id) ORDER BY created_at DESC";
        $donation_result = $connection->query($donation_query);
        if ($donation_result->num_rows > 0)
        {
          while ($row = $donation_result->fetch_assoc())
          {
            $user_details = '' . $row["firstName"] . ' ' . $row["lastName"] . ',  טלפון ' . $row["phone"] . '';
            $vet_name = $row["vet_name"];
            $amount = $row["amount"];
            $writeSomething = empty($row["comment"]) ? ללא : $row["comment"];
            $link = $row["link"];
            $date = $row["date"];
            $city = $row["city"];
            $firstName = $row["firstName"];
            $lastName = $row["lastName"];
            $donation_id = $row["id"];
            $phone = $row["phone"];
  
            echo '
              <div id="animals">
                <div class="animal-post" id="contentCatalog">
                  <span>פורסם על ידי: '.$firstName.' '.$lastName.'</span>
                  <br>
                  <span>פורסם בתאריך: '.$date.' </span>
                  <br>
                  <span>טלפון המפרסם: '.$phone.' </span>
                  <br>
                  <span>שם המרפאה: '.$vet_name.' </span>
                  <br>
                  <span>עיר המרפאה: '.$city.'</span>
                  <br>
                  <span>יעד כספי: '.$amount.' </span>
                  <br>
                  <span dir="rtl">תיאור בקשת תרומה: ' . $writeSomething . ' </span>
                  <br>
                  <span>:לחץ לתשלום</span>
                  <div>
                    <a href = ' . $link . ' >
                      <div style ="height: 50px; width: 50px; display: inline-block;margin-top: 0.5%;margin-right: 41%;">
                      <img src = "upload_images/pb3.jpg" alt = "pbox"style = "border-radius: 15px;">
                      </div>
                    </a>
                  </div>
                </div>
              </div>';
          }
        }       
      ?>
      </div>
    </div>
    <div id="footer-nav"></div>
  </body>
</html>





