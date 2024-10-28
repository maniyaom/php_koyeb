<?php

// Include database connection file (update config.php with your own database connection details)
include 'config.php';
include 'functions.php';
session_start();

// Check if user is already logged in
if(isset($_SESSION['user_id'])){
    header('location:index.php');
    exit();
}
else if(isset($_SESSION['admin_id'])){
    header('location:admin_page.php');
    exit();
}

// Check if form is submitted
if(isset($_POST['submit'])){

    // Get user input from the form
    $email = $_POST['email'];
    $password = $_POST['password'];  // Storing the raw password for simplicity (vulnerable)

    if (detectIntrusion($email) === 'Intrusion Detected') {
        header('location:unauthorized_access.html');
        exit();
    }

    // Vulnerable SQL query that directly uses user input
    $sql = "SELECT * FROM `users` WHERE email = '$email' AND password = '$password'";
   
    // Execute query (vulnerable to SQL injection)
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    // Check if the query returns any rows
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);

        // Set session variables based on user type
        if($row['user_type'] == 'admin'){
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];
            header('location:admin_page.php');
            exit;
        } elseif($row['user_type'] == 'user'){
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
            header('location:index.php');
            exit;
        }
    } else {
        // If login fails, display error message
        echo "Incorrect email or password!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="form-container">
        <form action="" method="post">
            <h3>Login Now</h3>
            <input type="text" name="email" placeholder="Enter your email" required class="box">
            <input type="password" name="password" placeholder="Enter your password" required class="box">
            <input type="submit" name="submit" value="Login Now" class="btn">
            <p>Don't have an account? <a href="register.php">Register Now</a></p>
        </form>
    </div>

</body>
</html>
