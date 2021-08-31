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

if(isset($_SESSION["user_id"]) && (!isset($_GET["aid"]) || !isset($_GET["patientId"]))) {
	echo "Invalid address!<br /><br >";
	echo 'Go back to your appointments, <a href="appointment.php">click here</a>';
}

else{
  if ($stmt = $mysqli->prepare("select status from offer, appointment where offer.aid=appointment.aid and providerId= ? and patientId=? and offer.aid=?")) {
    $patientId=htmlspecialchars($_GET["patientId"]);
    $aid=htmlspecialchars($_GET["aid"]);
    $stmt->bind_param("sss", $_SESSION["user_id"], $patientId, $aid);
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
          	echo "You can Mark this appointment: ";
            echo '<a href="pcomfirm.php?patientId=';
            echo htmlspecialchars($_GET["patientId"]);
            echo '&aid=';
            echo htmlspecialchars($_GET["aid"]);
            echo '&action=complete';
            echo '">(1)Complete</a> ';
            echo '<a href="pcomfirm.php?patientId=';
            echo htmlspecialchars($_GET["patientId"]);
            echo '&aid=';
            echo htmlspecialchars($_GET["aid"]);
            echo '&action=missed';
            echo '">(2)Did not Show Up</a>';
            echo "<br /><br >";

          }
          if ($status == "offer") {
          	echo "Waiting for response!<br /><br >";
          }
        }
    else {
    	echo "Appointment not found!<br /><br >";
    }  
    $stmt->close();
  }
  echo 'Go back to your appointments, <a href="appointment.php">click here</a>';
}
$mysqli->close();
?>

</html>