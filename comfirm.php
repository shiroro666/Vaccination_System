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

else if(isset($_SESSION["user_id"]) && (!isset($_GET["aid"]) ||  !isset($_GET["action"]))) {
  echo "Invalid address!<br /><br >";
  echo 'Go back to your appointments, <a href="view.php">click here</a>';
}


else if(isset($_SESSION["user_id"]) && ($_GET["action"] != "accepted" && $_GET["action"] != "declined" && $_GET["action"] != "cancelled")) {
  echo 'Invalid action.<br />';
  echo 'Go back to your appointments, <a href="view.php?patientId=';
  echo htmlspecialchars($_SESSION["user_id"]);
  echo '">click here</a>';
}

else if(isset($_SESSION["user_id"])) {
  if ($stmt = $mysqli->prepare("select status from offer where patientId=? and aid=? and CURDATE() <= deadline")) {
    $aid=htmlspecialchars($_GET["aid"]);
    $action=htmlspecialchars($_GET["action"]);
    $stmt->bind_param("ss", $_SESSION["user_id"], $aid);
    $stmt->execute();
    $stmt->bind_result($status);
    if ($stmt->fetch()) {
      if ($status=='cancelled' || $status=='missed' || $status=='declined' || $status=='completed'){
        echo "You cannot update this appointment!<br /><br >";
        $stmt->close();
      }
      else{
        $stmt->close();
        if ($stmt = $mysqli->prepare("update offer set status=? , replydate=CURDATE() where patientId=? and aid=?")) {
          $stmt->bind_param("sss", $action, $_SESSION["user_id"], $aid);
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
      echo "Appointment not found or expired!<br /><br >";
      $stmt->close();
    }
    
  }
  
  echo 'Go back to your appointments, <a href="view.php">click here</a>';
}
$mysqli->close();
?>

</html>