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

// Handle accept/reject actions
if(isset($_POST['action'])){
    $claim_id = mysqli_real_escape_string($conn, $_POST['claim_id']);
    $action = mysqli_real_escape_string($conn, $_POST['action']);
    $item_id = mysqli_real_escape_string($conn, $_POST['item_id']);
    
    // Verify the claim belongs to user's item
    $verify_sql = "
    SELECT c.id FROM claims c
    JOIN items i ON c.item_id = i.id
    WHERE c.id = '$claim_id' AND i.user_id = '$user_id'
    ";
    $verify_result = mysqli_query($conn, $verify_sql);
    
    if(mysqli_num_rows($verify_result) > 0){
        if($action == 'accept'){
            // Update claim status to accepted
            $update_claim = "UPDATE claims SET status = 'accepted' WHERE id = '$claim_id'";
            mysqli_query($conn, $update_claim);
            
            // Update item status to found
            $update_item = "UPDATE items SET status = 'found' WHERE id = '$item_id'";
            mysqli_query($conn, $update_item);
        } else if($action == 'reject'){
            // Update claim status to rejected
            $update_claim = "UPDATE claims SET status = 'rejected' WHERE id = '$claim_id'";
            mysqli_query($conn, $update_claim);
        }
        
        // Redirect to refresh page
        header('Location: my_claims.php');
        exit();
    }
}

// Fetch all claims for user's items
$sql = "
SELECT c.*, i.title, i.category, i.image, u.name AS finder_name, u.email AS finder_email
FROM claims c
JOIN items i ON c.item_id = i.id
JOIN user.user_data u ON c.finder_id = u.id
WHERE i.user_id = '$user_id'
ORDER BY c.id DESC
";


$result = mysqli_query($conn, $sql);

if(!$result){
    die("Query Error: " . mysqli_error($conn));
}

$claims = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Claims - Lost & Found</title>
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
            align-items: center;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .btn-logout {
            background-color: #e74c3c;
            color: white;
        }

        .btn-logout:hover {
            background-color: #c0392b;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 32px;
            color: #2c3e50;
        }

        .back-btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #2980b9;
        }

        .claims-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 30px;
        }

        .claim-card {
            border: 1px solid #ecf0f1;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #f9f9f9;
            transition: box-shadow 0.3s;
        }

        .claim-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .claim-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
            gap: 15px;
            flex-wrap: wrap;
        }

        .claim-title {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
        }

        .claim-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-accepted {
            background-color: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .claim-item-info {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .claim-item-info p {
            margin: 8px 0;
            color: #555;
        }

        .claim-item-image {
            max-width: 150px;
            border-radius: 6px;
            margin-top: 10px;
        }

        .finder-info {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border-left: 4px solid #3498db;
        }

        .finder-info h4 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .finder-info p {
            margin: 5px 0;
            color: #555;
        }

        .claim-message {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border-left: 4px solid #27ae60;
        }

        .claim-message h4 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .claim-message p {
            color: #555;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        .claim-image {
            max-width: 200px;
            border-radius: 6px;
            margin-top: 10px;
        }

        .claim-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .action-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-accept {
            background-color: #27ae60;
            color: white;
        }

        .btn-accept:hover {
            background-color: #229954;
            transform: translateY(-2px);
        }

        .btn-reject {
            background-color: #e74c3c;
            color: white;
        }

        .btn-reject:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        .btn-accept:disabled,
        .btn-reject:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .no-claims {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }

        .no-claims h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .no-claims p {
            color: #95a5a6;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            .claim-header {
                flex-direction: column;
            }

            .claim-actions {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">Marketplace</div>
            <nav class="nav-links">
                <a href="home_page.php">Home</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="my_items.php">My Items</a>
            </nav>
            <div class="auth-links">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?>!</span>
                <a href="logout.php" class="btn btn-logout">Logout</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="page-header">
            <h1>Claims on My Items</h1>
            <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
        </div>

        <div class="claims-container">
            <?php
            if(count($claims) > 0){
                foreach($claims as $claim){
                    $status_class = 'status-' . strtolower($claim['status']);
                    $is_accepted = ($claim['status'] == 'accepted');
                    $is_rejected = ($claim['status'] == 'rejected');
                    $is_pending = ($claim['status'] == 'pending');
                    
                    echo "<div class='claim-card'>";
                    
                    // Header with title and status
                    echo "<div class='claim-header'>";
                    echo "<div class='claim-title'>" . htmlspecialchars($claim['title']) . "</div>";
                    echo "<span class='claim-status " . $status_class . "'>" . strtoupper($claim['status']) . "</span>";
                    echo "</div>";
                    
                    // Item info
                    echo "<div class='claim-item-info'>";
                    echo "<p><strong>Category:</strong> " . htmlspecialchars($claim['category'] ?? 'N/A') . "</p>";
                    if(!empty($claim['image'])){
                        echo "<img src='uploads/" . htmlspecialchars($claim['image']) . "' alt='Item' class='claim-item-image'>";
                    }
                    echo "</div>";
                    
                    // Finder info
                    echo "<div class='finder-info'>";
                    echo "<h4>Finder Information</h4>";
                    echo "<p><strong>Name:</strong> " . htmlspecialchars($claim['finder_name']) . "</p>";
                    echo "<p><strong>Email:</strong> " . htmlspecialchars($claim['finder_email']) . "</p>";
                    echo "</div>";
                    
                    // Finder's message
                    echo "<div class='claim-message'>";
                    echo "<h4>Finder's Message</h4>";
                    echo "<p>" . htmlspecialchars($claim['message']) . "</p>";
                    if(!empty($claim['image'])){
                        echo "<img src='uploads/" . htmlspecialchars($claim['image']) . "' alt='Claim Image' class='claim-image'>";
                    }
                    echo "</div>";
                    
                    // Action buttons
                    if($is_pending){
                        echo "<div class='claim-actions'>";
                        echo "<form method='POST' style='flex: 1;'>";
                        echo "<input type='hidden' name='claim_id' value='" . htmlspecialchars($claim['id']) . "'>";
                        echo "<input type='hidden' name='item_id' value='" . htmlspecialchars($claim['item_id']) . "'>";
                        echo "<input type='hidden' name='action' value='accept'>";
                        echo "<button type='submit' class='action-btn btn-accept'>Accept Claim</button>";
                        echo "</form>";
                        
                        echo "<form method='POST' style='flex: 1;'>";
                        echo "<input type='hidden' name='claim_id' value='" . htmlspecialchars($claim['id']) . "'>";
                        echo "<input type='hidden' name='item_id' value='" . htmlspecialchars($claim['item_id']) . "'>";
                        echo "<input type='hidden' name='action' value='reject'>";
                        echo "<button type='submit' class='action-btn btn-reject'>Reject Claim</button>";
                        echo "</form>";
                        echo "</div>";
                    }
                    
                    echo "</div>";
                }
            } else {
                echo "<div class='no-claims'>";
                echo "<h2>No Claims Yet</h2>";
                echo "<p>You don't have any claims on your items. When someone finds your item, their claim will appear here.</p>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Marketplace. All rights reserved.</p>
    </footer>
</body>
</html>
