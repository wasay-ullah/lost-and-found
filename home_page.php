<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "item_data");

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

// Fetch all items from item_db table
$sql = "SELECT * FROM items";
$result = mysqli_query($conn, $sql);

if(!$result){
    die("Query Error: " . mysqli_error($conn));
}

$items = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Marketplace</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        
        header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        
        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: #3498db;
        }
        
        .auth-links {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }
        
        .btn-login {
            background-color: #3498db;
            color: white;
        }
        
        .btn-login:hover {
            background-color: #2980b9;
        }
        
        .btn-logout {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn-logout:hover {
            background-color: #c0392b;
        }
        
        .btn-myitems {
            background-color: #27ae60;
            color: white;
        }
        
        .btn-myitems:hover {
            background-color: #229954;
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
            color: white;
            padding: 40px;
            border-radius: 8px;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .hero-section h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .hero-section p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .section-title {
            font-size: 28px;
            margin-bottom: 30px;
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .item-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        
        .item-header {
            background-color: #ecf0f1;
            padding: 15px;
            border-bottom: 2px solid #bdc3c7;
        }
        
        .item-header h3 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .item-category {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .item-body {
            padding: 15px;
        }
        
        .item-body p {
            margin: 10px 0;
            line-height: 1.5;
            color: #555;
        }
        
        .item-seller {
            font-size: 13px;
            color: #7f8c8d;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .item-price {
            font-size: 22px;
            font-weight: bold;
            color: #27ae60;
            margin: 15px 0;
        }
        
        .item-date {
            font-size: 12px;
            color: #95a5a6;
            margin-top: 10px;
        }
        
        .no-items {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 8px;
        }
        
        .no-items h2 {
            color: #7f8c8d;
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        .no-items p {
            color: #95a5a6;
        }
        
        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">Marketplace</div>
            <nav class="nav-links">
                <a href="home_page.php">Home</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="my_items.php">My Items</a>
                <?php endif; ?>
            </nav>
            <div class="auth-links">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>!</span>
                    <a href="logout.php" class="btn btn-logout">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-login">Login</a>
                    <a href="register.php" class="btn btn-login">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <div class="container">
        <div class="hero-section">
            <h1>Welcome to Our Marketplace</h1>
            <p>Browse and discover amazing items available from our community</p>
        </div>
        
        <div class="section-title">
            Available Items (<?php echo count($items); ?>)
        </div>
        
        <?php
        if(count($items) > 0){
            echo "<div class='items-grid'>";
            foreach($items as $item){
                echo "<div class='item-card'>";
                echo "<div class='item-header'>";
                echo "<h3>" . htmlspecialchars($item['title'] ?? 'Unnamed Item') . "</h3>";
                if(!empty($item['category'])){
                    echo "<span class='item-category'>" . htmlspecialchars($item['category']) . "</span>";
                }
                echo "</div>";
                echo "<div class='item-body'>";
                if(!empty($item['description'])){
                    echo "<p><strong>Description:</strong><br>" . nl2br(htmlspecialchars($item['description'])) . "</p>";
                }
                echo "<div class='item-price'>$" . htmlspecialchars($item['price'] ?? '0') . "</div>";
                if(!empty($item['seller_name'])){
                    echo "<div class='item-seller'><strong>Seller:</strong> " . htmlspecialchars($item['seller_name']) . "</div>";
                }
                if(!empty($item['created_at'])){
                    echo "<div class='item-date'>Posted: " . date('M d, Y', strtotime($item['created_at'])) . "</div>";
                }
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div class='no-items'>";
            echo "<h2>No Items Available</h2>";
            echo "<p>Check back later for new items!</p>";
            echo "</div>";
        }
        ?>
    </div>
    
    <footer>
        <p>&copy; 2026 Marketplace. All rights reserved.</p>
    </footer>
</body>
</html>
