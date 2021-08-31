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

else if(isset($_SESSION["user_id"]) && (!isset($_GET["aid"]) || !isset($_GET["patientId"]) || !isset($_GET["action"]))) {
  echo "Invalid address!<br /><br >";
  echo 'Go back to your appointments, <a href="appointment.php">click here</a>';
}

else if(isset($_SESSION["user_id"]) && (htmlspecialchars($_GET["action"]) != "complete" && htmlspecialchars($_GET["action"]) != "missed")) {
  echo 'Invalid action.<br />';
  echo 'Go back to your appointments, <a href="appointment.php">click here</a>';
}

else if(isset($_SESSION["user_id"])) {
  if ($stmt = $mysqli->prepare("select status from offer, appointment where offer.aid=appointment.aid and providerId= ? and patientId=? and offer.aid=?")) {
    $patientId=htmlspecialchars($_GET["patientId"]);
    $aid=htmlspecialchars($_GET["aid"]);
    $action=htmlspecialchars($_GET["action"]);
    $stmt->bind_param("sss", $_SESSION["user_id"], $patientId, $aid);
    $stmt->execute();
    $stmt->bind_result($status);
    if ($stmt->fetch()) {
      if ($status=='cancelled' || $status=='missed' || $status=='declined' || $status=='complete' || $status=='offer'){
        echo "You cannot update this appointment!<br /><br >";
        $stmt->close();
      }
      else{
        $stmt->close();
        if ($stmt = $mysqli->prepare("update offer set status=? where patientId=? and aid=?")) {
          $stmt->bind_param("sss", $action, $patientId, $aid);
          $stmt->execute();
          $stmt->close();
          echo "Successfully update the status!<br /><br >";
        }
        else{
          echo "something wrong";
        }
      }

    }
    else{
      echo "Appointment not found!<br /><br >";
      $stmt->close();
    }
    
  }
  
  echo 'Go back to your appointments, <a href="appointment.php">click here</a>';
}
$mysqli->close();
?>

</html>