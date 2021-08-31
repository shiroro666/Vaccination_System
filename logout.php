<!DOCTYPE html>
<html>
<title>Logout</title>

<?php
session_start();
session_destroy();
echo "Goodbye! You are logged out. The page will refresh in 3 seconds";
  header("refresh: 3; index.php");
?>

</html>