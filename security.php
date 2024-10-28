<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;

$admin_id = $_SESSION['admin_id'] ?? null;

if (!isset($user_id) && !isset($admin_id)) {
    header('location:login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    setcookie('securityLevel', $_POST['securityLevel'], time() + (86400 * 30), "/");

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookly Security</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin_style.css">

    <style>
        body {
            font-size: 20px;
        }

        .title-left {
            font-size: 25px;
            margin-bottom: 10px;
        }

        .current-security-level {
            color: red;
        }

        .details {
            margin: 8px 0px;
            font-size: 20px;
            width: 70vw;
        }

        .security-info {
            margin-left: 40px;
        }

        .security-level-form,
        select {
            margin: 30px 0px;
            font-size: 20px;
        }

        select {
            margin-left: 7px;
            padding: 7px 8px;
            border: 1px solid gray;
            border-radius: 5px;
        }

        button {
            margin-left: 15px;
            padding: 12px 12px;
            font-size: 16px;
            background-color: #8e44ad;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>

</head>

<body>

    <body>

        <?php
        if (isset($user_id)) {
            include 'header.php';
        } elseif (isset($admin_id)) {
            include 'admin_header.php';
        }
        ?>

        <div class="heading">
            <h3>Security</h3>
            <p> <a href="index.php">Home</a> / Security </p>
        </div>

        <div class="security-info">
            <p class="title-left"><strong>Security Level</strong></p>
            <p class="current-security-level">Security level is currently: <strong>
                    <?php
                    echo isset($_COOKIE['securityLevel']) ? ucfirst($_COOKIE['securityLevel']) : 'High';
                    ?>
                </strong></p>

            <p class="details">You can set the security level to Low and High. The security level changes the
                vulnerability level of
                Bookly:
            </p>

            <ul>
                <li class="details"><strong>Low</strong> - This level is completely vulnerable, with no security
                    measures in place. It
                    serves as an example of how poor coding practices can lead to web application vulnerabilities,
                    providing
                    a platform for teaching or learning basic exploitation techniques.</li>
                <li class="details"><strong>High</strong> - This level introduces stronger security practices, making it
                    significantly
                    harder to exploit vulnerabilities. While some weaknesses may still exist, they are much more
                    difficult
                    to exploit, similar to challenges found in Capture The Flag (CTF) competitions.</li>
            </ul>
            <form method="POST" class="security-level-form">
                <label for="securityLevel">Set Security Level:</label>
                <select name="securityLevel" id="securityLevel">
                    <option value="low" <?php if (isset($_COOKIE['securityLevel']) && $_COOKIE['securityLevel'] == 'low')
                        echo 'selected'; ?>>Low</option>
                    <option value="high" <?php if (isset($_COOKIE['securityLevel']) && $_COOKIE['securityLevel'] == 'high')
                        echo 'selected';
                    elseif (!isset($_COOKIE['securityLevel']))
                        echo 'selected'; ?>>High</option>
                </select>
                <button type="submit">Set Security Level</button>
            </form>
        </div>


        <?php include 'footer.php'; ?>

        <!-- custom js file link  -->

        <?php
        if (isset($user_id)) {
            echo '<script src="js/script.js"></script>';
        } elseif (isset($admin_id)) {
            echo '<script src="js/admin_script.js"></script>';
        }
        ?>


    </body>

</html>