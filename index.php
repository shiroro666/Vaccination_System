<!DOCTYPE html>
<html>
<title>Patient Index Page</title>

<?php

include ("connectdb.php");

if(!isset($_SESSION["user_id"])) {
  echo "You are currently not logged in. <br /><br >\n";
  echo 'Click <a href="login.php">login</a> to login or <a href="register.php">register</a> if you don\'t have an account yet.<br /><br />';
  echo 'Click <a href="signup.php">Signup</a> to login if you are a provider.';
  echo "\n";
}
else {
  $username = htmlspecialchars($_SESSION["username"]);
  $user_id = htmlspecialchars($_SESSION["user_id"]);
  //echo "4";
  if ($stmt = $mysqli->prepare("select patientId, name, ssn, birthday, address, phone, email from patient where patientId=?")){
    //echo("1");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->bind_result($pid, $name, $ssn, $birthday, $address, $phone, $email);
    if ($stmt->fetch()){
      echo "Welcome $username. You are logged in to the system.<br /><br />\n";
      echo "Your Information:<br />";
      echo "UserID:".$pid."<br />";
      echo "Name:".$name."<br />";
      echo "SSN:".$ssn."<br />";
      echo "Birthday:".$birthday."<br />";
      echo "Address:".$address."<br />";
      echo "Phone:".$phone."<br />";
      echo "E-Mail:".$email."<br /><br />";
      echo 'To view the your appointment offers, <a href="view.php">click here</a>, ';
      echo "<br /><br />";
      echo 'Update information: <a href="update_info.php">click here</a>, ';
      echo "<br /><br />";
      echo 'View or add preferences: <a href="preference.php">click here</a>, ';
      echo "<br /><br />";
      echo 'Log out: <a href="logout.php">click here</a>.';
      echo "\n";
    }
    $stmt->close();
  }
  
  if ($stmt = $mysqli->prepare("select providertype, name, phone, address from provider where providerId=?")){
    //echo("2");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->bind_result($pt, $name, $phone, $address);
    if ($stmt->fetch()){
      echo "Welcome $username. You are logged in to the system.<br /><br />\n";
      echo "Your Information:<br />";
      echo "Provider Type:".$pt."<br />";
      echo "Name:".$name."<br />";
      echo "Address:".$address."<br />";
      echo "Phone:".$phone."<br /><br />";
      echo "To view or add appointment <a href=\"appointment.php\">here</a>.";
      echo "<br /><br />";
      echo 'Log out: <a href="logout.php">click here</a>.';
      echo "\n";
    }
    $stmt->close();
  }
  
}


?>

</html>