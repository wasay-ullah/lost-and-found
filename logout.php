<?php
session_start();
if (isset($_SESSION["name"])){
session_destroy();
echo"Logged out successfully";
}
else{
    echo "login first";
}
?>