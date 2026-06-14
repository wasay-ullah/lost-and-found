<?php
session_start();
if(!isset($_SESSION["user_id"])){
header("Location:../login.php");
exit();
}
if($_SESSION['role'] != 'admin')
{
    header("Location: ../dashboard.php");
    exit();
}
$conn = mysqli_connect("localhost","root","","item_data");
$sql = "SELECT * FROM items";
$result = mysqli_query($conn, $sql);

if(!$result){
    die("Query Error: " . mysqli_error($conn));
}

$items = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<?php
if (isset($_POST["item_id"])){
$id=$_POST["item_id"];
$del_sql = "DELETE FROM items WHERE id = $id";
$result = mysqli_query($conn, $del_sql);
header("Location: item_details.php");
exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 10px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .back-link {
            margin-bottom: 20px;
        }
        .back-link a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="back-link">
        <a href="a_dashboard.php">← Back to Dashboard</a>
    </div>
    <h1>Item Details</h1>
    <?php
    echo"<table>";
    echo"<tr>";
    echo "<th>User ID</th>";
    echo "<th>Item ID</th>";
    echo "<th>Title</th>";
    echo "<th>Category</th>";
    echo "<th>Status</th>";
    echo "</tr>";
    foreach ($items as $item) {
        echo"<tr>";
        echo "<td>".htmlspecialchars($item['user_id'] ?? 'N/A')."</td>";
        echo "<td>".htmlspecialchars($item['id'] ?? 'N/A')."</td>";
        echo "<td>".htmlspecialchars($item['title'] ?? 'N/A')."</td>";
        echo "<td>".htmlspecialchars($item['category'] ?? 'N/A')."</td>";
        echo "<td>".htmlspecialchars($item['status'] ?? 'N/A')."</td>";
        echo"</tr>";
    }
    echo"</table>";
    ?>
    <form method="post">
    <input type="number" name="item_id" id="">
    <button type="submit">Delete Item</button>
    </form>
</body>
</html>