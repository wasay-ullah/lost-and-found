<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}
else
{
    echo"welcome ".$_SESSION['name'];
}
?>
<a href="add_item.php">Add items</a>
<a href="my_items.php">My items</a>
<a href="logout.php">Logout</a>