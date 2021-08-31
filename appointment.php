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
  $userid=htmlspecialchars($_SESSION["user_id"]);

  if ($stmt = $mysqli->prepare("select providerId from provider where providerId=?")) {
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    if (!$stmt->fetch()){
      echo "You are not authorized to this page. <br /><br >\n";
      echo '<a href="index.php">Go back</a><br /><br />';
      $stmt->close();
    }
    else{
      //echo "1";
      $stmt->close();
      echo "Summary<br />";
      if ($stmt = $mysqli->prepare("with t1 as (select status from appointment, offer where appointment.aid=offer.aid and providerId=?),
      t2 as (select count(*) as o_ct from t1 where status=\"offer\"),
      t3 as (select count(*) as a_ct from t1 where status=\"accepted\"),
      t4 as (select count(*) as d_ct from t1 where status=\"declined\"),
      t5 as (select count(*) as c_ct from t1 where status=\"cancelled\"),
      t6 as (select count(*) as m_ct from t1 where status=\"missed\"),
      t7 as (select count(*) as com_ct from t1 where status=\"complete\")
      select o_ct, a_ct, d_ct, c_ct, m_ct, com_ct from t2,t3,t4,t5,t6,t7")) {
        $stmt->bind_param("s", $userid);
        $stmt->execute();
        $stmt->bind_result($o_ct, $a_ct, $d_ct, $c_ct, $m_ct, $com_ct);
        echo "<table border = '1'>\n";
        echo "<tr>";
        echo "<td>Offered Count</td>";
        echo "<td>Accept Count</td>";
        echo "<td>Declined Count</td>";
        echo "<td>Cancelled Count</td>";
        echo "<td>Missed Count</td>";
        echo "<td>Complete Count</td>";
        echo "</tr>";
        while ($stmt->fetch()) {
          echo "<tr>";
          echo "<td>$o_ct</td>";
          echo "<td>$a_ct</td>";
          echo "<td>$d_ct</td>";
          echo "<td>$c_ct</td>";
          echo "<td>$m_ct</td>";
          echo "<td>$com_ct</td>";
          echo "</tr>";
        }
    echo "</table>\n";
    $stmt->close();
    }
    echo "<br />";
    echo "Your appointments<br />";
      if ($stmt = $mysqli->prepare("select adate, atime from appointment where providerId=? order by adate, atime")){
        $stmt->bind_param("s", $userid);
        $stmt->execute();
        $stmt->bind_result($adate, $atime);
        echo "<table border = '1'>\n";
        echo "<tr>";
        echo "<td>date</td>";
        echo "<td>time</td>";
        echo "</tr>";
        while ($stmt->fetch()) {
          echo "<tr>";
          echo "<td>$adate</td>";
          echo "<td>$atime</td>";
          echo "</tr>";
        }
        echo "</table>\n";
        $stmt->close();
      }
      echo '<a href="add_appointment.php">Add New</a><br /><br />';
      echo "Your appointments' assignments<br />";
      if ($stmt = $mysqli->prepare("select offer.patientId, offer.aid, adate, atime, patient.name, patient.phone, offerdate, deadline, replydate, status from offer, appointment, patient where offer.aid=appointment.aid and offer.patientId=patient.patientId and providerId=?")) {
        $stmt->bind_param("s", $userid);
        $stmt->execute();
        $stmt->bind_result($pid, $aid, $adate, $atime, $name, $phone, $offerdate, $deadline, $replydate, $status);
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
        echo "<td>update</td>";
        echo "</tr>";
        while ($stmt->fetch()) {
          echo "<tr>";
          echo "<td>$adate</td>";
          echo "<td>$atime</td>";
          echo "<td>$name</td>";
          echo "<td>$phone</td>";
          echo "<td>$offerdate</td>";
          echo "<td>$deadline</td>";
          echo "<td>$replydate</td>";
          echo "<td>$status</td>";
          echo "<td><a href='pstatus.php?aid=".htmlspecialchars($aid)."&patientId=".htmlspecialchars($pid)."'>change</a></td>";
          echo "</tr>";
        }
        echo "</table>\n";
        $stmt->close();
        echo "<br />";
        echo "Filter the appointment: <br /><br />\n";
        echo '<form action="search_appointment.php" method="GET">';
        echo "\n";
        echo "Choose status";
        echo '<select name="status">';
        echo '<option value="offer">offer</option>';
        echo '<option value="accepted">accepted</option>';
        echo '<option value="declined">declined</option>';
        echo '<option value="cancelled">cancelled</option>';
        echo '<option value="missed">missed</option>';
        echo '<option value="complete">complete</option>';
        echo '</select>';
        echo "\n";
        echo "Sort by";
        echo '<select name="time" required>';
        echo '<option value="replydate">Acceptance(Reply Date)</option>';
        echo '<option value="offerdate">Assigned(Offered) Date</option>';
        echo '<option value="adate,atime">Appointment Time</option>';
        echo '</select>';
        echo "\n";
        echo '<input type="submit" value="Submit" />';
        echo '</form>';
        echo "\n";
        echo "<br /><br >";
        echo '<a href="logout.php">Logout</a><br /><br />';
        echo "\n";
      }
    }

  }

  
}
$mysqli->close();
?>

</html>