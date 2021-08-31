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

if(isset($_SESSION["user_id"]) && (!isset($_GET["aid"]))) {
	echo "Invalid address!<br /><br >";
	echo 'Go back to your appointments, <a href="view.php">click here</a>';
}


else if(isset($_SESSION["user_id"])) {
  if ($stmt = $mysqli->prepare("select status from offer where patientId=? and aid=?")) {
    $aid=htmlspecialchars($_GET["aid"]);
    $stmt->bind_param("ss", $_SESSION["user_id"], $aid);
    $stmt->execute();
    $stmt->bind_result($status);
    if ($stmt->fetch()) {
          if ($status == "declined") {
          	echo "The appointment is already been declined.<br /><br >";
          }
          if ($status == "cancelled") {
          	echo "The appointment is already been cancelled.<br /><br >";
          }
          if ($status == "missed") {
            echo "This appointment has been missed.<br /><br >";
          }
          if ($status == "complete") {
            echo "This appointment has been completed.<br /><br >";
          }
          if ($status == "accepted") {
          	echo "You accept this appointment, you can cancel it. ";
          	echo '<a href="comfirm.php?aid=';
            echo htmlspecialchars($_GET["aid"]);
            echo '&action=cancelled';
            echo '">Cancel</a>';
            echo "<br /><br >";

          }
          if ($status == "offer") {
          	echo "You can accept or decline this offer. ";
          	echo '<a href="comfirm.php?aid=';
  			echo htmlspecialchars($_GET["aid"]);
  			echo '&action=accepted';
  			echo '">Accept</a> ';
  			echo '<a href="comfirm.php?aid=';
  			echo htmlspecialchars($_GET["aid"]);
  			echo '&action=declined';
  			echo '">Decline</a>';
  			echo "<br /><br >";
          }
        }
    else {
    	echo "Appointment not found!<br /><br >";
    }  
    $stmt->close();
  }
  echo 'Go back to your appointments, <a href="view.php">click here</a>';
}
$mysqli->close();
?>

</html>