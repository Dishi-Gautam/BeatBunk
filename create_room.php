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

// Function to generate a unique room code
function generateRoomCode($conn) {
    do {
        $roomCode = strtoupper(bin2hex(random_bytes(3))); // 6-character random code
        $checkQuery = "SELECT id FROM rooms WHERE room_code = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $roomCode);
        $stmt->execute();
        $stmt->store_result();
    } while ($stmt->num_rows > 0); // Repeat until unique code is generated
    $stmt->close();
    return $roomCode;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $room_name = trim($_POST['room_name']);
    $genre = trim($_POST['genre']);
    $description = trim($_POST['description']);

    // Validate inputs
    if (empty($room_name) || empty($genre) || empty($description)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: create_room.php");
        exit();
    }

    // Generate a unique room code
    $room_code = generateRoomCode($conn);

    // Insert the new room into the database
    $query = "INSERT INTO rooms (user_id, room_name, genre, description, room_code) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("issss", $user_id, $room_name, $genre, $description, $room_code);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Room created successfully! Share the code with your friends: $room_code";
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
        <?php elseif (isset($_SESSION['success'])): ?>
            <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
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
            