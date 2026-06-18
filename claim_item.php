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

// Get item_id from URL
if(!isset($_GET['item_id'])){
    die("Item ID not found. <a href='home_page.php'>Go back</a>");
}

$item_id = mysqli_real_escape_string($conn, $_GET['item_id']);
$finder_id = $_SESSION['user_id'];

// Fetch item details
$sql = "SELECT * FROM items WHERE id = '$item_id'";
$result = mysqli_query($conn, $sql);

if(!$result || mysqli_num_rows($result) == 0){
    die("Item not found. <a href='home_page.php'>Go back</a>");
}

$item = mysqli_fetch_assoc($result);

// Check if user is trying to claim their own item
if($item['user_id'] == $_SESSION['user_id']){
    die("You cannot claim your own item. <a href='home_page.php'>Go back</a>");
}

// Check if user already has a pending claim for this item
$check_sql = "SELECT * FROM claims WHERE item_id = '$item_id' AND finder_id = '$finder_id' AND status = 'pending'";
$check_result = mysqli_query($conn, $check_sql);

if(mysqli_num_rows($check_result) > 0){
    die("You have already submitted a claim for this item. <a href='home_page.php'>Go back</a>");
}

// Handle form submission
if(isset($_POST['submit_claim'])){
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $image_name = '';
    
    // Handle image upload
    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0){
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $temp_name = $_FILES['image']['tmp_name'];
        $target_path = "uploads/" . $image_name;
        
        if(!move_uploaded_file($temp_name, $target_path)){
            die("Error uploading image. <a href='home_page.php'>Go back</a>");
        }
    }
    
    // Insert claim into database
    $insert_sql = "
    INSERT INTO claims (item_id, finder_id, message, image, status)
    VALUES ('$item_id', '$finder_id', '$message', '$image_name', 'pending')
    ";
    
    if(mysqli_query($conn, $insert_sql)){
        echo "<h2>Claim Submitted Successfully!</h2>";
        echo "<p>Your claim has been submitted and is pending review by the item owner.</p>";
        echo "<a href='home_page.php'>Return to Home</a>";
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Item - Lost & Found</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
            font-size: 28px;
        }

        .item-info {
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .item-info h2 {
            color: #2c3e50;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .item-info p {
            color: #555;
            margin: 8px 0;
        }

        .item-image {
            max-width: 200px;
            border-radius: 6px;
            margin-top: 10px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group textarea,
        .form-group input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-group textarea:focus,
        .form-group input[type="file"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-cancel {
            background: #e0e0e0;
            color: #333;
        }

        .btn-cancel:hover {
            background: #d0d0d0;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>I Found This Item</h1>
        
        <div class="item-info">
            <h2><?php echo htmlspecialchars($item['title']); ?></h2>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category'] ?? 'N/A'); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($item['description'] ?? 'N/A'); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($item['location'] ?? 'N/A'); ?></p>
            <?php if(!empty($item['image'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="Item" class="item-image">
            <?php endif; ?>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="message">Your Message *</label>
                <textarea 
                    name="message" 
                    id="message" 
                    placeholder="Describe where you found this item, under what circumstances, etc."
                    required
                ></textarea>
            </div>

            <div class="form-group">
                <label for="image">Upload Photo (Optional)</label>
                <input 
                    type="file" 
                    name="image" 
                    id="image"
                    accept="image/*"
                >
            </div>

            <div class="button-group">
                <button type="submit" name="submit_claim" class="btn-submit">Submit Claim</button>
                <button type="button" class="btn-cancel" onclick="window.location.href='home_page.php'">Cancel</button>
            </div>
        </form>

        <div class="back-link">
            <a href="home_page.php">← Back to Home</a>
        </div>
    </div>
</body>
</html>
