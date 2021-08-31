<!DOCTYPE html>
<html>
<title>Change Patient Group</title>

<?php

include "connectdb.php";


  //if the user have entered _all_ entries in the form, insert into database
  if(isset($_POST["groupnum"])) {
    if ($stmt = $mysqli->prepare("select * from patient where patientId = ?")) {
      $stmt->bind_param("s", $_GET["patientId"]);
      $stmt->execute();
      if (!$stmt->fetch()) {
          echo "User does not exist!";
          echo '<br /><a href="view_user.php">Back to view patient information</a>';
          $stmt->close();
      }
		  else {
		    $stmt->close();
		    if ($stmt = $mysqli->prepare("update patient set prionum=? where patientId=?")) {
              $stmt->bind_param("ss", $_POST["groupnum"],$_GET["patientId"]);
              $stmt->execute();
              $stmt->close();
              echo 'Success! Go back to view, <a href="view_user.php">click here</a>, ';
          }		  
      }	 
    }
  }
  else {
    echo "Change group: <br /><br />\n";
    echo '<form action="assign_group.php?patientId=';
    echo $_GET["patientId"];
    echo '" method="POST">';
    echo "\n";
    echo "Choose Group";
    echo '<select name="groupnum" required>';
    echo '<option value="1">Doctors & Nurses</option>';
    echo '<option value="2">60-year-old and up</option>';
    echo '<option value="3">10-year-old and below</option>';
    echo '<option value="4">normal</option>';
    echo '</select>';
    echo "\n";
    echo '<input type="submit" value="Submit" />';
    echo '</form>';
    echo "\n";
    echo '<br /><a href="view_user.php">Back to view patient information</a>';

  }
$mysqli->close();


?>


</html>