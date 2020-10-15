<?php
$servername = "slavadb.cj2mmopjp4b9.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "Slava1990!";
$dbname = "slava_db_new";
$connection = new mysqli($servername, $username, $password, $dbname);
$connection->query("SET NAMES 'utf8'");

if(isset($_POST['veterinars'])){
   $vet = mysqli_real_escape_string($connection,$_POST['veterinars']);
}

$link_arr = array();

if(true){
   $sqll = "SELECT vet_name, link, vet_phone FROM slava_db_new.donations WHERE vet_name = ? GROUP BY vet_name";
   $stmtt = $connection -> prepare($sqll);
   $stmtt -> bind_param("s", $vet);
   $stmtt -> execute();
   $link_result = $stmtt -> get_result();

   while( $row = mysqli_fetch_array($link_result) ){
      $link = $row['link'];
      $vetPhone = $row['vet_phone'];
      $link_arr[] = array("link" => $link,"vetPhone" => $vetPhone);
   }
}

echo json_encode($link_arr);

?>
