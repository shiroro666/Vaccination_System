<!DOCTYPE html>
<html>

<?php

include ("connectdb.php");

if(!isset($_SESSION["user_id"])) {
  echo "You are currently not logged in. <br /><br >\n";
  echo 'Click <a href="login.php">login</a> to login or <a href="register.php">register</a> if you don\'t have an account yet.';
  echo 'Click <a href="signup.php">Signup</a> to login if you are a provider.';
  echo "\n";
}

else if(isset($_SESSION["user_id"]) && (!isset($_GET["weekday"]) || !isset($_GET["time"]))) {
  echo "Invalid address!<br /><br >";
  echo 'Go back to your preferences, <a href="preference.php?patientId=';
  echo htmlspecialchars($_SESSION["user_id"]);
  echo '">click here</a>';
}

else if(isset($_SESSION["user_id"])) {
  if ($stmt = $mysqli->prepare("select * from preference where patientId=? and weekday=? and slot=?")) {
    $user_id=htmlspecialchars($_SESSION["user_id"]);
    $weekday = htmlspecialchars($_GET["weekday"]);
    $time = htmlspecialchars($_GET["time"]);
    $stmt->bind_param("sss", $user_id, $weekday, $time);
    $stmt->execute();
    
    if ($stmt->fetch()) {
      $stmt->close();
      if($stmt = $mysqli->prepare("SET SQL_SAFE_UPDATES=0")){
        //echo "deletion";
        $stmt->execute();
        $stmt->close();
      }
      if ($stmt = $mysqli->prepare("delete from preference where patientId=? and weekday=? and slot=?")) {
          $stmt->bind_param("sss", $user_id, $weekday, $time);
          $stmt->execute();
          $stmt->close();
          echo "Successfully delete the preference!<br /><br >";
      }
      if($stmt = $mysqli->prepare("SET SQL_SAFE_UPDATES=1")){
        //echo "deletion";
        $stmt->execute();
        $stmt->close();
      }
    }
    else{
      $stmt->close();
    }
  }
  echo 'Go back to your preferences, <a href="preference.php?patientId=';
  echo htmlspecialchars($_SESSION["user_id"]);
  echo '">click here</a>';
  echo "\n";
}
$mysqli->close();
?>

</html>