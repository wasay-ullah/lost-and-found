<?php
session_start();
if (isset($_SESSION["name"])){
session_destroy();
echo"Logged out successfully";
header("Location: login.php");
exit();
}
else{
    echo "login first";
}
?>