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

else if(!isset($_GET["status"]) || !isset($_GET["time"])){
  echo "Invalid request<br /><br >";
  echo "Go to appointment <a href=\"appointment.php\">here</a>.\n";
}

else if(isset($_SESSION["user_id"])) {
  $input_status= htmlspecialchars($_GET["status"]);
  $input_time=htmlspecialchars($_GET["time"]);
  if ($stmt = $mysqli->prepare("select adate, atime, patient.name, patient.phone, offerdate, deadline, replydate, status from offer, appointment, patient where offer.aid=appointment.aid and offer.patientId=patient.patientId and providerId=? and status=? order by ".$input_time)) {
    $stmt->bind_param("ss", $_SESSION["user_id"], $input_status);
    $stmt->execute();
    $stmt->bind_result($adate, $atime, $name, $phone, $offerdate, $deadline, $replydate, $status);
    echo "<table border = '1'>\n";
        echo "<tr>";
        echo "<td>date</td>";
        echo "<td>time</td>";
        echo "<td>name</td>";
        echo "<td>phone</td>";
        echo "<td>assigned time</td>";
        echo "<td>reply by</td>";
        echo "<td>reply date</td>";
        echo "<td>status</td>";
        echo "</tr>";
    while ($stmt->fetch()){

          echo "<tr>";
          echo "<td>$adate</td>";
          echo "<td>$atime</td>";
          echo "<td>$name</td>";
          echo "<td>$phone</td>";
          echo "<td>$offerdate</td>";
          echo "<td>$deadline</td>";
          echo "<td>$replydate</td>";
          echo "<td>$status</td>";
          echo "</tr>";
      }
      echo "</table>\n";
      $stmt->close();
      echo '<a href="appointment.php">Go back</a><br /><br />';
      echo "\n";
    }

  }

  
$mysqli->close();
?>

</html>