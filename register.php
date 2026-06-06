<?php
$conn = mysqli_connect("localhost","root","","user");

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $uName = mysqli_real_escape_string($conn, $_POST["u_name"]);
    $uPassword = mysqli_real_escape_string($conn, $_POST["password"]);
    $uEmail = mysqli_real_escape_string($conn, $_POST["u_email"]);
    $role = "user";
    
    if ($uName == "" || $uPassword == "" || $uEmail == ""){
        echo "Please fill all credentials"; 
    }
    else{
        $sql = "INSERT INTO user_data(name, role, email, passkey) VALUES('$uName', '$role', '$uEmail', '$uPassword')";
        if(mysqli_query($conn, $sql)){
            echo "Registered successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="register.php" method="post">
        <label for="u_name">Username</label>
        <input type="text" name="u_name" placeholder="enter student name">
        <label for="u_email">Email</label>
        <input type="email" name="u_email" id="" placeholder="enter email address">
        <label for="password">Password</label>
        <input type="password" name="password" id="" placeholder="enter your password">
        <label for="submit">Submit</label>
        <button type="submit" name="submit">Submit</button>
    </form>
    <a href="login.php">login here</a>
</body>
</html>