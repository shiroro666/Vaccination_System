<!DOCTYPE html>
<html>
<title>Add New Appointment</title>

<?php

include "connectdb.php";

if(!isset($_SESSION["user_id"])) {
  echo "You are currently not logged in. <br /><br >\n";
  echo 'Click <a href="login.php">login</a> to login or <a href="register.php">register</a> if you don\'t have an account yet.';
  echo 'Click <a href="signup.php">Signup</a> to login if you are a provider.';
  echo "\n";
}


else {
  
  if(isset($_POST["adate"]) && isset($_POST["atime"])) {
    $ad= str_replace("/", "-", htmlspecialchars($_POST["adate"]));
    if((time()+(7*60*60*24)) > strtotime($ad)){
      echo 'You must enter a date at least one week from today! Go back to view appointment, <a href="appointment.php">click here</a>';
    }
    else{
      if ($stmt = $mysqli->prepare("insert into appointment(adate, atime, providerId) values (?,?,?)")) {
        $atime=htmlspecialchars($_POST["atime"]);
        $stmt->bind_param("sss", $ad, $atime, $_SESSION["user_id"]);
        $stmt->execute();
        $stmt->close();
        echo 'Success! Go back to view appointment, <a href="appointment.php">click here</a>';
      }
    }  	 
	   
  }
  else {
    echo "Add a new appointment: <br /><br />\n";
    echo '<form action="add_appointment.php" method="POST">';
    echo "\n";
    echo 'Date: <input type="date" name="adate" required /><br />';
    echo "\n";
    echo "Time";
    echo '<input type="time" name="atime" min="08:00" max="16:00" required>';
    echo "\n";
    echo '<input type="submit" value="Submit" />';
    echo '</form>';
    echo "\n";
    echo '<br /><a href="appointment.php">Go back to view all appointment</a>';

  }
}
$mysqli->close();


?>


</html>