
<?php
$mysqli = new mysqli("127.0.0.1", "root", "980718", "p", "3306");
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
session_start();
if(isset($SESSION["REMOTE_ADDR"]) && $SESSION["REMOTE_ADDR"] != $SERVER["REMOTE_ADDR"]) {
  session_destroy();
  session_start();
}

?>
