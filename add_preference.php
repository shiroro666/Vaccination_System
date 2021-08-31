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
  if(isset($_POST["weekday"]) && isset($_POST["time"]) && isset($_POST["distance"])) {
    $weekday=htmlspecialchars($_POST["weekday"]);
    $time=htmlspecialchars($_POST["time"]);
    $distance=htmlspecialchars($_POST["distance"]);
    if ($stmt = $mysqli->prepare("select * from preference where patientId = ? and weekday=? and slot=?")) {
      
      $stmt->bind_param("sss", $_SESSION["user_id"], $weekday, $time);
      $stmt->execute();
      if ($stmt->fetch()) {
          echo "You need to delete the timeslot at this weekday first";
          echo 'Return to <a href="preference.php?patientId=';
          echo htmlspecialchars($_SESSION["user_id"]);
          echo '">Preference</a>, ';
          echo "<br /><br />";
          $stmt->close();
      }
		//if not then insert the entry into database, note that user_id is set by auto_increment
		else {
		    $stmt->close();
		    if ($stmt = $mysqli->prepare("insert into preference(patientId,weekday,slot,distance) values (?,?,?,?)")) {
              $stmt->bind_param("ssss", $_SESSION["user_id"], $weekday, $time, $distance);
              $stmt->execute();
              $stmt->close();
              echo 'Success! Go back to your preference, <a href="preference.php?patientId=';
              echo htmlspecialchars($_SESSION["user_id"]);
              echo '">click here</a>, ';
          }		  
        }	 
	   }
  }
  else {
    echo "Add a new preference: <br /><br />\n";
    echo '<form action="add_preference.php" method="POST">';
    echo "\n";
    echo "Choose weekday";
    echo '<select name="weekday" required>';
    echo '<option value="0">Sunday</option>';
    echo '<option value="1">Monday</option>';
    echo '<option value="2">Tuesday</option>';
    echo '<option value="3">Wednesday</option>';
    echo '<option value="4">Thursday</option>';
    echo '<option value="5">Friday</option>';
    echo '<option value="6">Saturday</option>';
    echo '</select>';
    echo "\n";
    echo "Choose time";
    echo '<select name="time" required>';
    echo '<option value="8:00">8:00AM-12:00PM</option>';
    echo '<option value="12:00">12:00PM-16:00PM</option>';
    echo '</select>';
    echo "\n";
    echo 'Distance in miles: <input type="text" name="distance" required /><br />';
    echo "\n";
    echo '<input type="submit" value="Submit" />';
    echo '</form>';
    echo "\n";
    echo '<br /><a href="preference.php?patientId=';
    echo htmlspecialchars($_SESSION["user_id"]);
    echo '">Back to view preference</a>';

  }
}
$mysqli->close();


?>


</html>