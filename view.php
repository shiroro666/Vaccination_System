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


if(isset($_SESSION["user_id"])) {
  if ($stmt = $mysqli->prepare("select appointment.aid, provider.name, provider.phone, provider.address, 111.111 * 0.62 
         * DEGREES(ACOS(LEAST(1.0, COS(RADIANS(provider.latitude))
         * COS(RADIANS(patient.latitude))
         * COS(RADIANS(provider.longitude - patient.longitude))
         + SIN(RADIANS(provider.latitude))
         * SIN(RADIANS(patient.latitude))))) AS distance_in_miles,
         adate, atime, offerdate, deadline, replydate, status from offer, appointment, provider, patient where offer.aid=appointment.aid and appointment.providerId=provider.providerId and offer.patientId=patient.patientId and patient.patientId=?")) {
    $stmt->bind_param("s", $_SESSION["user_id"]);
    $stmt->execute();
    $stmt->bind_result($aid, $providername, $phone, $address, $distance_in_miles, $adate, $atime, $offerdate, $deadline, $replydate, $status);
    echo "<table border = '1'>\n";
    echo "<tr>";
    echo "<td>Provider</td>";
    echo "<td>Phone</td>";
    echo "<td>Address</td>";
    echo "<td>Distance</td>";
    echo "<td>Appointment Date</td>";
    echo "<td>Appointment Time</td>";
    echo "<td>Date Offered</td>";
    echo "<td>Acception Deadline</td>";
    echo "<td>Your Reply Date</td>";
    echo "<td>Status</td>";
    echo "<td>Change Status</td>";
    echo "</tr>";
    while ($stmt->fetch()) {
          echo "<tr>";
            //echo "<td><a href='artist.php?artistId=".$artistId."'> $artistName</a></td>";
            echo "<td>$providername</td>";
            echo "<td>$phone</td>";
            echo "<td>$address</td>";
            echo "<td>$distance_in_miles</td>";
            echo "<td>$adate</td>";
            echo "<td>$atime</td>";
            echo "<td>$offerdate</td>";
            echo "<td>$deadline</td>";
            echo "<td>$replydate</td>";
            echo "<td>$status</td>";
            echo "<td><a href='status.php?aid=".htmlspecialchars($aid)."'>change</a></td>";
            echo "</tr>";
        }
        echo "</table>\n";
    $stmt->close();
  }
  echo '<a href="index.php">Go back</a><br /><br />';
  echo "\n";
}
$mysqli->close();
?>

</html>