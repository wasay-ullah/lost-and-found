<?php
session_start();
if (isset($_SESSION["name"])){
    session_destroy();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Logout - Lost & Found System</title>
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
                max-width: 400px;
                width: 100%;
                padding: 40px;
                text-align: center;
            }

            .icon {
                font-size: 48px;
                margin-bottom: 20px;
            }

            h1 {
                color: #333;
                margin-bottom: 10px;
                font-size: 28px;
            }

            .message {
                color: #666;
                margin-bottom: 30px;
                font-size: 16px;
                line-height: 1.6;
            }

            .success {
                color: #27ae60;
                padding: 12px;
                background: #d5f4e6;
                border-radius: 6px;
                margin-bottom: 20px;
            }

            a {
                display: inline-block;
                margin-top: 20px;
                padding: 12px 30px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                text-decoration: none;
                border-radius: 6px;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            a:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            }

            @media (max-width: 480px) {
                .container {
                    padding: 25px;
                }

                h1 {
                    font-size: 24px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="icon">👋</div>
            <h1>Logged Out</h1>
            <div class="success">You have been logged out successfully</div>
            <p class="message">Thank you for using the Lost & Found System. See you next time!</p>
            <a href="login.php">Return to Login</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}
else{
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error - Lost & Found System</title>
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
                max-width: 400px;
                width: 100%;
                padding: 40px;
                text-align: center;
            }

            .icon {
                font-size: 48px;
                margin-bottom: 20px;
            }

            h1 {
                color: #e74c3c;
                margin-bottom: 10px;
                font-size: 28px;
            }

            .message {
                color: #666;
                margin-bottom: 30px;
                font-size: 16px;
                line-height: 1.6;
            }

            .error {
                color: #c0392b;
                padding: 12px;
                background: #fadbd8;
                border-radius: 6px;
                margin-bottom: 20px;
            }

            a {
                display: inline-block;
                margin-top: 20px;
                padding: 12px 30px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                text-decoration: none;
                border-radius: 6px;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            a:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            }

            @media (max-width: 480px) {
                .container {
                    padding: 25px;
                }

                h1 {
                    font-size: 24px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="icon">⚠️</div>
            <h1>Error</h1>
            <div class="error">You must be logged in first</div>
            <p class="message">Please log in to access the system.</p>
            <a href="login.php">Go to Login</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}
?>