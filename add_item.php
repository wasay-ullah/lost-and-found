<?php
session_start();
if (!isset($_SESSION["user_id"])){
header("Location:login.php");
exit();
}

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
$item_status = "missing";
$user_id = $_SESSION["user_id"];
$q_insert_item=("
INSERT INTO items(user_id,item_type,title,description,category,location,status)
VALUES('$user_id','$item_type','$item_title','$item_description','$item_category','$item_location','$item_status')");
if (mysqli_query($connection,$q_insert_item)) {
    echo "item added successfuly";
    header("Location:dashboard.php");
    exit();
}
else {
    echo "not added";
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item - Lost & Found System</title>
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
            max-width: 500px;
            width: 100%;
            padding: 40px;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
            font-size: 28px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
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

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.3s ease;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .radio-group {
            display: flex;
            gap: 30px;
            margin-bottom: 8px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            margin-bottom: 0;
            cursor: pointer;
            font-weight: 500;
        }

        .radio-group input[type="radio"] {
            margin-right: 8px;
            cursor: pointer;
            width: 18px;
            height: 18px;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
        }

        button[type="submit"],
        .btn-back {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button[type="submit"] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            flex: 1;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        .btn-back {
            background: #f0f0f0;
            color: #333;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 1;
        }

        .btn-back:hover {
            background: #e0e0e0;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            color: #999;
            font-size: 12px;
        }

        @media (max-width: 480px) {
            .container {
                padding: 25px;
            }

            h1 {
                font-size: 24px;
            }

            .radio-group {
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Item</h1>
        <p class="subtitle">Report a Lost or Found Item</p>
        
        <form action="" method="post">
            <div class="form-group">
                <label>Item Type</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="item_type" value="lost" required>
                        Lost
                    </label>
                    <label>
                        <input type="radio" name="item_type" value="found" required>
                        Found
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" placeholder="e.g., Blue Backpack" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" placeholder="Provide detailed description of the item..." required></textarea>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select name="category" id="category" required>
                    <option value="">Select a category</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Accessories">Accessories</option>
                    <option value="Clothing">Clothing</option>
                    <option value="Documents">Documents</option>
                    <option value="Keys">Keys</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" placeholder="Where was it lost/found?" required>
            </div>

            <div class="button-group">
                <button type="submit" name="add_item">Submit Item</button>
            </div>
        </form>

        <div class="divider">
            <a href="dashboard.php" style="color: #667eea; text-decoration: none;">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>