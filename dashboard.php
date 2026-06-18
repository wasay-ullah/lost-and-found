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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Lost & Found System</title>
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
            padding: 20px;
        }

        .navbar {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .navbar h1 {
            color: #333;
            font-size: 24px;
            margin: 0;
        }

        .welcome {
            color: #667eea;
            font-weight: 600;
        }

        .nav-links {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .nav-links a {
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .nav-links a.add-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .nav-links a.add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .nav-links a.items-btn {
            background: #f0f0f0;
            color: #333;
        }

        .nav-links a.items-btn:hover {
            background: #e0e0e0;
        }

        .nav-links a.logout-btn {
            background: #e74c3c;
            color: white;
        }

        .nav-links a.logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .nav-links a.claims-btn {
            background: #9b59b6;
            color: white;
        }

        .nav-links a.claims-btn:hover {
            background: #8e44ad;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .welcome-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-bottom: 30px;
            text-align: center;
        }

        .welcome-section h2 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .welcome-section p {
            color: #666;
            font-size: 16px;
        }

        .user-name {
            color: #667eea;
            font-weight: 700;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .action-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .action-card .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .action-card h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 20px;
        }

        .action-card p {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .action-card a {
            display: inline-block;
            padding: 10px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .action-card a:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .stats-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 30px;
        }

        .stats-section h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }

        .stat-item {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-item .number {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-item .label {
            font-size: 14px;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                text-align: center;
            }

            .navbar h1 {
                width: 100%;
                margin-bottom: 10px;
            }

            .nav-links {
                width: 100%;
                justify-content: center;
            }

            .welcome-section {
                padding: 25px;
            }

            .welcome-section h2 {
                font-size: 24px;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Lost & Found System</h1>
        <div class="nav-links">
            <a href="add_item.php" class="add-btn">+ Add Item</a>
            <a href="my_items.php" class="items-btn">My Items</a>
            <a href="my_claims.php" class="claims-btn">My Claims</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="welcome-section">
            <h2>Welcome back, <span class="user-name"><?php echo $_SESSION['name']; ?></span>!</h2>
            <p>Manage your lost and found items in one place</p>
        </div>

        <div class="quick-actions">
            <div class="action-card">
                <div class="icon">📝</div>
                <h3>Add New Item</h3>
                <p>Report a lost or found item to the system</p>
                <a href="add_item.php">Report Item</a>
            </div>

            <div class="action-card">
                <div class="icon">📋</div>
                <h3>My Items</h3>
                <p>View and manage all your reported items</p>
                <a href="my_items.php">View Items</a>
            </div>

            <div class="action-card">
                <div class="icon">🔍</div>
                <h3>Browse All</h3>
                <p>Search through all lost and found items</p>
                <a href="home_page.php">Browse</a>
            </div>

            <div class="action-card">
                <div class="icon">✅</div>
                <h3>My Claims</h3>
                <p>Review claims from finders on your items</p>
                <a href="my_claims.php">View Claims</a>
            </div>
        </div>

        <div class="stats-section">
            <h3>Quick Stats</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="number">0</div>
                    <div class="label">Items Posted</div>
                </div>
                <div class="stat-item">
                    <div class="number">0</div>
                    <div class="label">Pending</div>
                </div>
                <div class="stat-item">
                    <div class="number">0</div>
                    <div class="label">Resolved</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>