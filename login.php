<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if both fields are not empty
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Both fields are required!";
        header("Location: login.html");
        exit();
    }

    // Connect to the database
    $con = mysqli_connect("localhost", "root", "mini", "beatbunk");

    // Check the connection
    if (mysqli_connect_errno()) {
        $_SESSION['error'] = "Failed to connect to the database.";
        //header("Location: error.html");
        exit();
    }

    // Retrieve the user from the database based on the username
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if user exists
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Successful login, start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to the logged-in homepage
            header("Location: homepage.php");
            exit();
        } else {
            $_SESSION['error'] = "Incorrect username or password!";
        }
    } else {
        $_SESSION['error'] = "User not found!";
    }

    // Close the database connection
    mysqli_stmt_close($stmt);
    mysqli_close($con);
}

// If there was an error, redirect to login page
header("Location: login.html");
exit();
?>
