<?php 

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
                              
if (!$connection->set_charset("utf8")) {
printf("Error loading character set utf8: %s\n", $connection->error); exit();
}



if(isset($_POST['cities'])){
   $city = mysqli_real_escape_string($connection,$_POST['cities']);
}

$users_arr = array();

if(true){
   $sql = "SELECT vet_name FROM slava_db_new.donations WHERE city = ? GROUP BY vet_name";
   $stmt = $connection -> prepare($sql);
   $stmt -> bind_param("s", $city);
   $stmt -> execute();
   $result = $stmt -> get_result();

   while( $row = mysqli_fetch_array($result) ){
      $vet_name = $row['vet_name'];

      $users_arr[] = array("vet_name" => $vet_name);
   }
}

echo json_encode($users_arr);

?>
