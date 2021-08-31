<!DOCTYPE html>
<html>

<?php

include ("connectdb.php");

if (isset($_GET["update"])){
  echo "You just update the appointment offers!<br/>";
  //automatically decline the appointment
  if ($stmt = $mysqli->prepare("update offer set status=\"declined\", replydate=CURDATE() where deadline < CURDATE() and status = \"offer\"")) {
    $stmt->execute();
    $stmt->close();
  }
  while($stmt = $mysqli->prepare("with t1 as(
  select aid,adate,atime,providerId,patient.prionum,email,slot,patient.patientId,distance 
  from preference, appointment, patient, grouptype
  where weekday(adate)=preference.weekday and 
  patient.patientId=preference.patientId and
  patient.prionum=grouptype.prionum and
  grouptype.startdate <= adate and
  adate > DATE_ADD(CURDATE(), INTERVAL 7 DAY) and
  TIMESTAMPDIFF(hour, slot, atime) >= 0 and 
  TIMESTAMPDIFF(hour, slot, atime)<4),
t2_1 as (
  select * from t1 
  where aid not in (select aid from offer where status='offer' or status='accepted' or status='complete') and
  patientId not in (select patientId from offer where status='offer' or status='accepted' or status='complete')
),
t2_2 as (
  select t2_1.aid, t2_1.patientId from t2_1, offer where t2_1.aid=offer.aid and t2_1.patientId=offer.patientId and 
    (status='missed' or status='declined' or status='cancelled')
),
t2 as (
  select t2_1.aid,t2_1.adate,t2_1.atime,t2_1.providerId,t2_1.prionum,t2_1.email,t2_1.slot,t2_1.patientId,t2_1.distance 
    from t2_1 left join t2_2 on t2_1.aid=t2_2.aid and t2_1.patientId=t2_2.patientId where 
    t2_2.aid is null or t2_2.patientId is null
),
t3 as (
  select aid, adate, atime, t2.providerId, provider.name, provider.address, t2.patientId, t2.prionum, t2.email,distance, 
         111.111 * 0.62 
         * DEGREES(ACOS(LEAST(1.0, COS(RADIANS(provider.latitude))
         * COS(RADIANS(patient.latitude))
         * COS(RADIANS(provider.longitude - patient.longitude))
         + SIN(RADIANS(provider.latitude))
         * SIN(RADIANS(patient.latitude))))) AS distance_in_miles
         from t2, provider, patient 
         where provider.providerId=t2.providerId and 
         patient.patientId=t2.patientId),
t4 as (
  select patientId, prionum, email, providerId, name, address, aid, adate, atime, distance_in_miles from t3
  where distance_in_miles <= distance
),
t5 as (
  select aid, count(*) as a_ct from t4 group by aid
)
select patientId, prionum, email, providerId, name, address, t4.aid, adate, atime, distance_in_miles
from t4, t5 where t4.aid=t5.aid
order by prionum, a_ct, adate, atime, distance_in_miles")){
    $stmt->execute();
    $stmt->bind_result($patientId, $prionum, $email, $providerId, $name, $address, $aid, $adate, $atime, $distance_in_miles);
    if($stmt->fetch()){
      $stmt->close();
      if($stmt = $mysqli->prepare("insert into offer(aid, patientid, offerdate, deadline, status) values(?,?,CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY),\"offer\")")){
        $stmt->bind_param("ss", $aid, $patientId);
        $stmt->execute();
        //send email
        $msg = "Hello ".$patientId."!\n You are assigned to the ".$name." at ".$address." on ".$adate." ".$atime.".\n";
        echo $msg;
        echo "<br/>";
        $msg = wordwrap($msg, 200);
        mail($email,"Appointment Assigned",$msg);
      }
      $stmt->close();
    }
    else{
      $stmt->close();
      break;
    }

  }

}
if ($stmt = $mysqli->prepare("select patientId, offerdate, deadline, replydate, status, appointment.aid, adate, atime, providerId from offer, appointment where offer.aid=appointment.aid order by offerdate")) {
  $stmt->execute();
  $stmt->bind_result($patientId, $offerdate, $deadline, $replydate, $status, $aid, $adate, $atime, $providerId);
  echo "<table border = '1'>\n";
  echo "<tr>";
  echo "<td>Patient ID</td>";
  echo "<td>Offered Date</td>";
  echo "<td>Reply By</td>";
  echo "<td>Reply date</td>";
  echo "<td>Status</td>";
  echo "<td>Appointment ID</td>";
  echo "<td>Appointment Date</td>";
  echo "<td>Appointment Date</td>";
  echo "<td>Provider ID</td>";
  echo "</tr>";
  while ($stmt->fetch()) {
    echo "<tr>";
    echo "<td>$patientId</td>";
    echo "<td>$offerdate</td>";
    echo "<td>$deadline</td>";
    echo "<td>$replydate</td>";
    echo "<td>$status</td>";
    echo "<td>$aid</td>";
    echo "<td>$adate</td>";
    echo "<td>$atime</td>";
    echo "<td>$providerId</td>";
    echo "</tr>";
    }
    echo "</table>\n";
    $stmt->close();
  }
  echo "Assign Appointment:\n";
  echo '<form action="assign_appointment.php?update=true" method="POST">';
  echo '<input type="submit" value="Update" />';
  echo '</form>';
  echo '<a href="admin.php">Go back</a><br /><br />';
  echo "\n";
$mysqli->close();
?>

</html>