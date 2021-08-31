<!DOCTYPE html>
<html>
<title>Register</title>

<?php

include "connectdb.php";
if(!isset($_SESSION["user_id"])) {
  echo "You are currently not logged in. <br /><br >\n";
  echo 'Click <a href="login.php">login</a> to login or <a href="register.php">register</a> if you don\'t have an account yet.';
  echo 'Click <a href="signup.php">Signup</a> to login if you are a provider.';
  echo "\n";
}
else {
  //if the user have entered _all_ entries in the form, insert into database
  if(isset($_POST["name"]) && isset($_POST["ssn"]) && isset($_POST["birthday"]) && isset($_POST["address"]) && isset($_POST["phone"]) && isset($_POST["email"]) && isset($_POST["password"])) {
    //echo "1";
    if ($stmt = $mysqli->prepare("select name, ssn, birthday, address, latitude, longitude, phone, email, password from patient where patientId = ?")) {
      //echo "1";
      $stmt->bind_param("s", $_SESSION["user_id"]);
      $stmt->execute();
      $stmt->bind_result($name, $ssn, $birthday, $address, $latitude, $longitude, $phone, $email, $password);
      if ($stmt->fetch()) {
        if($_POST["name"] != "") {$name=$_POST["name"];}
        if($_POST["ssn"] != "") {$ssn=$_POST["ssn"];}
        if($_POST["birthday"] != "") {$birthday=$_POST["birthday"];}
        if($_POST["address"] != "") {
          $address=$_POST["address"];
          $ad= str_replace(" ", "+", $_POST["address"]);
          $apiKey='AIzaSyA0-ZPqoWul4cmsvcNchLUlPMeFsD9FV1I';
          $geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($ad).'&sensor=false&key='.$apiKey);
          $output= json_decode($geocode);
          $latitude = $output->results[0]->geometry->location->lat;
          $longitude = $output->results[0]->geometry->location->lng;
        }

        if($_POST["phone"] != "") {$phone=$_POST["phone"];}
        if($_POST["email"] != "") {$email=$_POST["email"];}
        if($_POST["password"] != "") {$password=$_POST["password"];}
        $stmt->close();
        if($stmt = $mysqli->prepare("update patient set name=?, ssn=?, birthday=?, address=?, latitude=?, longitude=?, phone=?, email=?, password=? where patientId=?")){
            $stmt->bind_param("ssssssssss", $name, $ssn, $birthday, $address, $latitude, $longitude, $phone, $email, $password, $_SESSION["user_id"]);
            $stmt->execute();
            $stmt->close();
            echo "Successfully update the profile!<br /><br >";
            echo "You will be redirected to logout in 3 seconds or click <a href=\"logout.php\">here</a>.";
            header("refresh: 3; logout.php");

        }
      }
  }
  //if not then display registration form
  
  }
  else {
    echo "Enter your information below: <br /><br />\n";
    echo '<form action="update_info.php" method="POST">';
    echo "\n";
    echo 'Name: <input type="text" name="name"/><br />';
    echo "\n";
    echo 'SSN: <input type="text" name="ssn"/><br />';
    echo "\n";
    echo 'Birthday: <input type="date" name="birthday" /><br />';
    echo "\n";
    echo 'address: <input type="text" name="address"/><br />';
    echo "\n";
    echo 'phone: <input type="text" name="phone"/><br />';
    echo "\n";
    echo 'email: <input type="text" name="email"/><br />';
    echo "\n";
    echo 'Password: <input type="password" name="password"/><br />';
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