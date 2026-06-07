<?php
session_start();

$connection = mysqli_connect("localhost","root","","item_data");

if(!$connection){
    die("Connection Failed: " . mysqli_connect_error());
}
if (isset($_POST["add_item"])) {
$item_type = $_POST["item_type"];
$item_title = $_POST["title"];
$item_description = $_POST["description"];
$item_category = $_POST["category"];
$item_location = $_POST["location"];
$item_status = "Pending";
$user_id = $_SESSION["user_id"];
$q_insert_item=("
INSERT INTO items(user_id,item_type,title,description,category,location,status)
VALUES('$user_id','$item_type','$item_title','$item_description','$item_category','$item_location','$item_status')");
mysqli_query($connection,$q_insert_item);
if (mysqli_query($connection,$q_insert_item)) {
    echo "item added successfuly";
}
else {
    echo "not added";
}
}
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <form action="" method="post">
        <p>select item type</p>
        <label for="lost">Lost</label>
        <input type="radio" name="item_type" value="lost">
        <label for="found">Found</label>
        <input type="radio" name="item_type" value="found">
        <label for="title">Title</label>
        <input type="text" name="title" id="">
        <label for="description">Description</label>
        <input type="text" name="description" id="">
        <label for="category">Category</label>
        <input type="text" name="category" id="">
        <label for="location">location</label>
        <input type="text" name="location" id="">
        <button type="submit" name="add_item">Submit</button>
    </form>
</body>
</html>