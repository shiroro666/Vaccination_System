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
  $userid = htmlspecialchars($_SESSION["user_id"]);
  $array = [
    0 => "Sunday",
    1 => "Monday",
    2 => "Tuesday",
    3 => "Wednesday",
    4 => "Thursday",
    5 => "Friday",
    6 => "Saturday",
  ];
  if ($stmt = $mysqli->prepare("select weekday, slot, distance from preference where patientId=?")) {
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $stmt->bind_result($weekday, $time, $distance);
    echo "<table border = '1'>\n";
    echo "<tr>";
    echo "<td>Weekday</td>";
    echo "<td>TimeSlot</td>";
    echo "<td>Distance</td>";
    echo "<td>Delete</td>";
    echo "</tr>";
    while ($stmt->fetch()) {
          echo "<tr>";
          echo "<td>$array[$weekday]</td>";
          echo "<td>$time</td>";
          echo "<td>$distance</td>";
          echo "<td><a href='deletion.php?weekday=".htmlspecialchars($weekday)."&time=".htmlspecialchars($time)."'>Delete</a></td>";
          echo "</tr>";
        }
        echo "</table>\n";
    $stmt->close();
  }
  echo 'Add a preference: <a href="add_preference.php';
  echo '">click here</a>, ';
  echo '<a href="index.php">Go back</a><br /><br />';
  echo "\n";
}
$mysqli->close();
?>

</html>