<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get username and password from POST request and trim whitespace
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check for empty fields
    if (empty($username) || empty($password)) {
        echo "Username or password cannot be empty.";
        exit();
    }

    // Database credentials
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "beatbunk";

    // Create a database connection
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    // Check the database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM login WHERE username = ? AND password = ?");
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        $conn->close();
        exit();
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Debugging output for number of rows found
    echo "Rows found: " . $result->num_rows . "<br>";

    // Check if the query returned a match
    if ($result->num_rows == 1) {
        echo "Login successful. Redirecting...";
        header("Location: index.html"); // Redirect to the homepage
        exit();
    } else {
        echo "Invalid username or password. Redirecting to error page...";
        header("Location: error.html"); // Redirect to error page
        exit();
    }

    // Close the statement and connection
    
    
}


