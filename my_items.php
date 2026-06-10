<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    die("Please login first. <a href='login.php'>Go to Login</a>");
}

$conn = mysqli_connect("localhost", "root", "", "item_data");

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];

// Fetch items for the logged-in user from item_db table
$sql = "SELECT * FROM items WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

if(!$result){
    die("Query Error: " . mysqli_error($conn));
}

$items = mysqli_fetch_all($result, MYSQLI_ASSOC);

function mark_found($item_id){
    global $conn, $user_id;

    $item_id = mysqli_real_escape_string($conn, $item_id);
    $query = "UPDATE items SET status = 'found' WHERE id = '$item_id' AND user_id = '$user_id'";
    mysqli_query($conn, $query);
}

if(isset($_POST['m_found']) && !empty($_POST['item_id'])){
    mark_found($_POST['item_id']);
    header('Location: my_items.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Items</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .item-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            background-color: #fafafa;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .item-card h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        .item-card p {
            margin: 8px 0;
            color: #555;
        }
        .item-card strong {
            color: #333;
        }
        .no-items {
            text-align: center;
            color: #999;
            padding: 40px;
            font-size: 18px;
        }
        .logout-link {
            margin-top: 20px;
        }
        .logout-link a {
            background-color: #e74c3c;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
        .logout-link a:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Items</h1>
        <p>Welcome, <?php echo $_SESSION['name']; ?>!</p>
        
        <?php
        if(count($items) > 0){
            echo "<div class='items-grid'>";
            foreach($items as $item){
                echo "<div class='item-card'>";
                echo "<h3>" . htmlspecialchars($item['title']) . "</h3>";
                echo "<p><strong>Description:</strong> " . htmlspecialchars($item['description'] ?? 'N/A') . "</p>";
                echo "<p><strong>Price:</strong> $" . htmlspecialchars($item['price'] ?? '0') . "</p>";
                echo "<p><strong>Category:</strong> " . htmlspecialchars($item['category'] ?? 'N/A') . "</p>";
                echo "<p><strong>Status:</strong> " . htmlspecialchars($item['status'] ?? 'N/A') . "</p>";
                echo "<form method='post'>
                    <input type='hidden' name='item_id' value='" . htmlspecialchars($item['id']) . "'>
                    <button type='submit' name='m_found'>Mark as found</button>
                </form>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div class='no-items'>No items found. Add your first item!</div>";
        }
        ?>
        
        <div class="logout-link">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>