<!DOCTYPE html>
<html>

<?php

include ("connectdb.php");

if ($stmt = $mysqli->prepare("select patientId, name, ssn, birthday, address, phone, email, patient.prionum, startdate from patient left join grouptype on patient.prionum=grouptype.prionum")) {
  $stmt->execute();
  $stmt->bind_result($pid, $name, $ssn, $birthday, $address, $phone, $email, $prionum, $startdate);
  echo "<table border = '1'>\n";
  echo "<tr>";
  echo "<td>Patient ID</td>";
  echo "<td>Name</td>";
  echo "<td>SSN</td>";
  echo "<td>Birthday</td>";
  echo "<td>Address</td>";
  echo "<td>Phone</td>";
  echo "<td>Email</td>";
  echo "<td>Group number</td>";
  echo "<td>Eligible Date</td>";
  echo "<td>Change Group</td>";
  echo "</tr>";
  while ($stmt->fetch()) {
    echo "<tr>";
    echo "<td>$pid</td>";
    echo "<td>$name</td>";
    echo "<td>$ssn</td>";
    echo "<td>$birthday</td>";
    echo "<td>$address</td>";
    echo "<td>$phone</td>";
    echo "<td>$email</td>";
    echo "<td>$prionum</td>";
    echo "<td>$startdate</td>";
    echo "<td><a href='assign_group.php?patientId=".htmlspecialchars($pid)."'>change</a></td>";
    echo "</tr>";
    }
    echo "</table>\n";
    $stmt->close();
  }
  echo '<a href="admin.php">Go back</a><br /><br />';
  echo "\n";
$mysqli->close();
?>

</html>