<?php
// Start or resume a session
session_start();

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid form submission.";
    header("Location: homepage.php");
    exit();
}

// Connect to the database
$con = mysqli_connect("localhost", "root", "mini", "beatbunk");

// Check the connection
if (mysqli_connect_errno()) {
    $_SESSION['error'] = "Failed to connect to the database.";
    header("Location: homepage.php");
    exit();
}

// Retrieve form data (with a fallback to avoid errors)
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate form data
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $_SESSION['error'] = "All fields are required!";
    header("Location: homepage.php");
    exit();
}

if ($password !== $confirm_password) {
    $_SESSION['error'] = "Passwords do not match!";
    header("Location: homepage.php");
    exit();
}

$sql_check = "SELECT id FROM users WHERE username = ? OR email = ?";
$stmt_check = mysqli_prepare($con, $sql_check);

if ($stmt_check) {
    mysqli_stmt_bind_param($stmt_check, "ss", $username, $email);
    mysqli_stmt_execute($stmt_check);
    $result = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "Username or email already exists!";
        mysqli_stmt_close($stmt_check);
        header("Location: homepage.php");
        exit();
    }
    mysqli_stmt_close($stmt_check);
}


$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$sql = "INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())";
$stmt = mysqli_prepare($con, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
        
        header("Location: homepage.php");
        exit();
    } else {
        $_SESSION['error'] = "Error occurred while creating your account. Please try again.";
    }
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['error'] = "Error preparing the query. Please try again later.";
}

mysqli_close($con);


header("Location: homepage.php");
exit();
?>
