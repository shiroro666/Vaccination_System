<!DOCTYPE html>
<html>
<title>Register</title>

<?php

include "connectdb.php";

//if the user is already logged in, redirect them back to homepage
if(isset($_SESSION["username"])) {
  echo "You are already logged in. ";
  echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.";
  header("refresh: 3; index.php");
}
else {
  //if the user have entered _all_ entries in the form, insert into database
  if(isset($_POST["name"]) && isset($_POST["ssn"]) && isset($_POST["birthday"]) && isset($_POST["address"]) && isset($_POST["phone"]) && isset($_POST["email"]) && isset($_POST["username"]) && isset($_POST["password"])) {
    $ad= str_replace(" ", "+", $_POST["address"]);
    $apiKey='AIzaSyA0-ZPqoWul4cmsvcNchLUlPMeFsD9FV1I';
    $geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($ad).'&sensor=false&key='.$apiKey);
    $output= json_decode($geocode);
    $latitude = $output->results[0]->geometry->location->lat;
    $longitude = $output->results[0]->geometry->location->lng;
    //echo 'Latitude: ' . $latitude;
    //echo 'Longitude: ' . $longitude;
    //check if username already exists in database
    if ($stmt = $mysqli->prepare("select patientId from patient where patientId = ?")) {
      $stmt->bind_param("s", $_POST["username"]);
      $stmt->execute();
      $stmt->bind_result($username);
        if ($stmt->fetch()) {
          echo "That username already exists. ";
          echo "You will be redirected in 3 seconds or click <a href=\"register.php\">here</a>.";
          header("refresh: 3; register.php");
          $stmt->close();
      }
		else {
		    $stmt->close();
		    if ($stmt = $mysqli->prepare("insert into patient(patientId,name,SSN,birthday,address,latitude,longitude,phone,email,password) values (?,?,?,?,?,?,?,?,?,?)")) {
              $stmt->bind_param("ssssssssss", $_POST["username"],$_POST["name"],$_POST["ssn"],$_POST["birthday"],$_POST["address"],$latitude, $longitude,$_POST["phone"],$_POST["email"],$_POST["password"]);
              $stmt->execute();
              $stmt->close();
              echo "Registration complete, click <a href=\"index.php\">here</a> to return to homepage."; 
          }		  
        }	 
	}
  }
  //if not then display registration form
  else {
    echo "Enter your information below: <br /><br />\n";
    echo '<form action="register.php" method="POST">';
    echo "\n";
    echo 'Name: <input type="text" name="name" required /><br />';
    echo "\n";
    echo 'SSN: <input type="text" name="ssn" required /><br />';
    echo "\n";
    echo 'Birthday: <input type="date" name="birthday" max="2021-05-18" required /><br />';
    echo "\n";
    echo 'address: <input type="text" name="address" required /><br />';
    echo "\n";
    echo 'phone: <input type="text" name="phone" required /><br />';
    echo "\n";
    echo 'email: <input type="text" name="email" required /><br />';
    echo "\n";
    echo 'Username: <input type="text" name="username" required /><br />';
    echo "\n";
    echo 'Password: <input type="password" name="password" required /><br />';
    echo "\n";
    echo '<input type="submit" value="Submit" />';
    echo "\n";
    echo '</form>';
    echo "\n";
    echo '<br /><a href="index.php">Go back</a>';

  }
}
$mysqli->close();


?>


</html>