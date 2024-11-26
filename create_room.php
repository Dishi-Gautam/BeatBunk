<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "mini", "beatbunk");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $room_name = $_POST['room_name'];
    $genre = $_POST['genre'];
    $description = $_POST['description'];
    
    // Prepare and execute the SQL query to insert the room
    $query = "INSERT INTO rooms (user_id, room_name, genre, description) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("isss", $user_id, $room_name, $genre, $description);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Room created successfully!";
            header("Location: homepage.php");
            exit();
        } else {
            $_SESSION['error'] = "Failed to create room. Please try again.";
        }
        
        $stmt->close();
    } else {
        $_SESSION['error'] = "Error preparing the statement: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Room - BeatBunk</title>
    <link rel="stylesheet" href="create_room.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <nav class="navbar">
        <img src="./assets/logo.png" id="beatbunk-logo">
        <ul class="nav-links">
            <li><a href="homepage.php">Home</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="create-room">
    <div class="form-container">
        <h1>Create a New Room</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST" action="create_room.php">
            <label for="room_name">Room Name:</label>
            <input type="text" id="room_name" name="room_name" required>

            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <button type="submit">Create Room</button>
        </form>
    </div>
</section>

<footer>
    <div class="footer-content">
        <div class="footer-logo">
            <a href="#" class="logo">Beatbunk</a>
        </div>
        <ul class="footer-links">
            <li><a href="#about">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Beatbunk. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>
