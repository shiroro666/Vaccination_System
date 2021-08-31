<!DOCTYPE html>
<html>
<title>Sign up for Provider</title>

<?php

include "connectdb.php";

//if the user is already logged in, redirect them back to homepage
if(isset($_SESSION["username"])) {
  echo "You are already logged in. \n";
  echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
  header("refresh: 3; index.php");
}
else {
  //if the user have entered both entries in the form, check if they exist in the database
  if(isset($_POST["username"]) && isset($_POST["password"])) {
    if ($stmt = $mysqli->prepare("select providerId, name, password from provider where providerId = ? and password = ?")) {
      $stmt->bind_param("ss", $_POST["username"], $_POST["password"]);
      $stmt->execute();
      $stmt->bind_result($userid, $username, $password);
        if ($stmt->fetch()) {
      $_SESSION["user_id"] = $userid;
      $_SESSION["username"] = $username;
      $_SESSION["password"] = $password;
      $_SESSION["REMOTE_ADDR"] = $_SERVER["REMOTE_ADDR"];
          echo "Login successful. \n";
          echo "You will be redirected in 3 seconds or click <a href=\"appointment.php\">here</a>.";
          header("refresh: 3; appointment.php");
        }
    else {
      sleep(1);
      echo "Your username or password is incorrect, click <a href=\"signup.php\">here</a> to try again.";
    }
      $stmt->close();
    $mysqli->close();
    }  
  }
  //if not then display login form
  else {
    echo "Enter your username and password below: <br /><br />\n";
    echo '<form action="signup.php" method="POST">';
  echo "\n";
    echo 'Username: <input type="text" name="username" /><br />';
  echo "\n";
    echo 'Password: <input type="password" name="password" /><br />';
  echo "\n";
    echo '<input type="submit" value="Submit" />';
  echo "\n";
    echo '</form>';
  echo "\n";
  echo '<br /><a href="index.php">Go back</a>';
  }
}
?>

</html>