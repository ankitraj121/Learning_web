<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user input from the login form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection configuration
    $dbHost = 'your_db_host';
    $dbUser = 'your_db_username';
    $dbPass = 'your_db_password';
    $dbName = 'your_db_name';

    // Create a database connection
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare a SQL query to retrieve the hashed password based on the provided username
    $sql = "SELECT user_id, username, password_hash FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user with the provided username exists
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        $hashed_password = $row['password_hash'];

        // Verify the password using password_verify
        if (password_verify($password, $hashed_password)) {
            // Successful login; set up a session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header("Location: welcome.php"); // Redirect to the welcome page
            exit();
        } else {
            $error_message = "Login failed. Please check your credentials.";
        }
    } else {
        $error_message = "Username not found. Please register or try again.";
    }

    // Close the database connection and the prepared statement
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="assets/css/styles1.css">
</head>

<body>
    <div class="wrap">
        <div class="avatar">
            <img src="assets/img/home.png" alt="Home Icon">
        </div>
        <form id="login-form" method="POST" action="login.php">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <div class="bar">
                <i></i>
            </div>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <a href="#" class="forgot_link">Forgot?</a>
            <button type="submit">Sign in</button>
            <p>New here? <a href="
