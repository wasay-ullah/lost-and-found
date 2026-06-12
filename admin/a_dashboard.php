<?php 
$conn = mysqli_connect("localhost", "root", "", "user");
$conn2 = mysqli_connect("localhost", "root", "", "item_data");

if (!$conn || !$conn2) {
    die("Database connection failed: " . mysqli_connect_error());
}

$query = "SELECT COUNT(*) AS total_users FROM user_data WHERE role='user'";
$query2 = "SELECT COUNT(*) AS total_items FROM items";

$result_user = mysqli_query($conn, $query);
$result_items = mysqli_query($conn2, $query2);

if (!$result_user || !$result_items) {
    die("Query failed: " . mysqli_error($conn) . " / " . mysqli_error($conn2));
}

$row_user = mysqli_fetch_assoc($result_user);
$row_items = mysqli_fetch_assoc($result_items);

$total_user = $row_user['total_users'] ?? 0;
$total_items = $row_items['total_items'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin dashboard</h1>
    <p>Total users = <?php echo $total_user; ?></p>
    <p>Total items = <?php echo $total_items; ?></p>
    <button type="button"><a href="item_details.php">Items details</a></button>
    <button type="button"><a href="user_info.php">User information</a></button>
    <a href="../logout.php">Logout</a>
</body>
</html>